<?php


use App\Http\Controllers\Api\ScreenController;
use App\Http\Controllers\Api\ScreenV2Controller;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the prefix "admin" middleware group. Now create something great!
|
*/
//Api Routes


//Route::get('/screen/{uuid}', [ScreenController::class, 'getScreenData'])->name('screenUuid');
Route::get('/screen/{uuid}', [ScreenV2Controller::class, 'index'])->name('screenUuid');





