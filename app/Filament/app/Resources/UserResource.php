<?php

namespace App\Filament\app\Resources;

use App\Enums\RoleEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administrativo';

    protected static ?string $label = 'Usuário';

    protected static ?string $pluralLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 12])->schema([
                    Section::make()
                        ->columnSpan([
                            'default' => 12,
                            'lg' => 9,
                        ])
                        ->schema([
                            Grid::make(['default' => 12])->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->rules(['max:255', 'string'])
                                    ->placeholder('Nome')
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 6,
                                    ]),

                                TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->unique(
                                        'users',
                                        'email',
                                        fn (?Model $record) => $record
                                    )
                                    ->placeholder('Email')
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 6,
                                    ]),

                                TextInput::make('password')
                                    ->password()
                                    ->hidden(fn (string $context): bool => $context !== 'create')
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->placeholder('Senha')
                                    ->rule('confirmed')
                                    ->label('Senha')
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 4,
                                    ]),

                                TextInput::make('password_confirmation')
                                    ->hidden(fn (string $context): bool => $context !== 'create')
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->placeholder('Confirmação de Senha')
                                    ->label('Confirmação de Senha')
                                    ->password()
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 4,
                                    ]),

                                Forms\Components\Select::make('roles')
                                    ->label('Grupos de Permissões')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 4,
                                    ])
                                    ->searchable(),
                            ]),
                        ]),
                    Section::make()
                        ->columnSpan([
                            'default' => 12,
                            'lg' => 3,
                        ])
                        ->schema([
                            Placeholder::make('Criado em:')
                                ->content(fn (?User $record) => is_null($record) ? '-' : $record->created_at->format('d/m/Y H:i:s'))
                                ->columnSpanFull(),
                            Placeholder::make('Última Atualização:')
                                ->content(fn (?User $record) => is_null($record) ? '-' : $record->updated_at->format('d/m/Y H:i:s'))
                                ->columnSpanFull(),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->sortable()
                    ->label('Nome'),

                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->searchable()
                    ->label('E-mail'),

                Tables\Columns\TextColumn::make('registration_code')
                    ->label('Matrícula')
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.id')
                    ->formatStateUsing(fn (string $state): string => RoleEnum::getRoleEnumDescriptionById(intval($state)))
                    ->label('Grupo de Permissão')
                    ->color(fn (string $state): string => match (RoleEnum::getRoleEnum(intval($state))) {
                        RoleEnum::SuperAdministrador => 'danger',
                        RoleEnum::Professor => 'warning',
                        RoleEnum::Estudante => 'dark'
                    })
                    ->limitList(2)
                    ->badge(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Atualizado em')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Criado em')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Impersonate::make()
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
            'index' => \App\Filament\app\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\app\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\app\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
