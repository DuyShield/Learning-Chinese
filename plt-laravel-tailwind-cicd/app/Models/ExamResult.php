<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $table = 'exam_results';
    protected $guarded = [];

    protected $casts = ['taken_at' => 'date'];

    public function getPassedAttribute(): bool
    {
        return $this->score >= 60;
    }
}
