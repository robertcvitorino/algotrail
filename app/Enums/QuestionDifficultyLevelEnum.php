<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum QuestionDifficultyLevelEnum: int implements HasIcon, HasColor, HasLabel
{
    case BEGINNER = 1;
    case INTERMEDIATE = 2;
    case ADVANCED = 3;

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BEGINNER => 'carbon-skill-level-basic',
            self::INTERMEDIATE => 'carbon-skill-level-intermediate',
            self::ADVANCED => 'carbon-skill-level-advanced',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BEGINNER => 'success',
            self::INTERMEDIATE => 'warning',
            self::ADVANCED => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BEGINNER => __('enums/difficulty_question_level.beginner'),
            self::INTERMEDIATE => __('enums/difficulty_question_level.intermediate'),
            self::ADVANCED => __('enums/difficulty_question_level.advanced'),
        };
    }
}
