<?php

namespace App\Filament\app\Resources;

use App\Enums\TrailDifficultyLevelEnum;
use App\Filament\Resources\TrailResource\Pages;
use App\Models\Question;
use App\Models\Trail;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrailResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Trail::class;

    protected static ?string $navigationIcon = 'ionicon-trail-sign';

    protected static ?string $navigationGroup = 'Jogo';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Trilha';

    protected static ?string $pluralLabel = 'Trilhas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Nome da Trilha')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ToggleButtons::make('difficulty_level')
                            ->hint(fn($state) => $state == TrailDifficultyLevelEnum::MANUAL->value ? '' : 'O sistema selecionará aleatóriamente as questões da trilha.')
                            ->hintColor('warning')
                            ->hintIcon(fn($state) => $state == TrailDifficultyLevelEnum::MANUAL->value ? '' : 'tabler-alert-triangle')
                            ->options(TrailDifficultyLevelEnum::class)
                            ->label('Nível de Dificuldade')
                            ->disabled(fn($operation) => $operation != 'create')
                            ->live()
                            ->inline()
                            ->required(),
                    ]),

                Forms\Components\Grid::make()
                    ->visible(fn(Forms\Get $get, $operation) => $operation != 'create' || $get('difficulty_level') == TrailDifficultyLevelEnum::MANUAL->value)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Section::make('Fase 1')
                            ->label('Fase')
                            ->icon('tabler-circle-number-1')
                            ->schema([
                                Repeater::make('questions_level_1')
                                    ->label('Questões')
                                    ->defaultItems(5)
                                    ->minItems(4)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->grid(2)
                                    ->relationship(name: 'questions', modifyQueryUsing: fn($query) => $query->where('level', '=', 1))
                                    ->itemLabel(function (Repeater $component, array $state, string $uuid): string {
                                        $items = $component->getState();
                                        $keys = array_flip(array_keys($items));
                                        return 'Questão ' . ($keys[$uuid] + 1);
                                    })
                                    ->schema([
                                        Select::make('question')
                                            ->hiddenLabel()
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->fixIndistinctState()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->options(function (Forms\Get $get) {
                                                $questions = Question::query()
                                                    ->whereNull('deleted_at')
                                                    ->pluck('question', 'id');

                                                foreach ($questions as $key => $value) {
                                                    $questions[$key] = strip_tags($value);
                                                }

                                                return $questions;
                                            })
                                    ])
                            ]),

                        Forms\Components\Section::make('Fase 2')
                            ->label('Fase')
                            ->icon('tabler-circle-number-2')
                            ->schema([
                                Repeater::make('questions_level_2')
                                    ->label('Questões')
                                    ->defaultItems(5)
                                    ->minItems(4)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->grid(2)
                                    ->relationship(name: 'questions', modifyQueryUsing: fn($query) => $query->where('level', '=', 2))
                                    ->itemLabel(function (Repeater $component, array $state, string $uuid): string {
                                        $items = $component->getState();
                                        $keys = array_flip(array_keys($items));
                                        return 'Questão ' . ($keys[$uuid] + 1);
                                    })
                                    ->schema([
                                        Select::make('question')
                                            ->label('Questão')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->fixIndistinctState()
                                            ->options(function (Forms\Get $get) {
                                                $questions = Question::query()
                                                    ->whereNull('deleted_at')
                                                    ->pluck('question', 'id');

                                                foreach ($questions as $key => $value) {
                                                    $questions[$key] = strip_tags($value);
                                                }

                                                return $questions;
                                            })
                                    ])
                            ]),

                        Forms\Components\Section::make('Fase 3')
                            ->label('Fase')
                            ->icon('tabler-circle-number-3')
                            ->schema([
                                Repeater::make('questions_level_3')
                                    ->label('Questões')
                                    ->defaultItems(5)
                                    ->minItems(4)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->grid(2)
                                    ->relationship(name: 'questions', modifyQueryUsing: fn($query) => $query->where('level', '=', 3))
                                    ->itemLabel(function (Repeater $component, array $state, string $uuid): string {
                                        $items = $component->getState();
                                        $keys = array_flip(array_keys($items));
                                        return 'Questão ' . ($keys[$uuid] + 1);
                                    })
                                    ->schema([
                                        Select::make('question')
                                            ->label('Questão')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->fixIndistinctState()
                                            ->options(function (Forms\Get $get) {
                                                $questions = Question::query()
                                                    ->whereNull('deleted_at')
                                                    ->pluck('question', 'id');

                                                foreach ($questions as $key => $value) {
                                                    $questions[$key] = strip_tags($value);
                                                }

                                                return $questions;
                                            })
                                    ])
                            ]),

                        Forms\Components\Section::make('Fase 4')
                            ->label('Fase')
                            ->icon('tabler-circle-number-4')
                            ->schema([
                                Repeater::make('questions_level_4')
                                    ->label('Questões')
                                    ->defaultItems(5)
                                    ->minItems(4)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->grid(2)
                                    ->relationship(name: 'questions', modifyQueryUsing: fn($query) => $query->where('level', '=', 4))
                                    ->itemLabel(function (Repeater $component, array $state, string $uuid): string {
                                        $items = $component->getState();
                                        $keys = array_flip(array_keys($items));
                                        return 'Questão ' . ($keys[$uuid] + 1);
                                    })
                                    ->schema([
                                        Select::make('question')
                                            ->label('Questão')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->fixIndistinctState()
                                            ->options(function (Forms\Get $get) {
                                                $questions = Question::query()
                                                    ->whereNull('deleted_at')
                                                    ->pluck('question', 'id');

                                                foreach ($questions as $key => $value) {
                                                    $questions[$key] = strip_tags($value);
                                                }

                                                return $questions;
                                            })
                                    ])
                            ]),

                        Forms\Components\Section::make('Fase 5')
                            ->label('Fase')
                            ->icon('tabler-circle-number-5')
                            ->schema([
                                Repeater::make('questions_level_5')
                                    ->label('Questões')
                                    ->defaultItems(5)
                                    ->minItems(4)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->grid(2)
                                    ->relationship(name: 'questions', modifyQueryUsing: fn($query) => $query->where('level', '=', 5))
                                    ->itemLabel(function (Repeater $component, array $state, string $uuid): string {
                                        $items = $component->getState();
                                        $keys = array_flip(array_keys($items));
                                        return 'Questão ' . ($keys[$uuid] + 1);
                                    })
                                    ->schema([
                                        Select::make('question')
                                            ->label('Questão')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->fixIndistinctState()
                                            ->options(function (Forms\Get $get) {
                                                $questions = Question::query()
                                                    ->whereNull('deleted_at')
                                                    ->pluck('question', 'id');

                                                foreach ($questions as $key => $value) {
                                                    $questions[$key] = strip_tags($value);
                                                }

                                                return $questions;
                                            })
                                    ])
                            ]),
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\IconColumn::make('difficulty_level')
                    ->label('Nível de Dificuldade')
                    ->tooltip(fn($state): string => $state->getLabel())
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('Pontos da Trilha')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\IconColumn::make('finished')
                    ->label('Finalizada')
                    ->alignCenter()
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty_level')
                    ->label('Nível de Dificuldade')
                    ->options(TrailDifficultyLevelEnum::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('play')
                    ->visible(fn (Trail $record): bool => !$record->finished && auth()->user()->can('play', Trail::class))
                    ->label('Jogar')
                    ->color('primary')
                    ->button()
                    ->icon('ri-game-fill')
                    ->url(fn (Trail $record): string => route(TrailResource::getRouteBaseName(). '.play', $record))
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
            'index' => \App\Filament\app\Resources\TrailResource\Pages\ListTrails::route('/'),
            'create' => \App\Filament\app\Resources\TrailResource\Pages\CreateTrail::route('/create'),
            'edit' => \App\Filament\app\Resources\TrailResource\Pages\EditTrail::route('/{record}/edit'),
            'play' => \App\Filament\app\Resources\TrailResource\Pages\PlayTrail::route('/{record}/play'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
            'play',
        ];
    }
}
