<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $validated = $request->validate([
                                            'userAnswer' => ['required'],
                                        ]);


        //ahora busco el test asociado a esta tarea que esta en una carpeta que tengo que montar como volumen

        $studentCommands = $validated['userAnswer'];
        $testAssert = "assert [ -e '/var/log/test.log'";
        $testContent = "@test 'I can use an assert() from helper bats-assert' {{$studentCommands} {$testAssert} ]}";
        //Storage::disk('local')->put("codetest/{$courseId}/{$taskId}/testcode.bats", $testContent);
        $createContainer = $this->dockerApiCall(
            'post',
            'http://dummy/containers/create',
            [
                "Image" => "dduportal/bats",
                "Cmd" => [
                    "/my-test"
                ],
                "Volume"=>["my-test"],
                "HostConfig" => [
                    "Binds"=>["/Users/carmenmaymo/Code/pfg/pfg/storage/app/codetest/{$courseId}/{$taskId}:/my-test"],
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
         $stopContainer = $this->dockerApiCall(
             'post',
             "http://dummy/containers/{$containerId}/stop"
         );

         $removeContainer = $this->dockerApiCall(
             'delete',
             "http://dummy/containers/{$containerId}"
         );
         $pipelineSuccess = $createContainer->successful() &&
             $startContainer->successful()
             && $stopContainer->successful()
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
