<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',function () {
    return redirect(route('projects.index'));
});

Route::group(['middleware'=>'auth'],function(){

    Route::resource('projects',ProjectController::class);
    Route::post('projects/restore/{id}',[ProjectController::class,'restore'])->name('projects.restore');
    Route::delete('projects/delete/{id}',[ProjectController::class,'forcedelete'])->name('projects.force-delete');

    Route::resource('clients',ClientController::class);

    Route::resource('tasks',TaskController::class);

    Route::resource('users',UserController::class);
    Route::delete('users/delete/{id}',[UserController::class,'forcedelete'])->name('users.force-delete');

});


Auth::routes();

Route::get('/home',function () {
    return redirect(route('projects.index'));
});
