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
    const AVAILABLE_TASK_TYPES = ['document', 'quiz', 'card', 'code', 'introCourse'];

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
        $task = Task::find($taskId);
        $userPositionInCourse = $request->user()->courseProgress($team->id);
        $coursePoints = $request->user()->coursePoints($course->id);
        $courseProgress = $course->progressFromPosition($userPositionInCourse);
        $allowedIds = $tasks->slice(0, $courseProgress['position'])->pluck('id');
        $taskIndexInCourse = $course->taskIndexInCourse($taskId);
        $previous = $taskIndexInCourse>0? $tasks[$taskIndexInCourse-1]->id: null;
        $next = $taskIndexInCourse<=$tasks->count()? $tasks[$taskIndexInCourse+1]->id: null;

        return Jetstream::inertia()->render($request, 'Tasks/Show', [
            'teacher' => $teacher,
            'tasks'=>$tasks,
            'allowedIds'=>$allowedIds,
            'task'=>[
                'chapter'=>'Tengo que migrar la db!!',
                'name'=>$task->name,
                'id' =>$task->id,
                'type'=> $task->type,
                'content' =>$task->properties,
                'points'=>$task->points,
                'previousId'=>$previous,
                'nextId'=>$next
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
     * @param \Illuminate\Http\Request $request
     * @param int $course
     * @return \Inertia\Response
     */
    public function create(Request $request, $course)
    {
        $course = Course::find($course);
        $chapters = $course->chapters;
        $availableTypes = self::AVAILABLE_TASK_TYPES;

        return Inertia::render('Tasks/Create', ['courseName'=>$course->name, 'courseId'=>$course->id, 'chapters'=>$chapters, 'availableTypes'=>$availableTypes]);
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
                               'chapter_id' => 'required',
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
     * Delete the given task.
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
