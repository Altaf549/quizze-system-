<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'options',
        'correct_answer',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];

    // Accessor to check if question is truly active (depends on quiz and category)
    public function getIsActiveAttribute($value)
    {
        // Question is only active if itself, its quiz, and quiz's category are all active
        return $value && $this->quiz && $this->quiz->is_active;
    }

    // Check if question can be activated
    public function canBeActivated()
    {
        return $this->quiz && $this->quiz->canBeActivated();
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
