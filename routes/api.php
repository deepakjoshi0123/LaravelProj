<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberAuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {

    Route::get('/getUserInfo', [MemberAuthController::class,'me']);

    Route::get('/projects',[ProjectController::class,'getProjects']);
    Route::get('/projects/{id}/tasks',[TaskController::class,'getTasks']);

    Route::get('/refresh', [MemberAuthController::class,'refresh']);

 
   Route::get('/projects/{id}/tasks/{task_id}/members',[TaskController::class,'taskMembers']);
   Route::get('/projects/{id}/members',[TaskController::class,'projectMembers']);

   Route::get('/projects/{id}/tasks/{task_id}',[TaskController::class,'taskDetails']);

   Route::post('/projects/{id}/task',[TaskController::class,'addTask']);
   Route::post('/projects/{id}/task/{task_id}',[TaskController::class,'updateTask']);

   Route::delete('/projects/{id}/task/{task_id}',[TaskController::class,'delTask']);

   Route::post('/assignTask',[TaskController::class,'assignTask']);
   Route::post('/createProject',[ProjectController::class,'createProject']);

   Route::get('/shareProject',[ProjectController::class,'shareProject']);
   Route::post('/create/status', [ProjectController::class,'createStatus']);
   Route::get('/getCustomStatus', [ProjectController::class,'getCustomStatus']);

//___________________________________________________________________________________
});


Route::get('/member/verify/{token}',[MemberAuthController::class,'verifyMember']);
Route::post('/register',[MemberAuthController::class,'register']);
Route::post('/sendRestLink',[MemberAuthController::class,'sendRestLink']);
Route::post('/changePassword',[MemberAuthController::class,'changePassword']);


