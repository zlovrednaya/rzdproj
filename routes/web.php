<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
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
    $previous_vars[0]=Redis::get('train');
    $previous_vars[1]=Redis::get('departure_date');
    return view('search',compact('previous_vars'));
})->name('home');

//Route::post('/step2', 'App\Http\Controllers\HomeController@submitcheck');

Route::post('/step2', 'App\Http\Controllers\HomeController@allExecute')->name('step2get');
Route::get('/step2', function () {
    return view('step2');
})->name('step2get');

Route::post('/result','App\Http\Controllers\ResultController@continueStep')->name('result');
Route::get('/result', function () {
    return view('result');
});


