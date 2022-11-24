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
    // Route::post('/login',[MemberAuthController::class,'login']);
    Route::get('/dashboard',[ProjectController::class,'dashboard'])->name('dashboard');
});


//auth routes
Route::post('/login',[MemberAuthController::class,'login']);
Route::get('/register',[MemberAuthController::class,'register_view']);
Route::get('/login', [MemberAuthController::class,'login_view'])->name('login');

Route::get('/dashboard',[ProjectController::class,'dashboard'])->name('dashboard');
Route::get('/downloadTaskAttachment/{file_ame}',[TaskController::class,'downloadTaskAttachment']);

Route::get('/viewTaskAttachment/{file_ame}',[TaskController::class,'viewTaskAttachment']);

Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {

    Route::post('/me', [MemberAuthController::class,'me']);
 });


 Route::get('/logout',[MemberAuthController::class,'logout']);
