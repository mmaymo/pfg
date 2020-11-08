<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = Course::all();

        return Inertia::render('courses', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [


        ])->validate();

        Course::create($request->all());

        return redirect()->back()
            ->with('message', 'Course Created Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request              $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [

        ])->validate();

        if ($request->has('id')) {
            Course::find($request->input('id'))->update($request->all());
            return redirect()->back()
                ->with('message', 'Course Updated Successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Course::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
