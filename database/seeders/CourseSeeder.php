<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
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

        $editPermission->syncRoles($teacherRole);
        $studentRole = Role::create(['name' => 'alumno']);
        $viewPermission = Permission::create(['name' => 'view courses']);
        $studentRole->syncPermissions($viewPermission);
        $viewPermission->syncRoles([$teacherRole, $studentRole]);
        $teacherRole->syncPermissions([$editPermission ,$viewPermission]);

        $teacher->assignRole('profesor');
        Storage::disk('local')->makeDirectory("codetest/{$teacher->id}");
        Storage::disk('local')->makeDirectory("codetest/{$student->id}");


        Course::factory()->count(1)->create();
    }
}
