<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

        if($course->users()->where('email', $validated['email'])->exists()){
            return back(303);
        }
        if(User::where('email',$validated['email'])->exists()){
            $newCourseMember = User::where('email',$validated['email'])->first();
        }else{
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);

            $hashed_random_password = Hash::make($password);
            $newCourseMember = User::Create(
                [
                    "name" => "edita nombre",
                    "email" => $validated['email'],
                    "password" => $hashed_random_password
                ]
            );
        }

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
        $completed = $user->completedTasks($courseId);
        $ids = $completed->pluck('task_id');
        $user->tasks()->detach($ids);

        return back(303);
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
