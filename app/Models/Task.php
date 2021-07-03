<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'course_id',
            'chapter_id',
            'parent_id',
            'type',
            'points',
            'properties'
        ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'properties' => 'array',
        ];


    /**
     * Returns the course of this task
     *
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    /**
     * Returns the Tasks completed by this user
     *
     * @return BelongsToMany
     */
    public function userTasksCompleted(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'task_user');
    }

    /**
     * Mark the task as completed for this user
     *
     * @param int $courseId
     * @param     $points
     *
     * @return bool
     */
    public function markTaskAsDone(int $courseId, $points): bool
    {
        $this->userTasksCompleted()->attach(
            Auth::user()->id,
            [
                'course_id' => $courseId,
                'points' => $points
            ]
        );

        return true;
    }

    /**
     * Filter the output of the tasks
     *
     * @return Collection
     */
    public function getCleanTaskAttribute(): Collection
    {
        $attributes = $this->properties;
        if ($this->type == 'quiz') {
            $attributes['quiz']['correctAnswer'] = null;
        }

        $cleanTask = collect(
            [
                'id' => $this->id,
                'name' => $this->name,
                'points' => $this->points,
                'type' => $this->type,
                'properties' => $attributes
            ]
        );
        return $cleanTask;
    }

    /**
     * Check if this user is allowed to see this task
     *
     * @param $userId
     * @param $courseId
     *
     * @return bool
     */
    public function isAllowed($userId, $courseId): bool
    {
        return $this->emptyParent() || $this->isCompleted($userId, $courseId)
            || $this->parentIsCompleted($userId, $courseId);
    }

    /**
     * Check if this task has a parent task
     *
     * @return bool
     */
    public function emptyParent(): bool
    {
        return $this->parent_id == null;
    }

    /**
     * Returns the list of completed task of this user
     *
     * @param $userId
     * @param $courseId
     *
     * @return bool
     */
    public function isCompleted($userId, $courseId): bool
    {
        return $this->completedTasks($userId, $courseId);
    }

    /**
     * Check if this task is completed by this user
     *
     * @param $userId
     * @param $courseId
     *
     * @return bool
     */
    public function completedTasks($userId, $courseId): bool
    {
        return DB::table('task_user')->where(
            [
                ['task_id', '=', $this->id],
                ['user_id', '=', $userId],
                ['course_id', '=', $courseId]
            ]
        )->exists();
    }

    /**
     * Check if the parent task is completed
     *
     * @param $userId
     * @param $courseId
     *
     * @return bool
     */
    public function parentIsCompleted($userId, $courseId): bool
    {
        if ($this->parent_id == null) {
            return true;
        }
        $parent = Task::find($this->parent_id);

        return $parent->isCompleted($userId, $courseId);
    }

    /**
     * Adds points to the user for this task
     *
     * @param $isDone
     * @param $isCorrect
     * @param $courseId
     */
    protected function maybeAddPoints(
        $isDone,
        $isCorrect,
        $courseId
    ): void {
        if (!$isDone) {
            if ($isCorrect) {
                $previousPoints = Auth::user()->coursePoints($courseId);
                Auth::user()->coursesEnrolled()->updateExistingPivot(
                    $courseId,
                    [
                        'points' => $previousPoints + $this->points
                    ]
                );
                $this->userTasksCompleted()->attach(
                    Auth::user()->id,
                    [
                        'course_id' => $courseId,
                        'points' => $this->points
                    ]
                );
            } else {
                $this->userTasksCompleted()->attach(
                    Auth::user()->id,
                    [
                        'course_id' => $courseId,
                        'points' => 0
                    ]
                );
            }
        }
    }

    /**
     * Returns the collection of allowed tasks for this user
     *
     * @param int $courseId
     *
     * @return Collection
     */
    protected function getAllowedTasksIdsCollection(int $courseId
    ): Collection {
        $allowedIds = Task::all()->filter(
            function ($task) use ($courseId) {
                return $task->isAllowed(Auth::user()->id, $courseId);
            }
        )->pluck('id');
        return $allowedIds;
    }
}
