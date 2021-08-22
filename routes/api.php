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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register-device', 'App\Http\Controllers\DeviceController@register')->name('register');
Route::post('/purchase', 'App\Http\Controllers\DeviceController@checkMock')->name('purchase');

//Mock API routes
Route::post('/google-mock-api', 'App\Http\Controllers\MockApiController@google')->name('google-mock');
Route::post('/apple-mock-api', 'App\Http\Controllers\MockApiController@apple')->name('apple-mock');
