<?php

namespace App\Http\Controllers;

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
             return $logResult->body();
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


}
