<?php

namespace App\Models;

use App\Enums\TrailDifficultyLevelEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
        'difficulty_level',
    ];

    protected $casts = [
        'difficulty_level' => TrailDifficultyLevelEnum::class
    ];

    protected function score(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->questions()->sum('difficulty_level') * 10
        );
    }

    protected function finished(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->userAnswers()
                ->where('user_id', '=', auth()->user()->id)
                ->exists()
        );
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class)
            ->withPivot('level');
    }

    public function userAnswers() {
        return $this->hasMany(UserAnswer::class);
    }
}
