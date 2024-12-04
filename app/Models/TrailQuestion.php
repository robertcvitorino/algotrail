<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrailQuestion extends Pivot
{
    use SoftDeletes;

    protected $fillable = [
        'level',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function trail()
    {
        return $this->belongsTo(Trail::class);
    }
}
