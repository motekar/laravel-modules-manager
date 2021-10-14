<?php

use App\Http\Controllers\ModuleController;
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

Route::get('/modules', [ModuleController::class, 'modules'])->name('modules');
Route::post('/modules', [ModuleController::class, 'updateModule'])->name('modules.update');
