<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CodeTestController extends Controller
{

    /**
     * Test the given code task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param                          $taskId
     *
     *
     */
    public function testCodeTask(Request $request, $courseId, $taskId)
    {
        Log::debug('request', $request->all());
        $validated = $request->validate(
            [
                'userAnswer' => 'required',
            ]
        );
        Log::debug('validated', [$validated['userAnswer']]);
        $this->addPoints($validated['userAnswer'], $courseId, $taskId);
    }

    public function upload(Request $request,$courseId, $taskId){
        $request->testCode->store("codetest/{$courseId}/{$taskId}");

        return back();
    }

    protected function addPoints($isCorrect,$courseId, $taskId)
    {
        $task =Task::find($taskId);
        $isDone = Auth::user()->isTaskCompleted($courseId, $taskId);
        Log::debug('$isDone', [$isDone]);
        if(!$isDone){
            if($isCorrect){
                Log::debug('$task->points', [$task->points]);
                $previousPoints = Auth::user()->coursePoints($courseId);
                Auth::user()->coursesEnrolled()->updateExistingPivot($courseId, ['points' => $previousPoints + $task->points]);
                $task->userTasksCompleted()->attach(Auth::user()->id,['course_id'=>$courseId, 'points'=>$task->points]);
            }else{
                $task->userTasksCompleted()->attach(Auth::user()->id,['course_id'=>$courseId, 'points'=>0]);
            }
        }
    }
}
