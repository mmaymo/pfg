<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Jetstream;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courses = $user->allCoursesWithDetails;
        //foreach course I need the task that should be active
        //[course.name, course.pivot.owner, course.pivot.progress, course.pivot.points, course.tasks.enabledTask]

        return Inertia::render( 'Courses/Index', [
            'courses'=>$courses
        ]);

    }
    /**
     * Show the course contents management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Inertia\Response
     */
    public function show(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);
        $course = $team->course;
        $coursePoints = $course->rankingTeamCoursePoints;
        $courseProgress = $course->courseProgress;
        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return Jetstream::inertia()->render($request, 'Courses/Show', [
            'team' => $team->load('owner', 'users'),
            'course'=> $course,
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Courses/Create');
    }

    /**
     * Create a new team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        app(CreatesTeams::class)->create($request->user(), $request->all());

        return redirect(config('fortify.home'));
    }

    /**
     * Update the given team's name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);
        $course = Course::where('team_id', '=', $teamId);

        Gate::forUser($request->user())->authorize('update', $team);

        Validator::make($request->all(), [
            'degree' => ['string', 'max:255'],
            'semester' => ['boolean'],
            'pic' => ['string', 'max:255'],

        ])->validateWithBag('updateCourseDetails');
        $input = $request->all();
        $course->update(
            [
                'degree' => $input['degree'],
                'semester' => $input['semester'],
                'pic' => $input['pic'],
            ]
        );

        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        app(DeletesTeams::class)->delete($team);

        return redirect(config('fortify.home'));
    }
}
