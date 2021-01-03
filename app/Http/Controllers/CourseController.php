<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = $user->allTeams();

        $coursesData = [];
        foreach ($teams as $team){

            $course = Course::find($team->id);

            if($course->hasTasks()){
                $userPositionInCourse = $user->courseProgress($team->id);
                $progress = $course->progressFromPosition($userPositionInCourse);
                $points = $user->coursePoints($team->id);
                $coursesData[]=[
                    "id"=>$team->id,
                    "name"=>$team->name,
                    "owner"=>$team->owner->name,
                    "progress"=>$progress,
                    "points"=>$points,
                    "activeTaskId"=>$progress["activeTaskId"]
                ];
            }else{
                $coursesData[]=[
                    "id"=>$team->id,
                    "name"=>$team->name,
                    "owner"=>$team->owner->name,
                    "progress"=>[
                        "position" => 0,
                        "total" => 0
                    ],
                    "points"=>0,
                    "activeTaskId"=>null
                ];
            }
        }
        return Inertia::render( 'Courses/Index', [
            'courses'=>$coursesData,
        ]);

    }
    /**
     * Show the course contents management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Inertia\Response
     */
    public function show(Request $request, $courseId)
    {

        $course = Course::find($courseId);
        $students = $course->getMembersDetails();
        $itinerary = $course->getOrderedChaptersWithTasks();
        $course = [
            'courseDetails' => ['id'=>$course->id, 'name'=>$course->name, 'degree'=>$course->degree, 'semester'=>$course->semester, 'pic'=>$course->pic],
            'students'=>$students,
            'tasks'=>$itinerary,
        ];

       /* if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }*/

        return Jetstream::inertia()->render($request, 'Courses/Show', [
            'course'=> $course,
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Courses/Create');
    }

    /**
     * Create a new course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'degree' => ['nullable','string', 'max:255'],
            'semester' => ['nullable','boolean'],
            'pic' => ['nullable','string']
        ])->validateWithBag('createCourse');

        $validated['positionArray'] = [];

        $course = new Course();
        $course->name = $validated['name'];
        $course->user_id = Auth::user()->id;
        $course->positionArray = $validated['positionArray'];
        if($validated['degree']){
            $course->degree = $validated['degree'];
        }
        if($validated['semester']){
            $course->semester = $validated['semester'];
        }
        if($validated['pic']){
            $course->pic = $validated['pic'];
        }

        $course->save();

        //Course::create($validated);

        return redirect()->route('courses.show',[$course]);
    }

    /**
     * Update the given team's name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $courseId)
    {
        $course = Course::find($courseId);
        //Gate::forUser($request->user())->authorize('update', $course);

        $validated = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'degree' => ['nullable','string', 'max:255'],
            'semester' => ['nullable','boolean'],
            'pic' => ['nullable','string']
        ])->validateWithBag('updateCourseMain');

        $response = $course->update(
            [
                'name' => $validated['name'],
                'degree' => $validated['degree'],
                'semester' => $validated['semester'],
                'pic' => $validated['pic'],
            ]
        );

        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        //app(ValidateTeamDeletion::class)->validate($request->user(), $course);

        DB::transaction(function () use ($course) {
            $course->users()->detach();
            $course->delete();
        });

        return redirect(config('fortify.home'));
    }

    /**
     * updateOrderContent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderContent(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        $validated = Validator::make($request->all(), [
            'orderedContentIds' => ['array'],
        ])->validateWithBag('updateOrder');
        $wholeObject = $validated['orderedContentIds'];
        $newOrder = [];
        foreach ($wholeObject as $content){
            $newOrder[$content['id']]= [];
            foreach ($content['tasks'] as $task){
                array_push($newOrder[$content['id']], $task['id']);
            }
        }
        $course->insertPositions($newOrder);

        return back(303);
    }
}
