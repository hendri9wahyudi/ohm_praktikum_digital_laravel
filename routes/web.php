<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::get('/materi', [StudentController::class, 'material'])->name('student.material');
    Route::get('/soal', [StudentController::class, 'practice'])->name('student.practice');
    Route::post('/soal/verify', [StudentController::class, 'verifyAnswer'])->name('student.verify');
    Route::post('/soal/start', [StudentController::class, 'startSensor'])->name('student.start');
    Route::post('/soal/process', [StudentController::class, 'processData'])->name('student.process');
    Route::post('/soal/finish', [StudentController::class, 'finish'])->name('student.finish');

    Route::get('/guru', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/guru/praktikum', [TeacherController::class, 'practiceIndex'])->name('teacher.practice');
    Route::post('/guru/praktikum/save', [TeacherController::class, 'savePractice'])->name('teacher.practice.save');
    Route::get('/guru/manager', [TeacherController::class, 'users'])->name('teacher.users');
    Route::post('/guru/manager', [TeacherController::class, 'storeUser'])->name('teacher.users.store');
    Route::post('/guru/manager/{user}', [TeacherController::class, 'updateUser'])->name('teacher.users.update');
    Route::post('/guru/manager/{user}/delete', [TeacherController::class, 'deleteUser'])->name('teacher.users.delete');
});
