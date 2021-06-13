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

    protected function addPoints($isCorrect, $courseId, $taskId)
    {
        $task = Task::find($taskId);
        $isDone = Auth::user()->isTaskCompleted($courseId, $taskId);
        $task->maybeAddPoints($isDone, $isCorrect, $courseId);
    }

    public function upload(Request $request, $courseId, $taskId)
    {
        $fileName = $request->testCode->getClientOriginalName();
        $request->testCode->storeAs("codetest/ejemplos/", $fileName);

        return back();
    }
}
