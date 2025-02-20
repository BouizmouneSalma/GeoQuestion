<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'content',
        'latitude',
        'longitude',
        'location_name',
        'user_id'
    ];

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec Answer
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}