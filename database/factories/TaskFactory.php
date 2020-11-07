<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->sentence,
            'course_id'=>Course::all()->random(),
            'type'=>$this->faker->randomElement(['document','quiz','card','code']),
            'properties'=>json_encode('esto va a ser json')
        ];
    }
}
