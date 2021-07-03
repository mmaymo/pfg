<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Mockery\MockInterface;
use Tests\TestCase;

class CourseTest extends TestCase
{
    protected Course $course;
    /**
     * getCourseMembersDetails method test
     *
     * @return void
     * @test
     */
    public function getCourseMembersDetails()
    {
        $user = new User([
                             'email' => 'fake@test.com',
                             'name' => 'user1',
                             'password'=>bcrypt('admin')
                         ]);
        $testee = $this->partialMock(Course::class, function (MockInterface $mock) use($user){
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive( 'getUserPoints', 'getUserCourseProgress')->andReturn( 10, 10);
        });
        $testee->users = collect([$user]);

        $expected = [
            ['id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'points' => 10,
            'profile_photo_url' => null,
            'progress' => 10]
        ];

        $result = $testee->getCourseMembersDetails();

        $this->assertEquals($expected, $result);
    }
    /**
     * getOrderedChaptersWithTasks method test
     *
     * @return void
     * @test
     */
    public function getOrderedChaptersWithTasks()
    {
        $parentTask = new Task(['name' => 'taskname',
                                   'type' => 'document',
                                   'chapter_id'=>null,
                                   'parent_id'=>null,
                                   'course_id'=>$this->course->id,
                                   'points' => '10',
                                   'properties' => []]);
        $childTask = new Task(['name' => 'taskname2',
                                  'type' => 'code',
                                  'chapter_id'=>null,
                                  'parent_id'=>1,
                                  'course_id'=>$this->course->id,
                                  'points' => '10',
                                  'properties' => []]);

        $testee = $this->partialMock(Course::class, function (MockInterface $mock) use($parentTask, $childTask){
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('findTask')->andReturn($parentTask,$childTask);
        });
        $testee->positionArray = ["1" => [2]];
        $parentTask->tasks = collect();
        $parentTask->tasks->push($childTask);
        $expected = collect([$parentTask]);

        $result = $testee->getOrderedChaptersWithTasks();

        $this->assertEquals($expected, $result);
    }

    /**
     * deleteTaskFromPositions method test
     *
     * @return void
     * @test
     */
    public function deleteTaskFromPositions()
    {
        $testee = $this->partialMock(Course::class, function (MockInterface $mock){
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('saveCourse');
        });
        $testee->positionArray = ["1" => [2, 3, 4]];;
        $expected = ["1" => [ 1=>3, 2=>4]];

        $testee->deleteTaskFromPositions(2);

        $this->assertEquals($expected, $testee->positionArray);
        $expected = [ 3=>[], 4=>[]];

        $testee->deleteTaskFromPositions(1);

        $this->assertEquals($expected, $testee->positionArray);
    }

    /**
     * orderedTaskIdsFlat method test
     *
     * @return void
     * @test
     */
    public function orderedTaskIdsFlat()
    {
        $flattenArray = $this->course->orderedTaskIdsFlat();
        $expected = [1, 2, 3, 4];
        $this->assertEquals($expected, $flattenArray);
    }

    /**
     * reorder tasks method test
     *
     * @return void
     * @test
     */
    public function reorderTasks()
    {
        $orderedContentIds = [
            [
                'id' => 274,
                'name' => 'second',
                'tasks' => []
            ],
            [
                'id' => 273,
                'course_id' => 2,
                'tasks' => [
                    [
                        'id' => 276,
                        'another' => 'thing',
                        'tasks' => []
                    ],
                    [
                        'id' => 275,
                        'tasks' => []
                    ]

                ]
            ],

        ];
        $this->course->positionArray = $orderedContentIds;
        $newOrderedContentIds = [
            274 => [],
            273 => [
                276,
                275
            ]
        ];

        $expected = $this->course->reorderTasks($orderedContentIds);
        $this->assertEquals($expected, $newOrderedContentIds);
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
    }
}
