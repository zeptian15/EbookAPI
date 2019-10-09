<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Membuat Grup Api E-Book
Route::group(['prefix' => 'v1'], function () {
    // Untuk Management Buku
    Route::resource('book', 'BookController');
    // Untuk Register User baru
    Route::post('/user/register',[
        'uses' => 'AuthController@store'
    ]);
    // Untul Signin kedalam Aplikasi
    Route::post('/user/signin', [
        'uses' => 'AuthController@signin'
    ]);
    // Melihat daftar User
    Route::get('/user', [
        'uses' => 'AuthController@index'
    ]);
});