<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TrailDifficultyLevelEnum: int implements HasIcon, HasColor, HasLabel
{
    case MANUAL = -1;
    case BEGINNER = 1;
    case INTERMEDIATE = 2;
    case ADVANCED = 3;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BEGINNER => 'success',
            self::INTERMEDIATE => 'warning',
            self::ADVANCED => 'danger',
            self::MANUAL => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BEGINNER => 'carbon-skill-level-basic',
            self::INTERMEDIATE => 'carbon-skill-level-intermediate',
            self::ADVANCED => 'carbon-skill-level-advanced',
            self::MANUAL => 'heroicon-s-cog',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BEGINNER => __('enums/trail_difficulty_level.beginner'),
            self::INTERMEDIATE => __('enums/trail_difficulty_level.intermediate'),
            self::ADVANCED => __('enums/trail_difficulty_level.advanced'),
            self::MANUAL => __('enums/trail_difficulty_level.manual'),
        };
    }
}
