<?php

namespace App\Filament\Reports;

use App\Enums\RoleEnum;
use App\Models\Trail;
use App\Models\User;
use App\Models\UserAnswer;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Header\Layout\HeaderColumn;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Report;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class StudentsReport extends Report
{
    public ?string $heading = "Resultados dos Estudantes";

    public ?string $icon = "phosphor-student-fill";

    public ?string $logo = 'image/logo_light.svg';

    public ?string $group = 'Relatórios';
    public bool $shouldOpenInNewTab = false;
    public ?string $subHeading = "Resultado dos estudantes no jogo";

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                HeaderColumn::make()->schema([
                    Image::make(asset('image/logo_light.svg'))->height(120),
                ]),

                Text::make('Resultado dos estudantes no jogo')
                    ->secondary()
                    ->subTitle(),

                Header\Layout\HeaderRow::make()->schema([
                    Text::make('Relatório gerado em ' . Carbon::now()->format('d/m/Y H:i:s'))
                ])->alignRight()

            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Table::make()
                    ->columns([
                        Body\TextColumn::make('id')
                            ->label('#'),

                        Body\TextColumn::make('estudante')
                            ->label('Estudante'),

                        Body\TextColumn::make('matricula')
                            ->label('Matrícula'),

                        Body\TextColumn::make('score')
                            ->badge()
                            ->alignCenter()
                            ->label('Pontuação Total'),

                        Body\TextColumn::make('trails')
                            ->badge()
                            ->label('Trilhas'),

                        Body\TextColumn::make('num_questions_answers')
                            ->alignCenter()
                            ->label('Total de Respostas'),

                        Body\TextColumn::make('num_questions_answers_with_correct_answer')
                            ->badge()
                            ->alignCenter()
                            ->label('Total de Respostas Certas'),

                    ])
                    ->data($this->getDataQuery())
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                // ...
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('student_filter')
                    ->label('Estudante')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(User::query()
                        ->with('userAnswers')
                        ->whereHas('roles', function ($query) {
                            $query->where('id', RoleEnum::Estudante);
                        })->pluck('name', 'id')->toArray()),
            ]);
    }

    private function getDataQuery(): \Closure
    {
        return function (?array $filters) {
            $data = User::query()
                ->with('userAnswers')
                ->when(isset($filters['student_filter']), function ($query) use ($filters) {
                    $query->whereIn('id', $filters['student_filter']);
                })
                ->whereHas('roles', function ($query) {
                    $query->where('id', RoleEnum::Estudante);
                })->get();

            foreach ($data as $d) {
                $score = UserAnswer::query()
                    ->selectRaw('user_id, sum(score) as score')
                    ->where('user_id', '=', $d['id'])
                    ->groupBy('user_id')
                    ->first()?->score ?? 0;

                $trilhasIds = UserAnswer::query()
                    ->selectRaw(' trail_id as trails')
                    ->where('user_id', '=', $d['id'])
                    ->groupBy('trail_id')
                    ->get();

                $trilhasNames = Trail::query()
                    ->select('description')
                    ->whereIn('id', $trilhasIds)
                    ->pluck('description')->toArray();


                $d['estudante'] = $d->name;
                $d['matricula'] = $d->registration_code;
                $d['score'] = $score;
                $d['trails'] = $trilhasNames;
                $d['num_questions_answers'] = UserAnswer::query()
                    ->where('user_id', '=', $d['id'])
                    ->count();
                $d['num_questions_answers_with_correct_answer'] = UserAnswer::query()
                    ->where('user_id', '=', $d['id'])
                    ->where('selected_correctly', '=', true)
                    ->count();
            }
            return $data ?? collect([]);
        };
    }
}
