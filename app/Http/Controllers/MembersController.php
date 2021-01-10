<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Jetstream;

class MembersController extends Controller
{
    const ALUMNO = 'alumno';

    /**
     * Add a new member to the course.
     *
     * @param \Illuminate\Http\Request $request
     * @param $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::find($courseId);
        $validated = Validator::make($request->all(), [
            'email' => ['string', 'max:255']
        ])->validateWithBag('addCourseMember');

        $newCourseMember = Jetstream::findUserByEmailOrFail($validated['email']);

        $course->users()->attach($newCourseMember,['points'=>0]);
        $newCourseMember->assignRole(self::ALUMNO);


        return back(303);
    }

    /**
     * Update the role of a given member
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $courseId, $userId)
    {
        $user = User::find($userId);
        $user->coursesEnrolled()->updateExistingPivot($courseId, ['points' => 0]);
        $user->tasks->whereIn('course_id',$courseId)->delete();


    }

    /**
     * Delete a given member from the course.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $courseId
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $courseId, $userId)
    {
        $course = Course::find($courseId);
        $course->users()->detach($userId);

        return back(303);
    }
}
