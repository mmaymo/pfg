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
        foreach ($users as $user){
            $members[] = ['id'=>$user->id, 'name'=>$user->name, 'points'=>$user->pivot->points, 'profile_photo_url'=>$user->profile_photo_path, 'progress'=>$user->courseProgress($this->id)];
        }
        return $members;
    }
    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'course_id');
    }

    public function orderedTaskIdsFlat(){
        $positions = $this->positionArray;
        $keysChapters = array_keys ($positions );
        $flattenArray = array();
        for($i = 0; $i <count($positions); $i++){
            $flattenArray[] = $keysChapters[$i];
            foreach($positions[$keysChapters[$i]] as $chapterItem){
                $flattenArray[] = $chapterItem;
            }
        }
        return $flattenArray;
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
        $this->positionArray = $positions;
        $this->save();
    }
    public function taskCount(){
        return $this->tasks()->count();
    }

    public function deleteTaskFromPositions($taskId)
    {
        $chapters = $this->positionArray;
        if(array_key_exists($taskId, $chapters)){
            $children = $chapters[$taskId];
            unset($chapters[$taskId]);
            foreach($children as $child){
                $chapters[$child]= [];
            }
        }
        else{
            foreach($chapters as $key =>$subtasks){
                $chapters[$key] = array_filter($subtasks, function($tasks) use($taskId){
                    return $tasks != $taskId;
                });
            }
        }
        $this->positionArray = $chapters;
        $this->save();
    }

    public function deleteAllTasks(){
        Task::where('course_id', $this->id)->delete();
    }

    public function deleteAllMembers(){
        $this->users()->detach();
    }
}
