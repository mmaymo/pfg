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
    protected $fillable
        = [
            'team_id',
            'name',
            'degree',
            'semester',
            'pic'
        ];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('points');
    }


    public function getMembersDetails(){
        $users = $this->users;
        $members = [];
        $progress = "userProgressToDo";
        foreach ($users as $user){
            $members[] = ['id'=>$user->id, 'name'=>$user->name, 'points'=>$user->pivot->points, 'profile_photo_url'=>$user->profile_photo_path, 'progress'=>$progress];
        }
        return $members;
    }
    public function chapters()
    {
        return $this->hasMany('App\Models\Chapter', 'course_id');
    }


    public function itinerary()
    {
        return $this->chapters()->with('tasks');
    }

    public function rankingTeamCoursePoints()
    {
        $team = $this->team;
        return  $team->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('points')->orderBy('points', 'desc');
    }
    public function courseProgress()
    {
        $team = $this->team;
        return $team->belongsToMany('App\Models\User', 'users_course_progress')
            ->withPivot('progress');
    }
}
