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
// this api will be replaced by getToken 
Route::get('/getToken', [MemberAuthController::class,'getToken']);
Route::get('/refresh', [MemberAuthController::class,'refresh']);

Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {

    Route::get('/projects',[ProjectController::class,'getProjects']);
    Route::get('/tasks',[TaskController::class,'getTasks']);   
    Route::post('/refresh', [MemberAuthController::class,'refresh']);
    Route::post('/me', [MemberAuthController::class,'me']);
 });



Route::get('/member/verify/{token}',[MemberAuthController::class,'verifyMember']);
Route::post('/register',[MemberAuthController::class,'register']);
Route::post('/sendRestLink',[MemberAuthController::class,'sendRestLink']);
Route::post('/changePassword',[MemberAuthController::class,'changePassword']);
Route::get('/searchTask',[TaskController::class,'searchTask']);
Route::get('/filterTask',[TaskController::class,'filterTask']);
Route::post('/createProject',[ProjectController::class,'createProject']);
Route::post('/addMember',[ProjectController::class,'addMemberToProject']);
Route::get('/projMembers',[ProjectController::class,'getMembers']);

//task routes

Route::get('/assignees',[TaskController::class,'getAssignees']);


Route::get('/task',[TaskController::class,'taskDetails']);

Route::post('/addTask',[TaskController::class,'addTask']);

Route::post('/assignTask',[TaskController::class,'assignTask']);
Route::post('/editTask',[TaskController::class,'editTask']);
Route::delete('/delTask',[TaskController::class,'delTask']);
Route::get('/members',[TaskController::class,'members']); // end point and functions should be understable 
//___________________________________________________________________________________

//comments Routes
Route::get('/comments',[CommentController::class,'getComments']);
Route::post('/addComment',[CommentController::class,'addComment']);

