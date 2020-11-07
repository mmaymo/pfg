<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'team_id',
        'degree',
        'semeste',
        'pic'
    ];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function tasks()
    {
        return $this->hasOne('App\Models\Task', 'course_id');
    }
}
