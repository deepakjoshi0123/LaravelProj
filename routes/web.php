<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;


//project routes

Route::get('/projects',[ProjectController::class,'getProjects']);
Route::post('/createProject',[ProjectController::class,'createProject']);
Route::post('/addMember',[ProjectController::class,'addMemberToProject']);
Route::get('/members',[ProjectController::class,'getMembers']);

//task routes

Route::get('/tasks',[TaskController::class,'getTasks']);
Route::post('/addTask',[TaskController::class,'addTask']);
Route::post('/assignTask',[TaskController::class,'assignTask']);
Route::post('/editTask',[TaskController::class,'editTask']);
Route::delete('/delTask',[TaskController::class,'delTask']);
Route::get('/members',[TaskController::class,'members']);
// Route::get();
//comments

//add comment to the task
//list all comments
