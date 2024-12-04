<?php

namespace App\Filament\app\Resources\TrailResource\Pages;

use App\Filament\app\Resources\TrailResource;
use App\Models\Question;
use App\Models\Trail;
use App\Models\UserAnswer;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class PlayTrail extends Page
{
    use InteractsWithRecord;
    use InteractsWithForms;

    protected static string $resource = TrailResource::class;
    protected static ?string $slug = 'play';

    protected static string $view = 'filament.resources.trail-resource.pages.play-trail';

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Jogando Trilha - ' . $this->record->description;
    }

    public function getTitle(): string
    {
        return 'Trilha - ' . $this->record->description . ' / ' . $this->record->score . ' pontos';
    }

    public function form(Form $form): Form
    {

        return $form->schema([
            Wizard::make()
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        wire:click="submit"
                        type="submit"
                        size="lg"
                        icon="bi-send-fill"
                        color="warning"
                    >
                        Enviar
                    </x-filament::button>
                BLADE)))
                ->steps([
                    Wizard\Step::make('level-1')
                        ->model(Trail::class)
                        ->schema(function () {
                            $customForm = [];

                            $questions = $this->record->questions()->where('level', 1)->get();
                            foreach ($questions as $key => $question) {
                                $answers = [];
                                foreach ($question->answers as $index => $answer) {
                                    $answers[$index] = $answer['description'];
                                }
                                $customForm[$question->id] = Radio::make('trail' . $this->record->id . '-level1-question' . ($question->id))
                                    ->label(fn(): string => $key + 1 . '. ' . $question->question)
                                    ->required()
                                    ->options($answers);
                            }
                            return $customForm;
                        })
                        ->label('Fase 1')
                        ->icon('tabler-circle-number-1')
                        ->completedIcon('tabler-circle-check'),

                    Wizard\Step::make('level-2')
                        ->model(Trail::class)
                        ->schema(function () {
                            $customForm = [];

                            $questions = $this->record->questions()->where('level', 2)->get();
                            foreach ($questions as $key => $question) {
                                $answers = [];
                                foreach ($question->answers as $index => $answer) {
                                    $answers[$index] = $answer['description'];
                                }
                                $customForm[$question->id] = Radio::make('trail' . $this->record->id . '-level2-question' . ($question->id))
                                    ->label(fn(): string => $key + 1 . '. ' . $question->question)
                                    ->options($answers);
                            }
                            return $customForm;
                        })
                        ->label('Fase 2')
                        ->icon('tabler-circle-number-2')
                        ->completedIcon('tabler-circle-check'),

                    Wizard\Step::make('level-3')
                        ->model(Trail::class)
                        ->schema(function () {
                            $customForm = [];

                            $questions = $this->record->questions()->where('level', 3)->get();
                            foreach ($questions as $key => $question) {
                                $answers = [];
                                foreach ($question->answers as $index => $answer) {
                                    $answers[$index] = $answer['description'];
                                }
                                $customForm[$question->id] = Radio::make('trail' . $this->record->id . '-level3-question' . ($question->id))
                                    ->label(fn(): string => $key + 1 . '. ' . $question->question)
                                    ->options($answers);
                            }
                            return $customForm;
                        })
                        ->label('Fase 3')
                        ->icon('tabler-circle-number-3')
                        ->completedIcon('tabler-circle-check'),

                    Wizard\Step::make('level-4')
                        ->model(Trail::class)
                        ->schema(function () {
                            $customForm = [];

                            $questions = $this->record->questions()->where('level', 4)->get();
                            foreach ($questions as $key => $question) {
                                $answers = [];
                                foreach ($question->answers as $index => $answer) {
                                    $answers[$index] = $answer['description'];
                                }
                                $customForm[$question->id] = Radio::make('trail' . $this->record->id . '-level4-question' . ($question->id))
                                    ->label(fn(): string => $key + 1 . '. ' . $question->question)
                                    ->options($answers);
                            }
                            return $customForm;
                        })
                        ->label('Fase 4')
                        ->icon('tabler-circle-number-4')
                        ->completedIcon('tabler-circle-check'),

                    Wizard\Step::make('level-5')
                        ->model(Trail::class)
                        ->schema(function () {
                            $customForm = [];

                            $questions = $this->record->questions()->where('level', 5)->get();
                            foreach ($questions as $key => $question) {
                                $answers = [];

                                foreach ($question->answers as $index => $answer) {
                                    $answers[$index] = $answer['description'];
                                }

                                $customForm[$question->id] = Radio::make('trail' . $this->record->id . '-level5-question' . ($question->id))
                                    ->label(fn(): string => $key + 1 . '. ' . $question->question)
                                    ->options($answers);
                            }

                            return $customForm;
                        })
                        ->label('Fase 5')
                        ->icon('tabler-circle-number-5')
                        ->completedIcon('tabler-circle-check'),
                ])
        ])
            ->model(Trail::class)
            ->statePath('data');
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            $trail = $this->record;
            $count = 0;
            $level = 1;
            $score = 0;
            $questionNumberCorrect = 0;
            foreach ($this->data as $key => $value) {
                if (preg_match('/question(\d+)/', $key, $matches)) {
                    $question = Question::find($matches[1]);
                    $answer = array_column($question->answers, 'description');
                    $answerCorrect = array_search(true, array_column($question->answers, 'is_correct'));

                    $userAnswer = UserAnswer::create([
                        'user_id' => auth()->user()->id,
                        'question_id' => $question->id,
                        'trail_id' => $trail->id,
                        'level' => $level,
                        'answer_selected' => $question->answers[$this->data['trail' . $trail->id . '-level' . $level . '-question' . $question->id]]['description'],
                        'selected_correctly' => $this->data['trail' . $trail->id . '-level' . $level . '-question' . $question->id] == $answer[$answerCorrect],
                        'score' => $this->data['trail' . $trail->id . '-level' . $level . '-question' . $question->id] == $answer[$answerCorrect] ? $question->difficulty_level : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $score += $userAnswer->score;
                    $questionNumberCorrect += $userAnswer->selected_correctly ? 1 : 0;
                    $count++;
                    $level = $count % 5 == 0 ? $level + 1 : $level;
                }
            }

            DB::commit();

            Notification::make()
                ->success()
                ->title('Trilha respondida com sucesso!')
                ->body('Você acertou ' . $questionNumberCorrect . ' de ' . $count . ' questões e obteve ' . $score . ' pontos.')
                ->send();

            return redirect()->route(TrailResource::getRouteBaseName(). '.index');
        } catch (\Exception $exception) {
            dd($exception, $this->data);
            DB::rollBack();
            Notification::make()
                ->warning()
                ->title('Erro ao responder trilha!')
                ->body($exception->getMessage())
                ->send();
        }
    }
}
