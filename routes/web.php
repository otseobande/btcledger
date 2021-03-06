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
  return redirect('/transaction');
});

Auth::routes();

Route::get('/transaction', 'TransactionController@index')->name('transaction');
Route::post('/transaction', 'TransactionController@index');
Route::get('/transaction/{id}/delete', 'TransactionController@delete');
Route::get('/transaction/download', 'TransactionController@download');
