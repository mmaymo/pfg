<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = User::create([
            'name'=>'Profesor Test',
            'email'=>'test@test.com',
            'password'=>bcrypt('admin')
        ]);
        $student = User::create([
            'name'=>'Alumno Test',
            'email'=>'test1@test.com',
            'password'=>bcrypt('admin')
        ]);
        $teacherRole = Role::create(['name' => 'profesor']);
        $editPermission = Permission::create(['name' => 'edit courses']);
        $teacherRole->syncPermissions($editPermission);
        $editPermission->syncRoles($teacherRole);
        $studentRole = Role::create(['name' => 'alumno']);
        $viewPermission = Permission::create(['name' => 'view courses']);
        $studentRole->syncPermissions($viewPermission);
        $viewPermission->syncRoles($studentRole);
        $teacherRole->syncPermissions($viewPermission);

        $teacher->assignRole('profesor');


        Course::factory()->count(1)->create();
    }
}
