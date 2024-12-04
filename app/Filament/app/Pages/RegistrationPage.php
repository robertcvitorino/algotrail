<?php

namespace App\Filament\app\Pages;

use App\Enums\RoleEnum;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;

class RegistrationPage extends Register
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('registration_code')
                            ->numeric()
                            ->label('Matrícula')
                            ->required(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function handleRegistration(array $data): Model
    {
        $user = User::create($data);
        $user->assignRole(RoleEnum::Estudante->name);
        return $user;
    }

}
