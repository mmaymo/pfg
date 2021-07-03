<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Mockery\MockInterface;
use Tests\TestCase;

class TaskTest extends TestCase
{
    protected Course $course;
    protected Task $task;

    /**
     * getCleanTaskAttribute method test
     *
     * @return void
     * @test
     */
    public function getCleanTaskAttribute()
    {
        $cleanAttribute = $this->task->getCleanTaskAttribute();
        $expected = collect(
            [
                'id' => $this->task->id,
                'name' => $this->task->name,
                'points' => $this->task->points,
                'type' => $this->task->type,
                'properties' => [
                        "card"=>["back"=> null, "front"=> null],
                        "quiz"=> [
                            "question"=> "Proceso y núcleo son:",
                            "responses"=> ["R1", "R2", "R3", "R4"],
                            "correctAnswer"=> null
                        ],
                        "content"=> "",
                        "code_url"=> null
                ]
            ]
        );
        $this->assertEquals($expected, $cleanAttribute);
    }

    /**
     * isAllowed method test
     *
     * @return void
     * @test
     */
    public function isAllowedBecauseHasNoParent()
    {
        $testee = $this->partialMock(Task::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive( 'completedTasks', 'findTaskById', 'getAuthUser')->andReturn();
        });
        $testee->parent_id = null;
        $resultEmptyParent = $testee->isAllowed(1,1);
        $expected = true;
        $this->assertEquals($expected, $resultEmptyParent);
    }

    /**
     * isAllowed method test
     *
     * @return void
     * @test
     */
    public function isAllowedBecauseIsCompleted()
    {
        $testee = $this->partialMock(Task::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive( 'completedTasks', 'findTaskById', 'getAuthUser')->andReturn(true, 1, 1);
        });

        $testee->parent_id = 1;
        $resultIsCompletedTask = $testee->isAllowed(1,1);
        $expected = true;
        $this->assertEquals($expected, $resultIsCompletedTask);
    }



    protected function setUp(): void
    {
        parent::setUp();
        $positionArray = ["1" => [2, 3, 4]];
        $this->course = new Course(
            [
                'user_id' => 1,
                'name' => 'Diseño y Administración de Sistemas Operativos',
                'degree' => 'Ingeniería Informática',
                'semester' => 0,
                'pic' => '/images/unedDefault.jpg',
                'positionArray' => $positionArray
            ]
        );
        $this->task = new Task(['name' => 'taskname',
                                   'type' => 'quiz',
                                   'chapter_id'=>null,
                                   'parent_id'=>null,
                                   'course_id'=>$this->course->id,
                                   'points' => '10',
                                   'properties' => [
                                           "card"=>["back"=> null, "front"=> null],
                                           "quiz"=> [
                                               "question"=> "Proceso y núcleo son:",
                                                "responses"=> ["R1", "R2", "R3", "R4"],
                                                "correctAnswer"=> [0]
                                            ],
                                           "content"=> "",
                                           "code_url"=> null
                                   ]
                                ]);

    }
}
