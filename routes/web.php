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

Route::get('product', 'Auth\PaymentContoller@index');
Route::post('razorpay-payment', 'Auth\PaymentContoller@store')->name('razorpay.payment.store');

Auth::routes();

Route::middleware(['auth'])->group(function() {
  Route::group(['prefix' => 'post', 'as' => 'post.'], function() {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('create/{id?}', 'HomeController@create')->name('create');
    Route::post('save', 'HomeController@save')->name('save');
    Route::post('delete', 'HomeController@delete')->name('delete');
  });
});
