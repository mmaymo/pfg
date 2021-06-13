<?php

namespace Tests\Unit;

use App\Models\Course;
use Tests\TestCase;

class CourseTest extends TestCase
{
    protected $course;

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
        $this->course = Course::create(
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
