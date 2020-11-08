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
        $user = User::find(1);

        $team = $user->ownedTeams()->create(
            [
                'name'=>$this->faker->sentence,
                'personal_team'=>false
            ]
        );
        return [
            'team_id' => $team->id,
            'degree'=> 'Ingeniería Informática',
            'semester'=>$this->faker->boolean,
            'pic'=>'/images/unedDefault.jpg'
        ];
    }
}
