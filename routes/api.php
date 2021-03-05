<?php

use App\Http\Controllers\Api\Admin\GroupController;
use App\Http\Controllers\Api\Admin\StaffController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\TeacherController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('password/update', [AuthController::class, 'updatePassword']);

});

Route::group(['prefix' => 'admin'], function () {
    //////////////////////////////Staff Routes///////////////////////
    Route::get('staff/all', [StaffController::class, 'getAllStaff']);
    Route::post('staff/add', [StaffController::class, 'addStaff']);
    Route::post('staff/update', [StaffController::class, 'updateStaff']);
    Route::get('staff/show', [StaffController::class, 'getStaff']);
    Route::post('staff/delete', [StaffController::class, 'deleteStaff']);

    //////////////////////////////Teacher Routes///////////////////////
    Route::get('teachers/all', [TeacherController::class, 'getAllTeachers']);
    Route::post('teacher/add', [TeacherController::class, 'addTeacher']);
    Route::post('teacher/update', [TeacherController::class, 'updateTeacher']);
    Route::get('teacher/show', [TeacherController::class, 'getTeacher']);
    Route::post('teacher/delete', [TeacherController::class, 'deleteTeacher']);

    //////////////////////////////Group Routes///////////////////////
    Route::get('groups/all', [GroupController::class, 'getAllGroups']);
    Route::post('group/add', [GroupController::class, 'addGroup']);
    Route::post('group/update', [GroupController::class, 'updateGroup']);
    Route::get('group/show', [GroupController::class, 'getGroup']);
    Route::post('group/delete', [GroupController::class, 'deleteGroup']);

    //////////////////////////////Students Routes///////////////////////
    Route::get('students/all', [StudentController::class, 'getAllStudents']);
    Route::post('student/add', [StudentController::class, 'addStudent']);
    Route::post('student/add/group', [StudentController::class, 'addStudentToGroup']);
    Route::post('student/update', [StudentController::class, 'updateStudent']);
    Route::get('student/show', [StudentController::class, 'getStudent']);
    Route::post('student/delete', [StudentController::class, 'deleteStudent']);


});

