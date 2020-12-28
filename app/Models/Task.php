<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'course_id',
        'type',
        'position',
        'points',
        'properties'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];


    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
     public function getCleanTaskAttribute(){
         $attributes = $this->properties;
        if($this->type== 'quiz'){
            $attributes['questions']['correctAnswer'] = NULL;
        }

        $cleanTask = collect(
            ['id' => $this->id,
            'name' => $this->name,
            'points' => $this->points,
            'type' => $this->type,
            'properties' => $attributes]
        );
        return $cleanTask;

     }
}
