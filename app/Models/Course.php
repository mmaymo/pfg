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
            'name',
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
        return $this->hasMany('App\Models\Task', 'course_id')->orderBy('position');
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

    public function progressFromPosition($taskPosition)
    {
        $tasks = $this->tasks;
        $tasksLenght = $tasks->count();

        if ($taskPosition === 0) {
            return [
                "position" => 0,
                "total" => $tasksLenght,
                "activeTaskId" => $tasks->first()->id
            ];
        }
        $num = $tasks->search(
            function ($item, $key) use ($taskPosition) {
                return $item->position == $taskPosition;
            }
        );
        $taskId = $tasks[$num]->id;


        return [
            "position" => $num + 1,
            "total" => $tasksLenght,
            "activeTaskId" => $taskId
        ];
    }
    public function hasTasks(){
        return !$this->tasks->isEmpty();
    }

    public function taskIndexInCourse($taskId){
        $tasks = $this->tasks;
        return $tasks->search(
            function ($item, $key) use ($taskId) {
                return $item->id == $taskId;
            }
        );
    }
}
