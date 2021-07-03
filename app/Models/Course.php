<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable
        = [
            'user_id',
            'name',
            'degree',
            'semester',
            'pic',
            'positionArray'
        ];
    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'positionArray' => 'array',
    ];

    /**
     * The users that are enrolled in this course
     *
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('points');
    }

    /**
     * The array holding details for the members enrolled in the course
     *
     * @return array
     */
    public function getCourseMembersDetails(): array
    {
        $users = $this->users;
        $members = [];
        foreach ($users as $user) {
            $members[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'points' => $this->getUserPoints($user),
                'profile_photo_url' => $user->profile_photo_path,
                'progress' => $this->getUserCourseProgress($user)
            ];
        }
        return $members;
    }

    /**
     * The tasks that belong to the course
     *
     */
    public function tasks(): HasMany
    {
        return $this->hasMany('App\Models\Task', 'course_id');
    }

    /**
     * A flat array with the ordered tasks
     *
     */
    public function orderedTaskIdsFlat(): array
    {
        $positions = $this->positionArray;
        $keysChapters = array_keys ($positions );
        $flattenArray = array();
        for($i = 0; $i <count($positions); $i++){
            $flattenArray[] = $keysChapters[$i];
            foreach($positions[$keysChapters[$i]] as $chapterItem){
                $flattenArray[] = $chapterItem;
            }
        }
        return $flattenArray;
    }

    /**
     * A collection with the ordered tasks nested in their chapters
     *
     */
    public function getOrderedChaptersWithTasks(): Collection
    {
        $chapters = $this->positionArray;
        $orderedChapters = collect();

        if(!is_array($chapters) || empty($chapters)){
            return $orderedChapters;
        }
        foreach ($chapters as $chapter => $value){
            $selectedTask = $this->findTask($chapter);
            $selectedTask->tasks = collect();

            if(!empty($value)){
                foreach ($value as $taskId){
                    $task = $this->findTask($taskId);
                    $task = $task->clean_task;
                    $selectedTask->tasks->push($task);
                }
            }
            $orderedChapters->push($selectedTask);
        }

        return $orderedChapters;
    }

    /**
     * Save the order of the tasks passed as parameter
     *
     * @var array
     */
    public function insertPositions(array $positions){
        $this->positionArray = $positions;
        $this->saveCourse();
    }

    /**
     * Returns the number of tasks assigned to this course
     *
     */
    public function taskCount(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Removes the task given from the positions array
     *
     * @var int
     */
    public function deleteTaskFromPositions($taskId)
    {
        $chapters = $this->positionArray;
        if(array_key_exists($taskId, $chapters)){
            $children = $chapters[$taskId];
            unset($chapters[$taskId]);
            foreach($children as $child){
                $chapters[$child]= [];
            }
        }
        else{
            foreach($chapters as $key =>$subtasks){
                $chapters[$key] = array_filter($subtasks, function($tasks) use($taskId){
                    return $tasks != $taskId;
                });
            }
        }
        $this->positionArray = $chapters;
        $this->saveCourse();
    }

    /**
     * Delete all tasks from the course
     *
     */
    public function deleteAllTasks(){
        Task::where('course_id', $this->id)->delete();
    }

    /**
     * Delete all members of the course
     *
     */
    public function deleteAllMembers(){
        $this->users()->detach();
    }

    /**
     * Get course details to show on edit page
     * @param $course
     *
     * @return array
     */
    public function courseDetailsEditPage($course): array
    {
        $userList = User::all();
        $students = $course->getCourseMembersDetails();
        $itinerary = $course->getOrderedChaptersWithTasks();
        return [
            'courseDetails' => [
                'id' => $course->id,
                'name' => $course->name,
                'degree' => $course->degree,
                'semester' => $course->semester,
                'pic' => $course->pic
            ],
            'students' => $students,
            'tasks' => $itinerary,
            'userList' => $userList
        ];
    }

    /**
     * Returns a new ordered array of tasks
     *
     * @param $orderedContentIds
     *
     * @return array
     */
    public function reorderTasks($orderedContentIds): array
    {
        $wholeObject = $orderedContentIds;
        $newOrder = [];
        foreach ($wholeObject as $content) {
            $newOrder[$content['id']] = [];
            foreach ($content['tasks'] as $task) {
                array_push($newOrder[$content['id']], $task['id']);
            }
        }
        return $newOrder;
    }

    /**
     * @param $taskId
     *
     * @return mixed
     */
    protected function findTask($taskId)
    {
        return Task::find($taskId);
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    protected function getUserPoints($user)
    {
        return $user->pivot->points;
}

    /**
     * @param $user
     *
     * @return mixed
     */
    protected function getUserCourseProgress($user)
    {
        return $user->courseProgress($this->id);
}

    protected function saveCourse(): void
    {
        $this->save();
    }
}
