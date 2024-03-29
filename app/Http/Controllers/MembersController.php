<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MembersController extends Controller
{
    const ALUMNO = 'alumno';
    const TEACHER = 'profesor';

    /**
     * Add a new member to the course.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        $validated = Validator::make(
            $request->all(),
            [
                'file' => [],
                'email' => ['string', 'max:255']
            ]
        )->validateWithBag('addCourseMember');
        if (isset($validated['file'])) {
            $fileName = time() . '_' . $request->file('file')
                    ->getClientOriginalName();
            $request->file('file')->storeAs("studentsBatch/", $fileName);
            $csv = fopen("../storage/app/studentsBatch/{$fileName}", 'r');
            while (($row = fgetcsv($csv, 0, ",")) !== false) {
                if (User::where('email', $row[2])->exists()) {
                    $newCourseMember = User::where('email', $row[2])->first();
                } else {
                    $name = "{$row[0]} {$row[1]}";
                    $newCourseMember = $this->addNewMember($row[2], $name);
                }

                $this->addMemberRoleAndPoints($course, $newCourseMember);
            }
            return back(303);
        }

        if ($course->users()->where('email', $validated['email'])->exists()) {
            return back(303);
        }
        if (User::where('email', $validated['email'])->exists()) {
            $newCourseMember = User::where('email', $validated['email'])->first(
            );
        } else {
            $newCourseMember = $this->addNewMember($validated['email']);
        }

        $this->addMemberRoleAndPoints($course, $newCourseMember);

        return back(303);
    }

    /**
     * Add a new member with just the email
     *
     * @param string $mail
     * @param string $name
     *
     * @return mixed
     */
    protected function addNewMember($mail, $name = 'Jane Doe')
    {
        $random = str_shuffle(
            'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&'
        );
        $password = substr($random, 0, 10);

        $hashed_random_password = Hash::make($password);
        return User::Create(
            [
                "name" => $name,
                "email" => $mail,
                "password" => $hashed_random_password
            ]
        );
    }

    /**
     * Initiates the new member to the course, adds the folder for code tests and initial points
     *
     * @param $course
     * @param $newCourseMember
     */
    protected function addMemberRoleAndPoints($course, $newCourseMember): void
    {
        Storage::disk('local')->makeDirectory(
            "codetest/{$newCourseMember->id}"
        );
        $course->users()->attach($newCourseMember, ['points' => 0]);
        $newCourseMember->assignRole(self::ALUMNO);
    }

    /**
     * Add a new teacher to the course.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addTeacher(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'email' => ['string', 'max:255']
            ]
        )->validateWithBag('addCourseTeacherMember');

        if (User::where('email', $validated['email'])->exists()) {
            $newCourseMember = User::where('email', $validated['email'])->first(
            );
        } else {
            $newCourseMember = $this->addNewMember($validated['email']);
        }

        $this->addTeacherRole($newCourseMember);

        return back(303);
    }


    /**
     * Assigns the role teacher to the given member
     *
     * @param $newCourseMember
     */
    protected function addTeacherRole($newCourseMember)
    {
        $newCourseMember->assignRole(self::TEACHER);
    }

    /**
     * Update the role of a given member
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $courseId, $userId)
    {
        $user = User::find($userId);
        $user->coursesEnrolled()->updateExistingPivot(
            $courseId,
            ['points' => 0]
        );
        $completed = $user->completedTasks($courseId);
        $ids = $completed->pluck('task_id');
        $user->tasks()->detach($ids);

        return back(303);
    }

    /**
     * Delete a given member from the course.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param int                      $userId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $courseId, $userId)
    {
        $course = Course::find($courseId);
        $course->users()->detach($userId);

        return back(303);
    }
}
