<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainCategoryController;

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

//Rutas email verification
Route::get('email/verify/{id}', 'App\Http\Controllers\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'App\Http\Controllers\VerificationController@resend')->name('verification.resend');


//Route::apiResource('posts', PostController::class);


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::apiResource('category', MainCategoryController::class);



//Route::apiResource('posts', PostController::class);
Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');

Route::get('posts/posts', 'App\Http\Controllers\PostController@allPosts');


Route::group(['middleware' => ['jwt.verify']], function(){

    Route::post('follow/{user}', 'App\Http\Controllers\FollowsController@store');

    Route::post('user','App\Http\Controllers\UserController@getAuthenticatedUser');
    //Route::get('posts/posts', 'App\Http\Controllers\PostController@allPosts');
    
    //Route::get('posts/posts', 'App\Http\Controllers\PostController@allPosts');
    Route::apiResource('posts', PostController::class);

    Route::get('profiles/{user}', 'App\Http\Controllers\ProfileController@index');
});