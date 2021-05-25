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

Route::resource('courses', CourseController::class)->middleware(['auth','permission:edit courses']);
Route::resource('courses.tasks', TaskController::class)->scoped(
    [
        'task' => 'name',
    ]
)->middleware(['auth','permission:view courses', 'permission:edit courses']);
Route::resource('courses.users', MembersController::class)->scoped(
    [
        'user' => 'id',
    ]
)->middleware(['auth','permission:edit courses']);

Route::post('course/{course}/addOrder', [CourseController::class, 'updateOrderContent'])->name('updateOrderContent')->middleware(['auth','permission:edit courses']);
Route::delete('courses/{course}/deleteAllTasks', [CourseController::class, 'deleteAllTasks'])->name('deleteAllTasks')->middleware(['auth','permission:edit courses']);
Route::delete('courses/{course}/deleteAllMembers', [CourseController::class, 'deleteAllMembers'])->name('deleteAllMembers')->middleware(['auth','permission:edit courses']);
Route::post('courses/{course}/tasks/{task}/solve', [TaskController::class, 'solveTask'])->name('solveTask')->middleware(['auth','permission:view courses', 'permission:edit courses']);

Route::post('courses/{course}/{task}/addDone', [TaskController::class, 'addDone'])->name('addDone')->middleware(['auth','permission:view courses', 'permission:edit courses']);

Route::post('courses/{course}/tasks/{task}/solveMultiple', [TaskController::class, 'solveTaskMultiple'])->name('solveTaskMultiple')->middleware(['auth','permission:view courses', 'permission:edit courses']);
Route::post('courses/{course}/tasks/{task}/codetest', [CodeTestController::class, 'testCodeTask'])->name('testCodeTask')->middleware(['auth','permission:view courses', 'permission:edit courses']);
Route::post('courses/{course}/tasks/{task}/upload', [CodeTestController::class, 'upload'])->name('uploadTest')->middleware(['auth','permission:view courses', 'permission:edit courses']);

Route::get('courses/{course}/flash', [TaskController::class, 'flashCardsShuffle'])->name('flashCardsShuffle')->middleware(['auth','permission:view courses', 'permission:edit courses']);
Route::post('users/teachers', [MembersController::class, 'addTeacher'])->name('addTeacher')->middleware(['auth', 'permission:edit courses']);
