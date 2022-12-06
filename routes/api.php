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

Route::get('/getToken', [MemberAuthController::class,'getToken']);
Route::post('/create/status', [ProjectController::class,'createStatus']);
Route::get('/getCustomStatus', [ProjectController::class,'getCustomStatus']);
Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {
        Route::get('/refresh', [MemberAuthController::class,'refresh']);
    Route::get('/projects',[ProjectController::class,'getProjects']);
    Route::get('/tasks',[TaskController::class,'getTasks']);   
    Route::post('/refresh', [MemberAuthController::class,'refresh']);
    Route::post('/me', [MemberAuthController::class,'me']);
    Route::get('/searchTask',[TaskController::class,'searchTask']);
Route::get('/filterTask',[TaskController::class,'filterTask']);
Route::post('/createProject',[ProjectController::class,'createProject']);

Route::get('/projMembers',[ProjectController::class,'getMembers']);

Route::get('/shareProject',[ProjectController::class,'shareProject']);

Route::get('/add/assignees',[TaskController::class,'getAddAssignees']);//for add modal 

Route::get('/edit/assignees',[TaskController::class,'getEditAssignees']);//for edit modal

Route::get('/taskDetails',[TaskController::class,'taskDetails']);

Route::post('/addTask',[TaskController::class,'addTask']);
Route::post('/updateTask',[TaskController::class,'updateTask']);

Route::post('/assignTask',[TaskController::class,'assignTask']);
// Route::post('/editTask',[TaskController::class,'editTask']);
Route::delete('/delTask',[TaskController::class,'delTask']);
Route::get('/members',[TaskController::class,'members']); // end point and functions should be understable 
//___________________________________________________________________________________
});


Route::get('/member/verify/{token}',[MemberAuthController::class,'verifyMember']);
Route::post('/register',[MemberAuthController::class,'register']);
Route::post('/sendRestLink',[MemberAuthController::class,'sendRestLink']);
Route::post('/changePassword',[MemberAuthController::class,'changePassword']);

//task routes

//comments Routes
Route::get('/comments',[CommentController::class,'getComments']);
Route::post('/addComment',[CommentController::class,'addComment']);

