<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\TaskStatusesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LabelController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/email/verify', function () {
    return view('auth.verify');
})->name('verification.notice')->middleware('auth');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->name('verification.verify')->middleware(['auth', 'signed']);

Route::resource('task_statuses', TaskStatusesController::class)->except(['show']);
Route::resource('tasks', TaskController::class);
Route::resource('labels', LabelController::class)->except(['show']);
