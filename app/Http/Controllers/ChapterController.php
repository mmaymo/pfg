<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
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

class ChapterController extends Controller
{

    /**
     * Add a new member to the course.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'courseId'=>['required','int']
        ])->validateWithBag('addCourseMember');
        $course = Course::findOrFail($validated['courseId']);
        Chapter::create(['name'=>$validated['name'], 'course_id'=>$validated['courseId'], 'tasksPositionArray'=>serialize([])]);

        return back(303);
    }

    /**
     * Update the role of a given member
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $courseId)
    {

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
