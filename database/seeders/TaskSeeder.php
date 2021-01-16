<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->delete();
        $json = Storage::disk('local')->get("dyaso.json");
        $data = json_decode($json);
        foreach ($data as $task){
            Task::create([
                'name' => $task->name,
                'type' => $task->type,
                'chapter_id'=>$task->chapter_id,
                'course_id'=>$task->course_id,
                'points' => $task->points,
                'properties' => $task->properties
            ]);
        }
    }
}
