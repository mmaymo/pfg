<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function tasks()
    {
        return $this->hasOne('App\Models\Task', 'course_id');
    }
}
