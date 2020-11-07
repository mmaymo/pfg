<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create();
        $courses = Course::factory()->count(4)->create();
        Task::factory()->count(20)->create();
        foreach ($courses as $course){
            $team = $course->team;
            $student = User::factory()->count(1)->create();
            $team->users()->attach($student, ['role'=>'student']);
        }
    }
}
