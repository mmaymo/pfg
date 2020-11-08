<?php

namespace App\Actions\Jetstream;

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
            $course->delete();
            $team->purge();
        });

    }
}
