<?php


use App\Http\Controllers\Front\ScreenController;
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
//FrontEnd Routes


    Route::get('screen/{uuid}', [ScreenController::class, 'index'])->name('screen.uuid');
    Route::get('/user/profile/{id}', [ScreenController::class, 'getUserImage'])->name('profile.get.user.image');





