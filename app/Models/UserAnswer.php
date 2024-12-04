<?php

namespace App\Models;

use App\Enums\TrailDifficultyLevelEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'trail_id',
        'answer_selected',
        'selected_correctly',
        'score',
        'level',
    ];

    protected $casts = [
        'selected_correctly' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trail()
    {
        return $this->belongsTo(Trail::class);
    }
}
