<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'team_id',
            'degree',
            'semester',
            'pic'
        ];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'course_id');
    }

    public function itinerary()
    {
        $tasks = $this->tasks;
        return $tasks->sortBy('position');
    }

    public function rankingTeamCoursePoints()
    {
        $team = $this->team;
        return  $team->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('points')->orderBy('points', 'desc');
    }
    public function courseProgress()
    {
        $team = $this->team;
        return $team->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('progress');
    }
}
