<?php

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

// Route CRUD Web Management Buku

// Ambil data semua Buku
Route::get('/book', [
    'uses' => 'BookController@getAll'
]);
// Masuk Halaman Input Data
Route::get('/book/create', [
    'uses' => 'BookController@create'
]);
// Input data Buku Baru
Route::post('/book', [
    'uses' => 'BookController@upload'
]);
// Masuk Halaman Edit Data
Route::get('/book/update/{id}', [
    'uses' => 'BookController@edit'
]);
// Edit data Buku
Route::patch('/book/{id}', [
    'uses' => 'BookController@updateBook'
]);
// Delete data Buku
Route::delete('/book/{id}', [
    'uses' => 'BookController@destroy'
]);

