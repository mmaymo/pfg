<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TaskController extends Controller
{
    const AVAILABLE_TASK_TYPES
        = [
            'document',
            'quiz',
            'multipleQuiz',
            'card',
            'code',
            'introCourse',
            'chapter'
        ];

    /**
     * Show the task contents.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param int                      $taskId
     *
     * @return \Inertia\Response |\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $courseId, $taskId)
    {
        Log::debug('Show task method', [$request->all(), $courseId, $taskId]);
        $course = Course::find($courseId);
        $teacher = User::find($course->user_id)->name;
        if (!$request->user()->can('view courses')) {
            abort(403);
        }

        return Auth::user()->getTaskCourseDetails(
            $course,
            $courseId,
            $taskId,
            $request,
            $teacher
        );
    }

    /**
     * Show the task contents.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     * @return \Inertia\Response |\Illuminate\Http\RedirectResponse
     */
    public function flashCardsShuffle(Request $request, $courseId)
    {
        if (!$request->user()->can('view courses')) {
            abort(403);
        }

        return Auth::user()->getFlashCardTaskCourseDetails(
            $courseId,
            $request
        );
    }

    /**
     * Show the task creation screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $course
     *
     * @return \Inertia\Response
     */
    public function create(Request $request, $course)
    {
        if (!$request->user()->can('edit courses')) {
            abort(403);
        }
        $course = Course::find($course);
        $chapters = $course->tasks;
        $availableTypes = self::AVAILABLE_TASK_TYPES;

        return Inertia::render(
            'Tasks/Create',
            [
                'courseName' => $course->name,
                'courseId' => $course->id,
                'chapters' => $chapters,
                'availableTypes' => $availableTypes
            ]
        );
    }

    /**
     * Show the task edit screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $course
     *
     * @return \Inertia\Response
     */
    public function edit(Request $request, $course, $taskId)
    {
        if (!$request->user()->can('edit courses')) {
            abort(403);
        }
        $course = Course::find($course);
        $task = Task::find($taskId);
        $chapters = $course->tasks;
        $availableTypes = self::AVAILABLE_TASK_TYPES;

        return Inertia::render(
            'Tasks/Edit',
            [
                'courseName' => $course->name,
                'courseId' => $course->id,
                'chapters' => $chapters,
                'availableTypes' => $availableTypes,
                'task' => $task
            ]
        );
    }

    /**
     * Create a new task.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $course)
    {
        if (!$request->user()->can('edit courses')) {
            abort(403);
        }
        $validated = $request->validate(
            [
                'name' => 'required',
                'type' => 'required',
                'chapter_id' => '',
                'points' => '',
                'properties' => ''
            ]
        );
        $validated['course_id'] = $course;

        $task = Task::create($validated);
        $course = Course::find($course);
        $positions = $course->positionArray;
        $chapter_id = isset($validated['chapter_id']) ? $validated['chapter_id']
            : false;
        if ($chapter_id) {
            array_push($positions[$chapter_id], $task->id);
        } else {
            $positions[$task->id] = [];
        }

        $course->positionArray = $positions;
        $course->save();

        return redirect()->route('courses.show', [$course]);
    }

    /**
     * Update the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $task
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $courseId, $taskId)
    {
        if (!$request->user()->can('edit courses')) {
            abort(403);
        }

        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'type' => 'required',
                'chapter_id' => 'nullable',
                'points' => 'required',
                'properties' => 'required',
                'parent_id' => 'nullable'
            ]
        )->validateWithBag('updateTask');
        $task = Task::find($taskId);

        $task->update($validated);

        return back(303);
    }

    /**
     * Delete the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param                          $taskId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $courseId, $taskId)
    {
        if (!$request->user()->can('edit courses')) {
            abort(403);
        }

        $task = Task::find($taskId);
        $course = Course::find($courseId);
        $course->deleteTaskFromPositions($taskId);
        $task->delete();

        return back();
    }

    /**
     * Solve the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDone(Request $request, int $courseId)
    {
        $validated = $request->validate(
            [
                'nextId' => ['required'],
                'taskId' => ['required']
            ]
        );
        $taskId = $validated['taskId'];

        Auth::user()->processTaskForUser($courseId, $taskId);

        return redirect()->route(
            'courses.tasks.show',
            [
                'course' => $courseId,
                'task' => $validated['nextId']
            ]
        );
    }

    /**
     * Solve the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param                          $taskId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function solveTaskMultiple(Request $request, $courseId, $taskId)
    {

        $validated = $request->validate(
            [
                'userAnswer' => ['required'],
            ]
        );

        list($message, $correctAnswers) = Auth::user()->processQuizTaskForUser(
            $taskId,
            $courseId,
            $validated['userAnswer']
        );
        return response()->json(
            ["index" => $correctAnswers, "message" => $message]
        );
    }


}
