<?php

use App\Http\Middleware\NullEmail;
use App\Http\Middleware\Verifyemail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\ProjectController;
use App\Models\Task;

Route::get('/',function () {
    return redirect(route('projects.index'));
});

Route::group(['middleware'=>['auth',Verifyemail::class]],function(){

    Route::resource('projects',ProjectController::class);
    Route::post('projects/restore/{id}',[ProjectController::class,'restore'])->name('projects.restore');
    Route::delete('projects/delete/{id}',[ProjectController::class,'forcedelete'])->name('projects.force-delete');

    Route::resource('clients',ClientController::class);
    Route::post('clients/restore/{id}',[ClientController::class,'restore'])->name('clients.restore');
    Route::delete('clients/delete/{id}',[ClientController::class,'forcedelete'])->name('clients.force-delete');

    Route::resource('tasks',TaskController::class);
    Route::post('tasks/restore/{id}',[TaskController::class,'restore'])->name('tasks.restore');
    Route::delete('tasks/delete/{id}',[TaskController::class,'forcedelete'])->name('tasks.force-delete');

    Route::resource('users',UserController::class);
    Route::delete('users/delete/{id}',[UserController::class,'forcedelete'])->name('users.force-delete');
    Route::post(uri: 'users/restore/{id}', action: [UserController::class,'restore'])->name('users.restore');
});


Auth::routes();

Route::get('/home',function () {
    return redirect(route('projects.index'));
});

Route::group(['middleware'=>['auth',NullEmail::class]] ,function(){

    Route::get('verify',[VerifyController::class,'send'])->name('send');
    Route::post('verify',[VerifyController::class,'verify'])->name('verify');
    Route::get('click-on-verify/{email}',[VerifyController::class,'click'])->name('click');
});
