<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

class CourseContent extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @param                          $id
     *
     * @return \Inertia\Response
     */
    public function __invoke(Request $request, $id)
    {
        $team = Jetstream::newTeamModel()->findOrFail($id);
        $course = $team->course;
        $tasks = $course->tasks;

        $coursePoints = $course->rankingTeamCoursePoints;
        $courseProgress = $course->courseProgress;

        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return Jetstream::inertia()->render($request, 'Courses/ShowContent', [
            'team' => $team->load('owner', 'users'),
            'tasks'=>$tasks,

            'courseName'=> $team->name,
            'courseId'=> $course->id,
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
        ]);
    }
}
