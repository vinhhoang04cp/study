<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them should
| receive the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');

// Course routes
Route::resource('courses', CourseController::class);
Route::post('/courses/{course}/restore', [CourseController::class, 'restore'])->name('courses.restore');
Route::delete('/courses/{id}/force-delete', [CourseController::class, 'forceDelete'])->name('courses.force-delete');

// Lesson routes (nested under courses)
Route::resource('courses.lessons', LessonController::class);

// Enrollment routes
Route::resource('enrollments', EnrollmentController::class);
Route::get('/courses/{course}/enrollments', [EnrollmentController::class, 'byCourse'])->name('enrollments.by-course');

