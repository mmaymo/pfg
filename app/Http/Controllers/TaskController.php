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
     * @return \Inertia\Response
     */
    public function show(Request $request, $teamId, $taskId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);
        $teacher = $team->owner->name;
        $course = $team->course;
        $user = Auth::user();
        $tasks = $course->tasks;
        $positions = $tasks->pluck('id');
        $task = Task::find($taskId);
        $coursePoints = $user->coursePoints($course->id);
        $courseProgress = $user->courseProgress($course->id);

        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return Jetstream::inertia()->render($request, 'Tasks/Show', [
            'teacher' => $teacher,
            'tasks'=>$tasks,
            'task'=>[
                'chapter'=>'Tengo que migrar la db!!',
                'name'=>$task->name,
                'id' =>$task->id,
                'type'=> $task->type,
                'content' =>$task->properties,
                'points'=>$task->points,
                'orderedIds'=>$positions,
            ],
            'courseName'=> $team->name,
            'courseId'=> $course->id,
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
        ]);
    }
}
