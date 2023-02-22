<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\V1\AlbumController;
use \App\Http\Controllers\V1\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// apiResource gives you all request methods

Route::prifix("v1")->group(function(){
    Route::apiResource('album', AlbumController::class);
    Ruote::get("image",[ImageController::class,"index"]);
    Ruote::get("image/{image}",[ImageController::class,"show"]);
    Ruote::post("image/resize",[ImageController::class,"resize"]);
    Ruote::get("image/by-album/{album}",[ImageController::class,"byA;bum"]);
    Ruote::delete("image/{image}",[ImageController::class,'destroy']);
});