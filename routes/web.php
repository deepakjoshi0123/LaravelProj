<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberAuthController;
//project routes

Route::get('/projects',[ProjectController::class,'getProjects']);
Route::post('/createProject',[ProjectController::class,'createProject']);
Route::post('/addMember',[ProjectController::class,'addMemberToProject']);
Route::get('/projMembers',[ProjectController::class,'getMembers']);

//task routes

Route::get('/tasks',[TaskController::class,'getTasks']);
Route::get('/task',[TaskController::class,'taskDetails']);
Route::post('/addTask',[TaskController::class,'addTask']);
Route::post('/assignTask',[TaskController::class,'assignTask']);
Route::post('/editTask',[TaskController::class,'editTask']);
Route::delete('/delTask',[TaskController::class,'delTask']);
Route::get('/members',[TaskController::class,'members']);

//comments Routes
Route::get('/comments',[CommentController::class,'getComments']);
Route::post('/addComment',[CommentController::class,'addComment']);

//auth routes
// Route::post('/login',[MemberAuthController::class,'login']);
// Route::post('/register',[MemberAuthController::class,'register']);

Route::get('/dashboard',[ProjectController::class,'dashboard']);

Route::group([
    'middleware' => 'api',
 ], function ($router) {
    
    Route::post('/login', [MemberAuthController::class,'login']);
    Route::post('/logout', [MemberAuthController::class,'logout']);
    Route::post('/refresh', [MemberAuthController::class,'refresh']);
    Route::post('/me', [MemberAuthController::class,'me']);
 });