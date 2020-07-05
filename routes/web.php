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
Route::resource('books', 'BookController');
Route::post('search', 'BookController@search')->name('books.search');
Route::get('checkout', 'BookController@checkout')->name('books.checkout');
Route::post('checkout', 'BookController@addLoan')->name('loan.create');
Route::get('/book/loans/', 'BookController@loans')->name('books.loans');
Route::post('/book/loans/', 'BookController@loans')->name('books.loans');
Route::get('/book/loans/check-in', 'BookController@checkIn')->name('loan.check-in');
Route::post('/book/loans/check-in', 'BookController@updateLoan')->name('loan.update');
