<?php

namespace App\Models;

use App\Enums\QuestionDifficultyLevelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['question', 'difficulty_level', 'answers'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'answers' => 'array',
        'difficulty_level' => QuestionDifficultyLevelEnum::class,
    ];

    public function trails()
    {
        return $this->belongsToMany(Trail::class);
    }

    public function userAnswers() {
        return $this->hasMany(UserAnswer::class);
    }
}
