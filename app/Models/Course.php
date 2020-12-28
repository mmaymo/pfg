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
            'user_id',
            'name',
            'degree',
            'semester',
            'pic'
        ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'positionArray' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('points');
    }


    public function getMembersDetails(){
        $users = $this->users;
        $members = [];
        $progress = "userProgressToDo";
        foreach ($users as $user){
            $members[] = ['id'=>$user->id, 'name'=>$user->name, 'points'=>$user->pivot->points, 'profile_photo_url'=>$user->profile_photo_path, 'progress'=>$progress];
        }
        return $members;
    }
    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'course_id');
    }


    public function getOrderedChaptersWithTasks()
    {
        $chapters = $this->positionArray;
        $orderedChapters = collect();

        foreach ($chapters as $chapter => $value){
            $selectedTask = Task::find($chapter);
            $selectedTask->tasks = collect();
            if(!empty($value)){
                foreach ($value as $taskId){
                    $task = Task::find($taskId);
                    $task = $task->clean_task;
                    $selectedTask->tasks->push($task);
                }
            }
            $orderedChapters->push($selectedTask);
        }

        return $orderedChapters;
    }

    public function insertPositions(array $positions){
        $this->chaptersPositionArray = $positions;
        $this->save();
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
