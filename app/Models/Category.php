<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Accessor to check if category is truly active
    public function getIsActiveAttribute($value)
    {
        return $value;
    }

    // Check if category can be activated (no restrictions for now)
    public function canBeActivated()
    {
        return true;
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
