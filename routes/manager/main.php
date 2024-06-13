<?php

use App\Http\Controllers\Manager\AttendanceController;
use App\Http\Controllers\Manager\ConfigController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\EmployeeController;
use App\Http\Controllers\Manager\GetEmployeeController;
use App\Http\Controllers\Manager\GetEmployeeHistoryController;
use App\Http\Controllers\Manager\ProfileController;
use App\Http\Controllers\Manager\ScreenController;
use App\Http\Controllers\Manager\ScreenIpController;
use App\Http\Controllers\Manager\SetAttendanceHistoryController;
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
//Backend Routes
Route::group(['middleware' => ['auth','verified','xss','user.status','user.module:manager'], 'prefix' => 'manager','as' => 'manager.'], function() {
    //Dashboard
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');
    //Profile
    Route::get('profile/{id}',[ProfileController::class,'edit'])->name('profile');
    Route::put('profile/{id}',[ProfileController::class,'update'])->name('profile.update');
    Route::get('profile-image/{id}',[ProfileController::class,'getImage'])->name('profile.get.image');

    //Config
    Route::resource('config',ConfigController::class);
    Route::get('get-config',[ConfigController::class,'getIndex'])->name('get.config');
    Route::get('get-config-activity/{id}',[ConfigController::class,'getActivity'])->name('get.config-activity');
    Route::get('get-config-activity-log/{id}',[ConfigController::class,'getActivityLog'])->name('get.config-activity-log');
    Route::get('get-config-activity-trash',[ConfigController::class,'getTrashActivity'])->name('get.config-activity-trash');
    Route::get('get-config-activity-trash-log',[ConfigController::class,'getTrashActivityLog'])->name('get.config-activity-trash-log');


    // Employee
    Route::resource('employee', EmployeeController::class)->withoutMiddleware('xss');
    Route::get('get-employee', [EmployeeController::class, 'getIndex'])->name('get.employee');
    Route::get('get-employee/{id}', [EmployeeController::class, 'getActivity'])->name('get.employee-activity');
    Route::get('get-employee-log/{id}', [EmployeeController::class, 'getActivityLog'])->name('get.employee-activity-log');
    Route::get('get-employee-trash', [EmployeeController::class, 'getTrashActivity'])->name('get.employee-activity-trash');
    Route::get('get-employee-trash-log', [EmployeeController::class, 'getTrashActivityLog'])->name('get.employee-activity-trash-log');

    Route::put('employee-erp/{id}',[EmployeeController::class,'empErp'])->name('employee-erp');
    // Attendance
    Route::resource('attendance', AttendanceController::class)->withoutMiddleware('xss');
    Route::get('get-attendance', [AttendanceController::class, 'getIndex'])->name('get.attendance');
    Route::get('get-attendance-employee-select',[AttendanceController::class,'getAttendanceEmpIndexSelect'])->name('get.attendance-employee-select');
    Route::get('get-attendance/{id}', [AttendanceController::class, 'getActivity'])->name('get.attendance-activity');
    Route::get('get-attendance-log/{id}', [AttendanceController::class, 'getActivityLog'])->name('get.attendance-activity-log');
    Route::get('get-attendance-trash', [AttendanceController::class, 'getTrashActivity'])->name('get.attendance-activity-trash');
    Route::get('get-attendance-trash-log', [AttendanceController::class, 'getTrashActivityLog'])->name('get.attendance-activity-trash-log');

    //Attendance Delete All
    Route::delete('attendance-delete-all', [AttendanceController::class, 'deleteAll'])->name('attendance-delete-all');


    // Get Employee
    Route::resource('get-employees', GetEmployeeController::class)->withoutMiddleware('xss');
    Route::get('get-get-employees', [GetEmployeeController::class, 'getIndex'])->name('get.get-employees');
    Route::get('get-get-employees-area-select',[GetEmployeeController::class,'getAttendanceAreaIndexSelect'])->name('get.get-employees-area-select');
    Route::get('get-get-employees-terminal-select',[GetEmployeeController::class,'getAttendanceTerminalIndexSelect'])->name('get.get-employees-terminal-select');
    Route::get('get-employees-select',[GetEmployeeController::class,'getEmployeeIndexSelect'])->name('get.employees-select');
    Route::get('get-get-employees/{id}', [GetEmployeeController::class, 'getActivity'])->name('get.get-employees-activity');
    Route::get('get-get-employees-log/{id}', [GetEmployeeController::class, 'getActivityLog'])->name('get.get-employees-activity-log');
    Route::get('get-get-employees-trash', [GetEmployeeController::class, 'getTrashActivity'])->name('get.get-employees-activity-trash');
    Route::get('get-get-employees-trash-log', [GetEmployeeController::class, 'getTrashActivityLog'])->name('get.get-employees-activity-trash-log');


    // Get Employee History
    Route::resource('get-employee-histories', GetEmployeeHistoryController::class)->withoutMiddleware('xss');
    Route::get('get-get-employee-histories', [GetEmployeeHistoryController::class, 'getIndex'])->name('get.get-employee-histories');
    Route::get('get-get-employee-histories/{id}', [GetEmployeeHistoryController::class, 'getActivity'])->name('get.get-employee-histories-activity');
    Route::get('get-get-employee-histories-log/{id}', [GetEmployeeHistoryController::class, 'getActivityLog'])->name('get.get-employee-histories-activity-log');
    Route::get('get-get-employee-histories-trash', [GetEmployeeHistoryController::class, 'getTrashActivity'])->name('get.get-employee-histories-activity-trash');
    Route::get('get-get-employee-histories-trash-log', [GetEmployeeHistoryController::class, 'getTrashActivityLog'])->name('get.get-employee-histories-activity-trash-log');

    // Get Employee History Delete All
    Route::delete('get-employee-histories-delete-all', [GetEmployeeHistoryController::class, 'deleteAll'])->name('get-employee-histories-delete-all');

    Route::get('get-employee-histories/filter', [GetEmployeeHistoryController::class, 'filterData'])->name('get-employee-histories.filter');
    // Set Attendance History
    Route::resource('set-attendance-histories', SetAttendanceHistoryController::class)->withoutMiddleware('xss');
    Route::get('get-set-attendance-histories', [SetAttendanceHistoryController::class, 'getIndex'])->name('get.set-attendance-histories');
    Route::get('get-set-attendance-histories/{id}', [SetAttendanceHistoryController::class, 'getActivity'])->name('get.set-attendance-histories-activity');
    Route::get('get-set-attendance-histories-log/{id}', [SetAttendanceHistoryController::class, 'getActivityLog'])->name('get.set-attendance-histories-activity-log');
    Route::get('get-set-attendance-histories-trash', [SetAttendanceHistoryController::class, 'getTrashActivity'])->name('get.set-attendance-histories-activity-trash');
    Route::get('get-set-attendance-histories-trash-log', [SetAttendanceHistoryController::class, 'getTrashActivityLog'])->name('get.set-attendance-histories-activity-trash-log');


    // Set Attendance History Delete All
    Route::delete('set-attendance-histories-delete-all', [SetAttendanceHistoryController::class, 'deleteAll'])->name('set-attendance-histories-delete-all');

    // Screen
    Route::resource('screen',ScreenController::class);
    Route::get('get-screen',[ScreenController::class,'getIndex'])->name('get.screen');
    Route::get('get-screen-activity/{id}',[ScreenController::class,'getActivity'])->name('get.screen-activity');
    Route::get('get-screen-activity-log/{id}',[ScreenController::class,'getActivityLog'])->name('get.screen-activity-log');
    Route::get('get-screen-activity-trash',[ScreenController::class,'getTrashActivity'])->name('get.screen-activity-trash');
    Route::get('get-screen-activity-trash-log',[ScreenController::class,'getTrashActivityLog'])->name('get.screen-activity-trash-log');

    Route::put('screen-uuid/{id}',[ScreenController::class,'uuidUpdate'])->name('screen-uuid');

    // Screen IP
    Route::resource('screen/{sid}/screen-ip',ScreenIpController::class, ['as' => 'screen']);
    Route::get('screen/{sid}/get-screen-ip',[ScreenIpController::class,'getIndex'])->name('get.screen-ip');
    Route::get('get-screen-select',[ScreenIpController::class,'getScreenIndexSelect'])->name('get.screen-select');
    Route::get('screen/{sid}/get-screen-ip-activity/{id}',[ScreenIpController::class,'getActivity'])->name('get.screen-ip-activity');
    Route::get('get-screen-ip-activity-log/{id}',[ScreenIpController::class,'getActivityLog'])->name('get.screen-ip-activity-log');
    Route::get('screen/{sid}/get-screen-ip-activity-trash',[ScreenIpController::class,'getTrashActivity'])->name('get.screen-ip-activity-trash');
    Route::get('get-screen-ip-activity-trash-log',[ScreenIpController::class,'getTrashActivityLog'])->name('get.screen-ip-activity-trash-log');

});

Route::get('attendance-test', [AttendanceController::class, 'test'])->name('manager.attendance.test');
Route::get('attendance-erp-test', [AttendanceController::class, 'testErp'])->name('manager.attendance.erp.test');
Route::get('employee-test', [EmployeeController::class, 'test'])->name('manager.employee.test');


