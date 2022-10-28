<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberAuthController;
//project routes


Route::post('/createProject',[ProjectController::class,'createProject']);
Route::post('/addMember',[ProjectController::class,'addMemberToProject']);
Route::get('/projMembers',[ProjectController::class,'getMembers']);

//task routes

Route::get('/assignees',[TaskController::class,'getAssignees']);

Route::get('/tasks',[TaskController::class,'getTasks']);
Route::get('/task',[TaskController::class,'taskDetails']);

Route::post('/addTask',[TaskController::class,'addTask']);

Route::post('/assignTask',[TaskController::class,'assignTask']);
Route::post('/editTask',[TaskController::class,'editTask']);
Route::delete('/delTask',[TaskController::class,'delTask']);
Route::get('/members',[TaskController::class,'members']); // end point and functions should be understable 

//comments Routes
Route::get('/comments',[CommentController::class,'getComments']);
Route::post('/addComment',[CommentController::class,'addComment']);

Route::get('/dashboard',[ProjectController::class,'dashboard']);
//auth routes
// Route::post('/login',[MemberAuthController::class,'login']);
Route::post('/register',[MemberAuthController::class,'register']);
Route::get('/register',[MemberAuthController::class,'register_view']);
Route::get('/login', [MemberAuthController::class,'login_view']);

Route::get('/member/verify/{token}',[MemberAuthController::class,'verifyMember']);

Route::post('/login', [MemberAuthController::class,'login']);


Route::get('/projects',[ProjectController::class,'getProjects']);


Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {

    Route::post('/logout', [MemberAuthController::class,'logout']); // out 
    Route::post('/refresh', [MemberAuthController::class,'refresh']);
    Route::post('/me', [MemberAuthController::class,'me']);
 });