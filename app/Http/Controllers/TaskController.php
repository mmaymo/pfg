<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class TaskController extends Controller
{
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
}
