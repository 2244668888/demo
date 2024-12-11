<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("call_for_assistance",[APIController::class,'call_for_assistance']);
Route::post("machine_up_time",[APIController::class,'machine_up_time']);
Route::post("production",[APIController::class,'production']);
Route::post("machine_preperation",[APIController::class,'machine_preperation']);
Route::post("machine_count",[APIController::class,'machine_count']);

