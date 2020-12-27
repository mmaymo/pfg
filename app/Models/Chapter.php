<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'course_id',
            'name',
            'tasksPositionArray'
        ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tasksPositionArray' => 'array',
    ];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'chapter_id');
    }
}
