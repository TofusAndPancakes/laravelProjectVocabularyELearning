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
})->middleware('guest')->name('index');

// -- Login in and Logging Out --
//Login
Route::get('/login', [UserController::class, 'login'])->middleware('guest')->name('login');
//Authentication of Login
Route::post('/users/login', [UserController::class, 'authenticate'])->middleware('guest')->name('login.authenticate');
//Logout
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');

// -- User Interface --
Route::get('/menu', [WebAppController::class, 'index'])->middleware('auth')->name('menu');

//Lesson
Route::get('/lesson', [WebAppController::class, 'lesson'])->middleware('auth')->name('lesson');
Route::post('/lesson/result', [WebAppController::class, 'resultLesson'])->middleware('auth')->name('lesson.result');

//Review
Route::get('/review', [WebAppController::class, 'review'])->middleware('auth')->name('review');
Route::post('/review/result', [WebAppController::class, 'result'])->middleware('auth')->name('review.result');

// -- Admin Priorities -- 
Route::get('/admin', [AdminController::class, 'indexLevel'])->middleware('auth')->middleware('isAdmin')->name('admin.level');
//Manage Vocabularies
Route::get('/admin/level/{level}/vocabulary', [AdminController::class, 'indexVocabulary'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary');

//Create New Levels
Route::get('/admin/level/create', [LevelController::class, 'create'])->middleware('auth')->middleware('isAdmin')->name('admin.level.create');
Route::post('/admin/level/store', [LevelController::class, 'store'])->middleware('auth')->middleware('isAdmin')->name('admin.level.store');

//Update Levels
Route::get('/admin/level/{level}/edit', [LevelController::class, 'edit'])->middleware('auth')->middleware('isAdmin')->name('admin.level.edit');
Route::post('/admin/level/{level}/update', [LevelController::class, 'update'])->middleware('auth')->middleware('isAdmin')->name('admin.level.update');

//Delete Levels
Route::delete('admin/level/{level}/delete', [LevelController::class, 'delete'])->middleware('auth')->middleware('isAdmin')->name('admin.level.delete');

//Create New Vocabulary
Route::get('/admin/level/{level}/vocabulary/create', [VocabularyController::class, 'create'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary.create');
Route::post('/admin/level/{level}/vocabulary/store', [VocabularyController::class, 'store'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary.store');

//Update Vocabulary
Route::get('/admin/level/{level}/vocabulary/{vocabulary}/edit', [VocabularyController::class, 'edit'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary.edit');
Route::post('/admin/level/{level}/vocabulary/{vocabulary}/update', [VocabularyController::class, 'update'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary.update');

//Delete Vocabulary
Route::delete('admin/level/{level}/vocabulary/{vocabulary}/delete', [VocabularyController::class, 'delete'])->middleware('auth')->middleware('isAdmin')->name('admin.vocabulary.delete');;

//Manage Users
Route::get('/admin/user', [AdminController::class, 'indexUser'])->middleware('auth')->middleware('isAdmin')->name('admin.user');

//Create New Users
Route::get('/admin/user/create', [UserController::class, 'create'])->middleware('auth')->middleware('isAdmin')->name('admin.user.create');
Route::post('/admin/user/store', [UserController::class, 'store'])->middleware('auth')->middleware('isAdmin')->name('admin.user.store');

//Update Users
Route::get('/admin/user/{user}/edit', [UserController::class, 'edit'])->middleware('auth')->middleware('isAdmin')->name('admin.user.edit');
Route::post('/admin/user/{user}/update', [UserController::class, 'update'])->middleware('auth')->middleware('isAdmin')->name('admin.user.update');

//Delete Users
Route::delete('/admin/user/{user}/delete', [UserController::class, 'delete'])->middleware('auth')->middleware('isAdmin')->name('admin.user.delete');

