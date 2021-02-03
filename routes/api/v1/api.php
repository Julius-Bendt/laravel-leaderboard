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


Route::prefix('/user')->group(function(){
    Route::post('/login','AuthController@login')->name("user.login");
    Route::post('/register','AuthController@Register')->name("user.register");
});

Route::get('/leaderboard/get/{key}/{secret}','LeaderboardController@getLeaderboard')->name("leaderboard.game.get");

Route::prefix('/score')->group(function(){
    Route::post('/new','ScoreController@createOrUpdate')->name("score.create");
    Route::post('/update','ScoreController@createOrUpdate')->name("score.update"); //Gives false security, even though it does the same as create.

    //Move to score controller
    Route::get('/amount/{key}/{secret}','ScoreController@amount')->name("leaderboard.amount");
    Route::get('/fetch/{key}/{secret}/{offset}/{limit}','ScoreController@fetch')->name("leaderboard.fetch");
});

Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::prefix('/leaderboard')->group(function(){
        Route::post('/new','LeaderboardController@create')->name("leaderboard.create");
        Route::post('/update','LeaderboardController@update')->name("leaderboard.update");

        Route::get('/get','LeaderboardController@all')->name("leaderboard.all");
        Route::get('/get/{id}','LeaderboardController@get')->name("leaderboard.get");

        Route::delete("delete/{id}",'LeaderboardController@delete')->name("leaderboard.delete");
    });

    //id is leaderboard id
    Route::prefix('/scores/{id}')->group(function(){
        Route::get('/get/{limit}/{offset}','ScoreController@fetchFromDashboard')->name("score.dashboard.fetch");
    });
});

