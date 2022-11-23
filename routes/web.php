<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\WebAppController;

//Default Homepage
Route::get('/', function () {
    return view('index');
});

// -- Login in and Logging Out --
//Login
Route::get('/login', [UserController::class, 'login'])->middleware('guest')->name('login');
//Authentication of Login
Route::post('/users/login', [UserController::class, 'authenticate'])->middleware('guest');
//Logout
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// -- User Interface --
Route::get('/menu', [WebAppController::class, 'index'])->middleware('auth');

//Lesson
Route::get('/lesson', [WebAppController::class, 'lesson'])->middleware('auth');
Route::post('/lesson/result', [WebAppController::class, 'result'])->middleware('auth');

//Review
Route::get('/review', [WebAppController::class, 'review'])->middleware('auth');
Route::get('/review/result', [WebAppController::class, 'result'])->middleware('auth');

// -- Admin Priorities -- 
Route::get('/admin', [AdminController::class, 'indexLevel'])->middleware('auth');
//Manage Vocabularies
Route::get('/admin/level/{level}/vocabulary', [AdminController::class, 'indexVocabulary'])->middleware('auth');

//Create New Levels
Route::get('/admin/level/create', [LevelController::class, 'create'])->middleware('auth');
Route::post('/admin/level/store', [LevelController::class, 'store'])->middleware('auth');

//Update Levels
Route::get('/admin/level/{level}/edit', [LevelController::class, 'edit'])->middleware('auth');
Route::post('/admin/level/{level}/update', [LevelController::class, 'update'])->middleware('auth');

//Delete Levels
Route::delete('admin/level/{level}/delete', [LevelController::class, 'delete'])->middleware('auth');

//Create New Vocabulary
Route::get('/admin/level/{level}/vocabulary/create', [VocabularyController::class, 'create'])->middleware('auth');
Route::post('/admin/level/{level}/vocabulary/store', [VocabularyController::class, 'store'])->middleware('auth');

//Update Vocabulary
Route::get('/admin/level/{level}/vocabulary/{vocabulary}/edit', [VocabularyController::class, 'edit'])->middleware('auth');
Route::post('/admin/level/{level}/vocabulary/{vocabulary}/update', [VocabularyController::class, 'update'])->middleware('auth');

//Delete Vocabulary
Route::delete('admin/level/{level}/vocabulary/{vocabulary}/delete', [VocabularyController::class, 'delete'])->middleware('auth');
