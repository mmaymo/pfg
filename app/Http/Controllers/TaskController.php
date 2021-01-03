<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Task;
use App\Models\Team;
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

        $teacher = User::find($course->user_id)->name;
        $itinerary = $course->getOrderedChaptersWithTasks();

        $task = Task::find($taskId);

        $coursePoints = Auth::user()->coursePoints($courseId);
        $courseProgress = Auth::user()->courseProgress($courseId);
        $allowedIds = Auth::user()->completedTasks($courseId)->pluck('task_id');
        $flatItineray = $course->orderedTaskIdsFlat();
        $tasksLenght = $course->taskCount();
        $currentTaskPositionIndex = array_search($taskId, $flatItineray);
        $nextIndex = ($currentTaskPositionIndex + 1)< $tasksLenght?$currentTaskPositionIndex + 1:$currentTaskPositionIndex;
        $next = $flatItineray[$nextIndex];
        $previousIndex = ($currentTaskPositionIndex -1)>=0?$currentTaskPositionIndex - 1:$currentTaskPositionIndex;
        $previous = $flatItineray[$previousIndex];

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
                'id'=>$task->id,
                'name'=>$task->name,
                'type'=>$task->type,
                'contents'=>$task->properties,
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

    /**
     * Solve the given task.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $courseId
     * @param                          $taskId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function solveTask(Request $request, $courseId, $taskId)
    {
        $validated = $request->validate([
            'userAnswer' => ['required'],
        ]);

        $message = "La tarea ya estaba completada";
        if(!$this->isDone($courseId, $taskId)){
            $task =Task::find($taskId);

            $correctAnswer = (int)$task->properties['quiz']['correctAnswer'];
            if ($correctAnswer == $validated['userAnswer']) {
                Auth::user()->allCourses()->updateExistingPivot($courseId, ['points' => $task->points]);
            }

            $this->markTaskAsDone($courseId, $taskId);
            //catch error saving in db
            $message = "Tarea completada";
        }

        return response()->json(["index"=>$correctAnswer, "message"=>$message]);
    }

    private function markTaskAsDone(int $courseId, $taskId)
    {
        $task =Task::find($taskId);
        $task->userTasksCompleted()->attach(Auth::user()->id,['course_id'=>$courseId]);

        return true;
    }

    private function isDone($courseId, $taskId)
    {
        return Auth::user()->isCompletedTask($courseId, $taskId);
    }
}
