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
    $user = new \App\Models\User();
    $user->name = 'admin';
    $user->email = 'admin@mail.ru';
    $user->password = '12345';
    $user->save();
    return view('welcome');


});
