<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberAuthController;

Route::get('/enterEmail',[MemberAuthController::class,'Enter_Email_view']);
Route::get('/changePassword/{token}',[MemberAuthController::class,'change_password_view']);

//____________________________________________________________________________________

Route::group(['middleware' => ['web']], function () {
    Route::get('/dashboard',[ProjectController::class,'dashboard'])->name('dashboard');
});


//auth routes
Route::post('/login',[MemberAuthController::class,'login']);
Route::get('/register',[MemberAuthController::class,'register_view']);
Route::get('/login', [MemberAuthController::class,'login_view'])->name('login');

Route::get('/dashboard',[ProjectController::class,'dashboard'])->name('dashboard');

Route::get('/projects/{id}/tasks/{task_id}/attachment/{file_name}/download',[TaskController::class,'downloadTaskAttachment']);
Route::get('/projects/{id}/tasks/{task_id}/attachment/{file_name}',[TaskController::class,'viewTaskAttachment']);

//change search and filter routes too

 Route::get('/logout',[MemberAuthController::class,'logout']);
