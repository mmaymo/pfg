<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Jetstream;

class TaskController extends Controller
{
    public function index(Course $course)
    {

        $user = Auth::user();
        $team = Team::find($course->id);
        Gate::forUser($user)->authorize('update', $team);
        $tasks = $course->tasks;

        return Inertia::render( 'Tasks/Index', [
            'course'=>[
                'id'=>$team->id,
                'name'=>$team->name
            ],

            'tasks'=>$tasks,
            'userPermissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);

    }
    /**
     * Show the task contents.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $teamId
     * @param int                      $taskId
     *
     * @return \Inertia\Response |\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $teamId, $taskId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }
        if (! $request->user()->canSeeTask($teamId, $taskId)) {
            return back();
        }
        $teacher = $team->owner->name;
        $course = $team->course;
        $tasks = $course->tasks;
        $positions = $tasks->pluck('id');
        $task = Task::find($taskId);
        $coursePoints = $request->user()->coursePoints($course->id);
        $courseProgress = $request->user()->courseProgress($course->id);//todo progress from position

        return Jetstream::inertia()->render($request, 'Tasks/Show', [
            'teacher' => $teacher,
            'tasks'=>$tasks,
            'allowedIds'=>[12,8],
            'task'=>[
                'chapter'=>'Tengo que migrar la db!!',
                'name'=>$task->name,
                'id' =>$task->id,
                'type'=> $task->type,
                'content' =>$task->properties,
                'points'=>$task->points,
                'previousId'=>12,
                'nextId'=>13
            ],
            'courseName'=> $team->name,
            'courseId'=> $course->id,
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
        ]);
    }
    /**
     * Show the task creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Tasks/Create');
    }

    /**
     * Create a new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $course)
    {
        $validated = $request->validate([
                               'name' => 'required',
                               'type' => 'required',
                               'position' => 'required',
            'points'=>'required',
            'properties'=>'required'
                           ]);
        $validated['course_id'] = $course;

        Task::create($validated);


        return back();
    }

    /**
     * Update the given task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $course, $task)
    {
        $teamAuth = Team::find($course);
        Gate::forUser($request->user())->authorize('update', $teamAuth);

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'position' => 'required',
            'points'=>'required',
            'properties'=>'required'

        ])->validateWithBag('updateTask');
        $task = Task::find($task);
        $task->update($validated);

        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $teamId
     * @param                          $taskId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId, $taskId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $task = Task::find($taskId);
        $task->delete();

        return back();
    }
}
