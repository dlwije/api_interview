<?php

use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\EmailListController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);
Route::get('v1/refresh-token', [AuthController::class, 'refresh']);

Route::group(['prefix' => 'v1', 'middleware'=> 'auth:api'], function (){
    Route::get('/get-email-list/{per_page?}', [EmailListController::class, 'index']);
});
