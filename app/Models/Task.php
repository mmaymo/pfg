<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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




    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function userTasksCompleted()
    {
        return $this->belongsToMany('App\Models\User', 'task_user');
    }

    public function markTaskAsDone(int $courseId, $points)
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

    public function getCleanTaskAttribute()
    {
        $attributes = $this->properties;
        if ($this->type == 'quiz') {
            $attributes['questions']['correctAnswer'] = null;
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

    public function isAllowed($userId, $courseId)
    {
        return $this->emptyParent() || $this->isCompleted($userId, $courseId)
            || $this->parentIsCompleted($userId, $courseId);
    }

    public function emptyParent()
    {
        return $this->parent_id == null;
    }

    public function isCompleted($userId, $courseId)
    {
        return $this->completedTasks($userId, $courseId);
    }

    public function completedTasks($userId, $courseId)
    {
        return DB::table('task_user')->where(
            [
                ['task_id', '=', $this->id],
                ['user_id', '=', $userId],
                ['course_id', '=', $courseId]
            ]
        )->exists();
    }

    public function parentIsCompleted($userId, $courseId)
    {
        if ($this->parent_id == null) {
            return true;
        }
        $parent = Task::find($this->parent_id);

        return $parent->isCompleted($userId, $courseId);
    }

    /**
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
     * @param int $courseId
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAllowedTasksIdsCollection(int $courseId
    ): \Illuminate\Support\Collection {
        $allowedIds = Task::all()->filter(
            function ($task) use ($courseId) {
                return $task->isAllowed(Auth::user()->id, $courseId);
            }
        )->pluck('id');
        return $allowedIds;
    }
}
