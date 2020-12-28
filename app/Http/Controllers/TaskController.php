<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Jetstream;
use function PHPUnit\Framework\isEmpty;

class TaskController extends Controller
{
    const AVAILABLE_TASK_TYPES = ['document', 'quiz', 'card', 'code', 'introCourse'];

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
        $course = Course::find($courseId);

        /*if (! $request->user()->belongsToTeam($course)) {
            abort(403);
        }
        if (! $request->user()->canSeeTask($courseId, $taskId)) {
            return back();
        }*/

        $teacher = User::find($course->user_id);
        $itinerary = $course->getOrderedChaptersWithTasks();

        $tasks = $course->tasks;
        $task = Task::find($taskId);
        $userPositionInCourse = $request->user()->courseProgress($course->id);
        $coursePoints = $request->user()->coursePoints($course->id);
        $courseProgress = $course->progressFromPosition($userPositionInCourse);
        $allowedIds = $tasks->slice(0, $courseProgress['position'])->pluck('id');
        $taskIndexInCourse = $course->taskIndexInCourse($taskId);
        $previous = $taskIndexInCourse>0? $tasks[$taskIndexInCourse-1]->id: null;
        $next = $taskIndexInCourse<=$tasks->count()? $tasks[$taskIndexInCourse+1]->id: null;

        return Jetstream::inertia()->render($request, 'Tasks/Show', [
            'courseDetails' => [
                'id'=>$course->id,
                'name'=>$course->name,
                'degree'=>$course->degree,
                'semester'=>$course->semester,
                'pic'=>$course->pic,
                'teacher'=>$teacher],
            'tasks'=>$itinerary,
            'allowedIds'=>$allowedIds,
            'task'=>[

                'previousId'=>$previous,
                'nextId'=>$next
            ],
            'coursePoints'=>$coursePoints,
            'courseProgress'=>$courseProgress,
        ]);
    }

    /**
     * Show the task creation screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $course
     * @return \Inertia\Response
     */
    public function create(Request $request, $course)
    {
        $course = Course::find($course);
        $chapters = $course->tasks;

        $availableTypes = self::AVAILABLE_TASK_TYPES;

        return Inertia::render('Tasks/Create', ['courseName'=>$course->name, 'courseId'=>$course->id, 'chapters'=>$chapters, 'availableTypes'=>$availableTypes]);
    }
    /**
     * Show the task edit screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $course
     * @return \Inertia\Response
     */
    public function edit(Request $request, $course, $taskId)
    {
        $course = Course::find($course);
        $task = Task::find($taskId);
        $chapters = $course->chapters;
        $availableTypes = self::AVAILABLE_TASK_TYPES;

        return Inertia::render('Tasks/Edit', ['courseName'=>$course->name, 'courseId'=>$course->id, 'chapters'=>$chapters, 'availableTypes'=>$availableTypes, 'task'=>$task]);
    }

    /**
     * Create a new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $course)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'chapter_id'=>'',
            'points' => '',
            'properties' => ''
        ]);
        $validated['course_id'] = $course;

        $task = Task::create($validated);
        $course = Course::find($course);
        $positions = $course->positionArray;
        $chapter_id = isset($validated['chapter_id'])? $validated['chapter_id']: false;
        if($chapter_id){
            array_push($positions[$chapter_id], $task->id);
        }else{
            array_push($positions, $task->id);
            $positions[$task->id] = [];
        }

        $course->positionArray = $positions;
        $course->save();

        return redirect()->route('courses.show',[$course]);
    }

    /**
     * Update the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $task)
    {

        //Gate::forUser($request->user())->authorize('update', $teamAuth);

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'chapter_id' => 'required',
            'points' => 'required',
            'properties' => 'required'

        ])->validateWithBag('updateTask');
        $task = Task::find($task);
        $task->update($validated);

        return back(303);
    }

    /**
     * Delete the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $teamId
     * @param                          $taskId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId, $taskId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $task = Task::find($taskId);
        $task->delete();

        return back();
    }
}
