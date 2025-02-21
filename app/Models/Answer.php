<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'question_id'
    ];

    // Relation avec Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}