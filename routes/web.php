<?php

use App\Http\Controllers\DeletedTaskController;
use App\Http\Controllers\TaskController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('tasks', TaskController::class);
Route::get('/bin', [DeletedTaskController::class, 'show'])->name('bin');
Route::get('/deleted-tasks', [DeletedTaskController::class, 'index']);
Route::put('/deleted-tasks/restore/{id}', [DeletedTaskController::class, 'restore']);
Route::put('/deleted-tasks/delete/{id}', [DeletedTaskController::class, 'delete']);
