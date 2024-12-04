<?php

namespace App\Filament\app\Resources;

use App\Enums\QuestionDifficultyLevelEnum;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'fas-question';

    protected static ?string $navigationGroup = 'Jogo';

    protected static ?string $label = 'Questão';

    protected static ?string $pluralLabel = 'Questões';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('question')
                    ->label('Questão')
                    ->disableToolbarButtons(['attachFiles', 'link'])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\ToggleButtons::make('difficulty_level')
                    ->options(QuestionDifficultyLevelEnum::class)
                    ->label('Nível de Dificuldade')
                    ->inline()
                    ->required(),
                Forms\Components\Repeater::make('answers')
                    ->label('Respostas')
                    ->columnSpanFull()
                    ->grid(2)
                    ->minItems(5)
                    ->defaultItems(5)
                    ->maxItems(5)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->itemLabel(fn (array $state) => "Resposta #" . count($state))
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Descrição')
                            ->required(),
                        Forms\Components\Toggle::make('is_correct')
                            ->distinct()
                            ->fixIndistinctState()
                            ->label('Resposta Correta')

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Questão')
                    ->wrap()
                    ->limit(150)
                    ->html()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('difficulty_level')
                    ->tooltip(fn($state): string => $state->getLabel())
                    ->label('Nível de Dificuldade')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->alignCenter()
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->alignCenter()
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('difficulty_level')
                    ->label('Nível de dificuldade')
                    ->searchable()
                    ->multiple()
                    ->options(QuestionDifficultyLevelEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\app\Resources\QuestionResource\Pages\ListQuestions::route('/'),
            'create' => \App\Filament\app\Resources\QuestionResource\Pages\CreateQuestion::route('/create'),
            'edit' => \App\Filament\app\Resources\QuestionResource\Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
