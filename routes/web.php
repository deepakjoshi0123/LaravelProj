<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberAuthController;


Route::post('/changePassword',[MemberAuthController::class,'changePassword']);
Route::get('/enterEmail',[MemberAuthController::class,'Enter_Email_view']);
Route::get('/changePassword/{token}',[MemberAuthController::class,'change_password_view']);

//____________________________________________________________________________________

Route::post('/sendRestLink',[MemberAuthController::class,'sendRestLink']);


//___________________________________________________________________________________

Route::get('/searchTask',[TaskController::class,'searchTask']);
Route::get('/filterTask',[TaskController::class,'filterTask']);

//project routes


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

//comments Routes
Route::get('/comments',[CommentController::class,'getComments']);
Route::post('/addComment',[CommentController::class,'addComment']);


//auth routes
// Route::post('/login',[MemberAuthController::class,'login']);
Route::post('/register',[MemberAuthController::class,'register']);
Route::get('/register',[MemberAuthController::class,'register_view']);
Route::get('/login', [MemberAuthController::class,'login_view'])->name('login');

Route::get('/member/verify/{token}',[MemberAuthController::class,'verifyMember']);

Route::post('/login', [MemberAuthController::class,'login']);


Route::post('/logout', [MemberAuthController::class,'logout']);


//after expiry
//middle ware jwt
//after login create session

Route::group(['middleware' => ['jwt.verify']
    ], function ($router) {
    
    Route::get('/projects',[ProjectController::class,'getProjects']);

    Route::get('/tasks',[TaskController::class,'getTasks']);

    Route::get('/dashboard',[ProjectController::class,'dashboard'])->name('dashboard');
    
    Route::post('/refresh', [MemberAuthController::class,'refresh']);
    Route::post('/me', [MemberAuthController::class,'me']);
 });