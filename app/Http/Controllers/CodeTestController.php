<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        $userId = Auth::user()->id;
        $validated = $request->validate([
                                            'userAnswer' => ['required'],
                                        ]);

        $studentCommands = $validated['userAnswer'];
        $testContent=Storage::get("codetest/{$courseId}/{$taskId}/testcode.bats");
        $testContent=str_replace('studentCommand', $studentCommands,$testContent);

        Storage::disk('local')->put("codetest/{$courseId}/{$taskId}/{$userId}/testcode.bats", $testContent);
        $createContainer = $this->dockerApiCall(
            'post',
            'http://dummy/containers/create',
            [
                "Image" => "dduportal/bats",
                "Cmd" => [
                    "/my-test"
                ],
                "Tty"=>true,
                "Volume"=>["my-test"],
                "HostConfig" => [
                    "Binds"=>["/Users/carmenmaymo/Code/pfg/pfg/storage/app/codetest/{$courseId}/{$taskId}/{$userId}:/my-test"],
                ]
            ]
        );

        $containerId = $createContainer->json("Id");
        $startContainer = $this->dockerApiCall(
            'post',
            "http://dummy/containers/{$containerId}/start"
        );
        $logResult = $this->dockerApiCall(
            'post',
            "http://dummy/containers/{$containerId}/attach?stream=1&stdout=1"
        );

         $removeContainer = $this->dockerApiCall(
             'delete',
             "http://dummy/containers/{$containerId}"
         );

         $pipelineSuccess = $createContainer->successful() &&
             $startContainer->successful()
             && $removeContainer->successful();
         if($pipelineSuccess){
             $isCorrect = $this->isCorrect($logResult->body());
             $parsedResult = $this->parseResult($logResult->body());
             $this->addPoints($isCorrect,$courseId, $taskId);
             return  $parsedResult;
         }
        return 'error que no he podido hacer la operación';
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $commands
     *
     * @return mixed
     */
    protected function dockerApiCall(
        string $method,
        string $url,
        array $commands =[]
    ) {
        return Http::withOptions(
            [
                'curl' => [
                    CURLOPT_UNIX_SOCKET_PATH => '/var/run/docker.sock'
                ]
            ]
        )
            ->withHeaders(["Content-Type: application/json"])
            ->$method(
                $url,
                $commands
            );
    }

    public function upload(Request $request,$courseId, $taskId){
        $request->testCode->store("codetest/{$courseId}/{$taskId}");

        return back();
    }

    protected function addPoints($isCorrect,$courseId, $taskId)
    {
        $task =Task::find($taskId);
        $isDone = Auth::user()->isTaskCompleted($courseId, $taskId);
        if(!$isDone){
            if($isCorrect){
                $previousPoints = Auth::user()->coursePoints($courseId);
                Auth::user()->coursesEnrolled()->updateExistingPivot($courseId, ['points' => $previousPoints + $task->points]);
            }
            $task->userTasksCompleted()->attach(Auth::user()->id,['course_id'=>$courseId]);
        }
    }

    private function isCorrect($body)
    {
        $isFailed = str_contains($body, 'failed');

        return !$isFailed;
    }
    private function parseResult($body)
    {
        $body = str_replace("#", "\n", $body);
        return str_replace("\n\n", "\n", $body);

    }
}
