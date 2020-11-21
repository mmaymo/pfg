<?php

namespace App\Http\Controllers;

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
        $coursePoints = $course->rankingTeamCoursePoints;
        $courseProgress = $course->courseProgress;
        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return Jetstream::inertia()->render($request, 'Courses/ShowContent', [
            'team' => $team->load('owner', 'users'),
            'course'=> $course,
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
        ]);
    }
}
