<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RoleEnum: int implements HasColor, HasLabel
{
    case SuperAdministrador = 1;
    case Professor = 2;
    case Estudante = 3;




    public static function getRoleEnum($role)
    {
        return match ($role) {
            1 => RoleEnum::SuperAdministrador,
            2 => RoleEnum::Professor,
            3 => RoleEnum::Estudante
        };
    }

    public static function getRoleEnumDescriptionById($role)
    {
        return match ($role) {
            1 => 'Super Administrador',
            2 => 'Professor',
            3 => 'Estudante'
        };
    }

    public static function getRoleEnumDescription($role)
    {
        return match ($role) {
            self::SuperAdministrador => 'Super Administrador',
            self::Professor => 'Professor',
            self::Estudante => 'Estudante'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SuperAdministrador => 'danger',
            self::Professor => 'info',
            self::Estudante => 'warning'
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SuperAdministrador => 'Super Administrador',
            self::Professor => 'Professor',
            self::Estudante => 'Estudante'
        };
    }
}
