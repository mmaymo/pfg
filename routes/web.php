<?php

use App\Http\Controllers\CourseContent;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\TaskController;
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
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('courses', CourseController::class);
Route::resource('courses.tasks', TaskController::class)->scoped(
    [
        'task' => 'name',
    ]
);
Route::resource('courses.users', MembersController::class)->scoped(
    [
        'user' => 'id',
    ]
);

Route::get('course/{id}', CourseContent::class)->name('course');
