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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// LINE用ルート
Route::post('line', 'LineController@line');
Route::get('line', 'LineController@get');
Route::get('results', 'ResultController@all_results');

// 管理者画面
Route::get('management', 'ManagementController@showAnswersStatus');