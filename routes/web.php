<?php

use App\Http\Controllers\CodeTestController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();
    $isTeacher = $user->hasRole('profesor');
    $ownedCourses = $user->courses;
    $isEnrolled = $user->hasRole('alumno');
    $enrolledCourses = $user->coursesEnrolled;
    return Inertia\Inertia::render('Dashboard',['isTeacher'=>$isTeacher, 'isEnrolled'=>$isEnrolled, 'ownedCourses'=>$ownedCourses, 'enrolledCourses'=>$enrolledCourses]);
})->name('dashboard');

Route::resource('courses', CourseController::class)->middleware('permission:edit courses');
Route::resource('courses.tasks', TaskController::class)->scoped(
    [
        'task' => 'name',
    ]
);
Route::resource('courses.users', MembersController::class)->scoped(
    [
        'user' => 'id',
    ]
)->middleware('permission:edit courses');

Route::post('course/{course}/addOrder', [CourseController::class, 'updateOrderContent'])->name('updateOrderContent')->middleware('permission:edit courses');
Route::delete('courses/{course}/deleteAllTasks', [CourseController::class, 'deleteAllTasks'])->name('deleteAllTasks')->middleware('permission:edit courses');
Route::delete('courses/{course}/deleteAllMembers', [CourseController::class, 'deleteAllMembers'])->name('deleteAllMembers')->middleware('permission:edit courses');
Route::post('courses/{course}/tasks/{task}/solve', [TaskController::class, 'solveTask'])->name('solveTask');
Route::post('courses/{course}/tasks/{task}/solveMultiple', [TaskController::class, 'solveTaskMultiple'])->name('solveTaskMultiple');

Route::post('courses/{course}/tasks/{task}/codetest', [CodeTestController::class, 'testCodeTask'])->name('testCodeTask');
