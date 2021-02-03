<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthController;

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



/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

});
*/
