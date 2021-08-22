<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register-device', 'App\Http\Controllers\DeviceController@register')->name('register');

//Mock API routes
Route::post('/google-mock-api', 'App\Http\Controllers\MockApiController@google')->name('google-mock');
Route::post('/apple-mock-api', 'App\Http\Controllers\MockApiController@apple')->name('google-mock');
