<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class CourseController extends Controller
{

    /**
     * Show the course contents management screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Inertia\Response
     */
    public function show(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        return Jetstream::inertia()->render(
            $request,
            'Courses/Show',
            [
                'course' => $course->courseDetailsEditPage($course),
            ]
        );
    }

    /**
     * Show the course creation screen.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Courses/Create');
    }

    /**
     * Create a new course.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => ['string', 'max:255'],
                'degree' => ['nullable', 'string', 'max:255'],
                'semester' => ['nullable', 'boolean'],
                'pic' => ['nullable', 'string']
            ]
        )->validateWithBag('createCourse');

        $course = Course::create([
                                     'user_id' => Auth::user()->id,
                                     'name' =>  $validated['name'],
                                     'degree' => $validated['degree']?:'',
                                     'semester' => $validated['semester']?:false,
                                     'pic' => $validated['pic']?:'',
                                     'positionArray' => []
                                 ]);

        return redirect()->route('courses.show', [$course]);
    }

    /**
     * Update the given course's details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $courseId)
    {
        $course = Course::find($courseId);

        $validated = Validator::make(
            $request->all(),
            [
                'name' => ['string', 'max:255'],
                'degree' => ['nullable', 'string', 'max:255'],
                'semester' => ['nullable', 'boolean'],
                'pic' => ['nullable', 'string']
            ]
        )->validateWithBag('updateCourseMain');

        $course->update(
            [
                'name' => $validated['name'],
                'degree' => $validated['degree'],
                'semester' => $validated['semester'],
                'pic' => $validated['pic'],
            ]
        );

        return back(303);
    }

    /**
     * Delete the given course.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        DB::transaction(
            function () use ($course) {
                $course->users()->detach();
                $course->delete();
            }
        );

        return redirect(config('fortify.home'));
    }

    /**
     * Update course's tasks order.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCourseTasksOrder(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        $validated = Validator::make(
            $request->all(),
            [
                'orderedContentIds' => ['array'],
            ]
        )->validateWithBag('updateOrder');
        $newOrder = $course->reorderTasks($validated['orderedContentIds']);
        $course->insertPositions($newOrder);

        return back(303);
    }

    /**
     * Delete all tasks in the course
     * @param $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAllTasks($courseId)
    {
        $course = Course::find($courseId);
        $course->deleteAllTasks();

        return back(303);
    }

    /**
     * Delete all enrolled members
     * @param $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAllMembers($courseId)
    {
        $course = Course::find($courseId);
        $course->deleteAllMembers();

        return back(303);
    }
}
