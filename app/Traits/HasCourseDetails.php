<?php


namespace App\Traits;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Jetstream;

trait HasCourseDetails
{
    public function coursesEnrolled()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress');
    }

    public function coursesEnrolledWithPoints()
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress')
            ->withPivot('points');
    }
    public function courses(){
        return $this->hasMany('App\Models\Course');
    }
    public function tasks(){
        return $this->belongsToMany('App\Models\Task', 'task_user', 'user_id', 'task_id')->withPivot('course_id');
    }

    public function completedTasks($courseId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
    }
    public function isTaskCompleted($courseId, $taskId){
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId], ['task_id', '=', $taskId]]
        )->exists();
    }

    public function completedTasksCount($courseId){
        return $this->completedTasks($courseId)->count();
    }

    public function coursePoints($courseId)
    {
        $collection = DB::table('users_course_progress')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
        return $collection->first()?$collection->first()->points:0;
    }

    public function courseProgress($courseId){
        $course = Course::find($courseId);
        $tasksCourseCount = $course->taskCount();
        $userTasksDone =$this->completedTasksCount($courseId);
        return ($userTasksDone / $tasksCourseCount) * 100;
    }


    public function canSeeTask($courseId, $taskId)
    {
        $completedTasks = $this->completedTasks($courseId);
        $isDone = $completedTasks->contains('task_id', $taskId);
        if($isDone){
            return true;
        }

        $task = Task::find($taskId);
        if($task->emptyParent()){
            return true;
        }

        $parentIsDone = $completedTasks->contains('task_id', $task->parent_id);
        if($parentIsDone){
            return true;
        }
        return false;
    }


    /**
     * @param         $course
     * @param int     $courseId
     * @param int     $taskId
     * @param Request $request
     * @param         $teacher
     *
     * @return \Inertia\Response
     */
    public function getTaskCourseDetails(
        $course,
        int $courseId,
        int $taskId,
        Request $request,
        $teacher
    ): \Inertia\Response {
        $itinerary = $course->getOrderedChaptersWithTasks();
        $coursePoints = Auth::user()->coursePoints($courseId);
        $courseProgress = Auth::user()->courseProgress($courseId);
        $allowedIds = Auth::user()->getAllowedTasksIdsCollection($courseId);
        $task = Task::find($taskId);
        if (!$request->user()->canSeeTask($courseId, $taskId)) {
            return Jetstream::inertia()->render(
                $request,
                'Tasks/Show',
                [
                    'allowed' => false,
                    'courseDetails' => [
                        'id' => $course->id,
                        'name' => $course->name,
                        'degree' => $course->degree,
                        'semester' => $course->semester,
                        'pic' => $course->pic,
                        'teacher' => $teacher
                    ],
                    'tasks' => $itinerary,
                    'allowedIds' => $allowedIds,
                    'task' => [
                        'id' => $task->id,
                        'name' => $task->name,
                        'type' => $task->type
                    ],
                    'coursePoints' => $coursePoints,
                    'courseProgress' => $courseProgress,
                ]
            );
        }
        $taskDone = $this->isDone($courseId, $taskId);
        $flatItineray = $course->orderedTaskIdsFlat();
        $tasksLenght = $course->taskCount();
        $currentTaskPositionIndex = array_search($taskId, $flatItineray);
        $nextIndex = ($currentTaskPositionIndex + 1) < $tasksLenght
            ? $currentTaskPositionIndex + 1 : $currentTaskPositionIndex;
        $next = $flatItineray[$nextIndex];
        $previousIndex = ($currentTaskPositionIndex - 1) >= 0
            ? $currentTaskPositionIndex - 1 : $currentTaskPositionIndex;
        $previous = $flatItineray[$previousIndex];

        return Jetstream::inertia()->render(
            $request,
            'Tasks/Show',
            [
                'allowed' => true,
                'courseDetails' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'degree' => $course->degree,
                    'semester' => $course->semester,
                    'pic' => $course->pic,
                    'teacher' => $teacher
                ],
                'tasks' => $itinerary,
                'allowedIds' => $allowedIds,
                'task' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'type' => $task->type,
                    'contents' => $task->properties,
                    'isDone' => $taskDone,
                    'previousId' => $previous,
                    'nextId' => $next,
                    'user' => Auth::user()->getAuthIdentifier()
                ],
                'coursePoints' => $coursePoints,
                'courseProgress' => $courseProgress,
            ]
        );
    }

    /**
     * @param         $course
     * @param int     $courseId
     * @param Request $request
     *
     * @return \Inertia\Response
     */
    public function getFlashCardTaskCourseDetails(
        int $courseId,
        Request $request
    ): \Inertia\Response {
        $course = Course::find($courseId);
        $teacher = User::find($course->user_id)->name;
        $itinerary = $course->getOrderedChaptersWithTasks();
        $coursePoints = Auth::user()->coursePoints($courseId);
        $courseProgress = Auth::user()->courseProgress($courseId);
        $allowedIds = $this->getAllowedTasksIdsCollection($courseId);
        $flashTasks = Task::where('type', 'card')->get();

        return Jetstream::inertia()->render(
            $request,
            'Tasks/Flashcard',
            [
                'courseDetails' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'degree' => $course->degree,
                    'semester' => $course->semester,
                    'pic' => $course->pic,
                    'teacher' => $teacher
                ],
                'tasks' => $itinerary,
                'allowedIds' => $allowedIds,
                'cards' => $flashTasks,
                'coursePoints' => $coursePoints,
                'courseProgress' => $courseProgress,
            ]
        );
    }

    /**
     * @param int $courseId
     * @param     $taskId
     */
    public function processTaskForUser(int $courseId, $taskId): void
    {
        if (!$this->isDone($courseId, $taskId)) {
            $task = Task::find($taskId);
            $previousPoints = $this->coursePoints($courseId);
            $this->coursesEnrolled()->updateExistingPivot(
                $courseId,
                [
                    'points' => $previousPoints + $task->points
                ]
            );
            $task->markTaskAsDone($courseId, $task->points);
        }
    }

    /**
     * @param     $taskId
     * @param int $courseId
     * @param     $userAnswer
     *
     * @return array
     */
    public function processQuizTaskForUser(
        $taskId,
        int $courseId,
        $userAnswer
    ): array {
        $message = "La tarea ya estaba completada";
        $task = Task::find($taskId);
        $correctAnswers =  $task->properties['quiz']['correctAnswer'];
        if (!$this->isDone($courseId, $taskId)) {
            sort($correctAnswers);
            sort($userAnswer);
            $points = 0;
            if ($correctAnswers == $userAnswer) {
                $previousPoints = $this->coursePoints($courseId);
                $points = $task->points;
                $this->coursesEnrolled()->updateExistingPivot(
                    $courseId,
                    [
                        'points' => $previousPoints + $task->points
                    ]
                );
            }
            $task->markTaskAsDone($courseId, $points);
            $message = "Tarea completada";
        }

        return ["index" => $correctAnswers, "message" => $message];
    }

    /**
     * @param int $courseId
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAllowedTasksIdsCollection(int $courseId
    ): \Illuminate\Support\Collection {
        $allowedIds = Task::all()->filter(
            function ($task) use ($courseId) {
                return $task->isAllowed($this->id, $courseId);
            }
        )->pluck('id');
        return $allowedIds;
    }


    public function isDone($courseId, $taskId)
    {
        return $this->isTaskCompleted($courseId, $taskId);
    }

}
