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
        $teacher = User::factory()->count(1)->create();
        $student = User::factory()->count(1)->create();
        $role = Role::create(['name' => 'profesor']);
        $permission = Permission::create(['name' => 'edit courses']);
        $role->syncPermissions($permission);
        $permission->syncRoles($role);
        $role = Role::create(['name' => 'alumno']);
        $permission = Permission::create(['name' => 'view courses']);
        $role->syncPermissions($permission);
        $permission->syncRoles($role);

        $teacher->assignRole('profesor');


        $course = Course::factory()->count(1)->create();
        //todo tasks from json
        

    }
}
