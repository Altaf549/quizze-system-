<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'time_limit',
        'difficulty',
        'total_points',
        'image',
        'attempts_count',
        'rating',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Accessor to check if quiz is truly active (depends on category)
    public function getIsActiveAttribute($value)
    {
        // Quiz is only active if both itself and its category are active
        return $value && $this->category && $this->category->is_active;
    }

    // Check if quiz can be activated
    public function canBeActivated()
    {
        return $this->category && $this->category->is_active;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
