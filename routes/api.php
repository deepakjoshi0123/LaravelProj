<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api',
 ], function ($router) {
  
    Route::post('login', 'MemberAuthController@login');
    Route::post('logout', 'MemberAuthController@logout');
    Route::post('refresh', 'MemberAuthController@refresh');
    Route::post('me', 'MemberAuthController@me');
 });
