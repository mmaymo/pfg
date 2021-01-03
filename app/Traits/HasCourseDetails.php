<?php


namespace App\Traits;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

trait HasCourseDetails
{
    public function allCourses()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress');
    }

    public function allCoursesWithPoints()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress')
            ->withPivot('points');
    }

    public function completedTasks($courseId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
    }
    public function isTaskCompleted($courseId, $taskId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId], ['task_id', '=', $taskId]]
        )->first();
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
        //todo probably move to task somewhere
        $course = Course::find($courseId);//2
        $courseProgress = $this->courseProgress($courseId);//16

        $tasks = $course->tasks;
        $ids = $tasks->pluck('id');//12,8,13,11
        $positions = $tasks->pluck('position');//8, 16, 34, 71

        $keyToCheck = $ids->search($taskId);//1
        $lastKeyAllowed = $positions->search($courseProgress);//1

        if ($keyToCheck <= $lastKeyAllowed) {
            return true;
        }
        return false;
    }
}
