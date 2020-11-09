<?php

namespace App\Actions\Jetstream;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team and course associated.
     *
     * @param  mixed  $team
     * @return void
     */
    public function delete($team)
    {
        DB::transaction(function () use ($team) {
            $course = $team->course;
            Task::where('course_id', '=', $course->id)->delete();
            $course->delete();
            $team->purge();
        });

    }
}
