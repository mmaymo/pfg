<?php


namespace App\Traits;

use App\Models\Course;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

trait HasCourseDetails
{
    public function coursesEnrolled()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress');
    }

    public function coursesEnrolledWithPoints()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress')
            ->withPivot('points');
    }
    public function courses(){
        return $this->hasMany('App\Models\Course');
    }
    public function tasks(){
        return $this->belongsToMany('App\Models\Task', 'task_user', 'user_id', 'task_id')->withPivot('course_id');
    }

    public function completedTasks($courseId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
    }
    public function isTaskCompleted($courseId, $taskId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId], ['task_id', '=', $taskId]]
        )->exists();
    }

    public function completedTasksCount($courseId){
        return $this->completedTasks($courseId)->count();
    }

    public function coursePoints($courseId)
    {
        $collection = DB::table('users_course_progress')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
        return $collection->first()?$collection->first()->points:0;
    }

    public function courseProgress($courseId){
        $course = Course::find($courseId);
        $tasksCourseCount = $course->taskCount();
        $userTasksDone =$this->completedTasksCount($courseId);
        return ($userTasksDone / $tasksCourseCount) * 100;
    }


    public function canSeeTask($courseId, $taskId)
    {
        $completedTasks = $this->completedTasks($courseId);
        $isDone = $completedTasks->contains('task_id', $taskId);
        if($isDone){
            return true;
        }

        $task = Task::find($taskId);
        if($task->emptyParent()){
            return true;
        }

        $parentIsDone = $completedTasks->contains('task_id', $task->parent_id);
        if($parentIsDone){
            return true;
        }
        return false;
    }
}
