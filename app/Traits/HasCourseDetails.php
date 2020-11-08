<?php


namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasCourseDetails
{
    public function allCourses()
    {
        return $this->belongsToMany('App\Models\Team', 'users_course_progress');
    }

    public function allCoursesWithPoints()
    {
        return $this->belongsToMany('App\Models\Team', 'users_course_progress')
            ->withPivot('points');
    }

    public function coursePoints($courseId)
    {
        $collection = DB::table('users_course_progress')->where(
            [['user_id', '=', $this->id], ['team_id', '=', $courseId]]
        )->get();
        return $collection->first()->points;
    }

    public function allCoursesWithProgress()
    {
        return $this->belongsToMany('App\Models\Team', 'users_course_progress')
            ->withPivot('progress');
    }

    public function courseProgress($courseId)
    {
        $collection = DB::table('users_course_progress')->where(
            [['user_id', '=', $this->id], ['team_id', '=', $courseId]]
        )->get();
        return $collection->first()->progress;
    }
}
