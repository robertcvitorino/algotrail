<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionTrail extends Pivot
{
    use HasFactory;

    protected $table = 'question_trail';

    protected $fillable = ['question_id', 'trail_id', 'level'];
}
