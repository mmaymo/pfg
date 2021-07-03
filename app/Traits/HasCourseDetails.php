<?php


namespace App\Traits;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

trait HasCourseDetails
{
    /**
     * Returns the courses this user is enrolled in with progress
     *
     * @return BelongsToMany
     */
    public function coursesEnrolled(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Course', 'users_course_progress');
    }

    /**
     * Returns the courses this user is enrolled in
     *
     * @return HasMany
     */
    public function courses(): HasMany
    {
        return $this->hasMany('App\Models\Course');
    }

    /**
     * Returns the user tasks completed
     *
     * @return BelongsToMany
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Task', 'task_user', 'user_id', 'task_id')->withPivot('course_id');
    }

    /**
     * Returns the completed tasks by this user
     *
     * @param $courseId
     *
     * @return Collection
     */
    public function completedTasks($courseId): Collection
    {
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
    }

    /**
     * Check if the task is completed by this user
     *
     * @param $courseId
     * @param $taskId
     *
     * @return bool
     */
    public function isTaskCompleted($courseId, $taskId): bool
    {
        return DB::table('task_user')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId], ['task_id', '=', $taskId]]
        )->exists();
    }

    /**
     * Returns the number of completed tasks
     *
     * @param $courseId
     *
     * @return int
     */
    public function completedTasksCount($courseId): int
    {
        return $this->completedTasks($courseId)->count();
    }

    /**
     * Returns the points of the course for this user
     *
     * @param $courseId
     *
     * @return int
     */
    public function coursePoints($courseId): int
    {
        $collection = DB::table('users_course_progress')->where(
            [['user_id', '=', $this->id], ['course_id', '=', $courseId]]
        )->get();
        return $collection->first()?$collection->first()->points:0;
    }

    /**
     * Returns the progress of the course for this user
     *
     * @param $courseId
     *
     * @return float|int
     */
    public function courseProgress($courseId){
        $course = Course::find($courseId);
        $tasksCourseCount = $course->taskCount();
        if($tasksCourseCount == 0){
            return 0;
        }
        $userTasksDone =$this->completedTasksCount($courseId);
        return ($userTasksDone / $tasksCourseCount) * 100;
    }


    /**
     * Check if the user can see this task
     *
     * @param $courseId
     * @param $taskId
     *
     * @return bool
     */
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
     * Show the task with course details
     *
     * @param         $course
     * @param int     $courseId
     * @param int     $taskId
     * @param Request $request
     * @param         $teacher
     *
     * @return Response
     */
    public function getTaskCourseDetails(
        $course,
        int $courseId,
        int $taskId,
        Request $request,
        $teacher
    ): Response {
        $coursePoints = Auth::user()->coursePoints($courseId);
        $courseProgress = Auth::user()->courseProgress($courseId);
        $allowedIds = Auth::user()->getAllowedTasksIdsCollection($courseId);
        $task = $this->getCleanedTask($taskId);
        if (!$request->user()->canSeeTask($courseId, $taskId)) {
            return Jetstream::inertia()->render(
                $request,
                'Tasks/Show',
                [
                    'allowed' => false,
                    'courseDetails' => $this->getCourseDetails(
                        $course,
                        $teacher
                    ),
                    'tasks' => $this->getOrderedCourseTasks($course),
                    'allowedIds' => $allowedIds,
                    'task' => $this->notAllowedUserTaskDetails($task),
                    'coursePoints' => $coursePoints,
                    'courseProgress' => $courseProgress,
                ]
            );
        }
        $taskDone = $this->isDone($courseId, $taskId);

        return Jetstream::inertia()->render(
            $request,
            'Tasks/Show',
            [
                'allowed' => true,
                'courseDetails' => $this->getCourseDetails(
                    $course,
                    $teacher
                ),
                'tasks' => $this->getOrderedCourseTasks($course),
                'allowedIds' => $allowedIds,
                'task' => $this->allowedUserTaskDetails(
                    $course,
                    $task,
                    $taskDone
                ),
                'coursePoints' => $coursePoints,
                'courseProgress' => $courseProgress,
            ]
        );
    }

    /**
     * Show the flashcard task with course details
     *
     * @param int     $courseId
     * @param Request $request
     *
     * @return Response
     */
    public function getFlashCardTaskCourseDetails(
        int $courseId,
        Request $request
    ): Response {
        $course = Course::find($courseId);
        $teacher = User::find($course->user_id)->name;
        $coursePoints = Auth::user()->coursePoints($courseId);
        $courseProgress = Auth::user()->courseProgress($courseId);
        $allowedIds = $this->getAllowedTasksIdsCollection($courseId);
        $flashTasks = Task::where('type', 'card')->get();

        return Jetstream::inertia()->render(
            $request,
            'Tasks/Flashcard',
            [
                'courseDetails' => $this->getCourseDetails(
                    $course,
                    $teacher
                ),
                'tasks' => $this->getOrderedCourseTasks($course),
                'allowedIds' => $allowedIds,
                'cards' => $flashTasks,
                'coursePoints' => $coursePoints,
                'courseProgress' => $courseProgress,
            ]
        );
    }

    /**
     * Process the task to completed and adds points
     *
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
     * Process the quiz task to completed, adds the points
     * and returns the correct answer
     *
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
     * Returns the collection of Ids of the allowed tasks
     * for the user in the given course
     *
     * @param int $courseId
     *
     * @return Collection
     */
    protected function getAllowedTasksIdsCollection(int $courseId
    ): Collection {
        $allowedIds = Task::all()->filter(
            function ($task) use ($courseId) {
                return $task->isAllowed($this->id, $courseId);
            }
        )->pluck('id');
        return $allowedIds;
    }


    /**
     * Check if the task is completed
     *
     * @param $courseId
     * @param $taskId
     *
     * @return bool
     */
    public function isDone($courseId, $taskId)
    {
        return $this->isTaskCompleted($courseId, $taskId);
    }

    /**
     * @param $course
     * @param $teacher
     *
     * @return array
     */
    protected function getCourseDetails($course, $teacher): array
    {
        return [
            'id' => $course->id,
            'name' => $course->name,
            'degree' => $course->degree,
            'semester' => $course->semester,
            'pic' => $course->pic,
            'teacher' => $teacher
        ];
    }

    /**
     * @param $course
     *
     * @return mixed
     */
    protected function getOrderedCourseTasks($course)
    {
        return $course->getOrderedChaptersWithTasks();
    }

    /**
     * @param     $course
     * @param int $taskId
     *
     * @return array
     */
    protected function calculatePreviousAndNextTask($course, int $taskId): array
    {
        $flatItineray = $course->orderedTaskIdsFlat();
        $tasksLenght = $course->taskCount();
        $currentTaskPositionIndex = array_search($taskId, $flatItineray);
        $nextIndex = ($currentTaskPositionIndex + 1) < $tasksLenght
            ? $currentTaskPositionIndex + 1 : $currentTaskPositionIndex;
        $next = $flatItineray[$nextIndex];
        $previousIndex = ($currentTaskPositionIndex - 1) >= 0
            ? $currentTaskPositionIndex - 1 : $currentTaskPositionIndex;
        $previous = $flatItineray[$previousIndex];
        return array($next, $previous);
    }

    /**
     * @param      $task
     * @param bool $taskDone
     * @param      $previous
     * @param      $next
     *
     * @return array
     */
    protected function allowedUserTaskDetails(
        $course,
        $task,
        bool $taskDone
    ): array {
        list($next, $previous) = $this->calculatePreviousAndNextTask(
            $course,
            $task['id']
        );
        return [
            'id' => $task['id'],
            'name' => $task['name'],
            'type' => $task['type'],
            'contents' => $task['properties'],
            'isDone' => $taskDone,
            'previousId' => $previous,
            'nextId' => $next,
            'user' => Auth::user()->getAuthIdentifier()
        ];
    }

    /**
     * @param int $taskId
     *
     * @return mixed
     */
    protected function getCleanedTask(int $taskId)
    {
        $task = Task::find($taskId);
        $task = $task->getCleanTaskAttribute();
        return $task;
    }

    /**
     * @param $task
     *
     * @return array
     */
    protected function notAllowedUserTaskDetails($task): array
    {
        return [
            'id' => $task->id,
            'name' => $task->name,
            'type' => $task->type
        ];
    }

}
