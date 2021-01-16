<?php

namespace Database\Factories;

use App\Models\Course;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //todo update positionArray
        return [
            'user_id'=>1,
            'name'=>'Diseño y Administración de Sistemas Operativos',
            'degree'=> 'Ingeniería Informática',
            'semester'=>0,
            'pic'=>'/images/unedDefault.jpg',
            'positionArray'=>[]
        ];
    }
}
