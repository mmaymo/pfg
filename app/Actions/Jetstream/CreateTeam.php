<?php

namespace App\Actions\Jetstream;

use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Jetstream;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     * Creates the associated course too
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        $newTeam = $user->ownedTeams()->create([
                                                   'name' => $input['name'],
                                                   'personal_team' => false,
                                               ]);

        Course::create(['team_id'=>$newTeam->id]);

        return $newTeam;
    }
}
