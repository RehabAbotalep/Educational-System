<?php

use App\Http\Controllers\Api\Admin\ComplaintController;
use App\Http\Controllers\Api\Admin\GroupController;
use App\Http\Controllers\Api\Admin\SessionController;
use App\Http\Controllers\Api\Admin\StaffController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\TeacherController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EndUser\EndUserController;
use App\Http\Controllers\Api\EndUser\ExamController;
use App\Http\Controllers\Api\EndUser\QuestionController;
use App\Http\Controllers\Api\EndUser\StudentExamController;
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

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.token', 'roles:Admin']], function () {
    //////////////////////////////Staff Routes///////////////////////
    Route::get('staff/all', [StaffController::class, 'getAllStaff']);
    Route::post('staff/add', [StaffController::class, 'addStaff']);
    Route::post('staff/update', [StaffController::class, 'updateStaff']);
    Route::get('staff/show', [StaffController::class, 'getStaff']);
    Route::post('staff/delete', [StaffController::class, 'deleteStaff']);

    //////////////////////////////Session Routes///////////////////////
    Route::get('sessions/all', [SessionController::class, 'allSessions']);
    Route::post('session/add', [SessionController::class, 'addSession']);
    Route::post('session/delete', [SessionController::class, 'deleteSession']);

    //////////////////////////////Complaints Routes///////////////////////
    Route::get('complaints/all', [ComplaintController::class, 'allComplaints']);
    Route::get('complaint/show', [ComplaintController::class, 'getComplaint']);
    Route::post('complaint/delete', [ComplaintController::class, 'deleteComplaint']);

    //////////////////////////////subscriptions Routes///////////////////////
    Route::get('limit/subscriptions', [SubscriptionController::class, 'limitSubscriptions']);
    Route::get('closed/subscriptions', [SubscriptionController::class, 'closedSubscriptions']);

});
Route::group(['prefix' => 'dashboard', 'middleware' => ['jwt.token', 'roles:Admin.Support.Secretary']], function () {

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

    /////////////////////////////Students Routes///////////////////////
    Route::get('students/all', [StudentController::class, 'getAllStudents']);
    Route::post('student/add', [StudentController::class, 'addStudent']);
    Route::post('student/add/group', [StudentController::class, 'addStudentToGroup']);
    Route::post('student/update', [StudentController::class, 'updateStudent']);
    Route::get('student/show', [StudentController::class, 'getStudent']);
    Route::post('student/delete', [StudentController::class, 'deleteStudent']);
});

Route::group(['prefix' => 'student', 'middleware' => ['jwt.token', 'roles:Student']], function () {
    Route::post('attendance/take', [\App\Http\Controllers\Api\EndUser\StudentController::class, 'saveAttendance']);

    /////////////////////////////Exams Routes///////////////////////
    Route::get('exams/new', [StudentExamController::class, 'newExams']);
    Route::get('exams/old', [StudentExamController::class, 'oldExams']);
    Route::get('exams/student/new', [StudentExamController::class, 'newStudentExam']);
    Route::post('exams/student/store', [StudentExamController::class, 'storeStudentExam']);


});

Route::group(['prefix' => 'endUser', 'middleware' => ['jwt.token', 'roles:Student.Teacher']], function () {

    Route::post('complaints/send', [EndUserController::class, 'sendComplaint']);
    Route::get('schedule', [EndUserController::class, 'schedule']);
    Route::get('group/timelines', [EndUserController::class, 'groupTimeline']);
    Route::post('discussion/add', [EndUserController::class, 'addDiscussion']);
    Route::get('discussion/all', [EndUserController::class, 'allDiscussion']);
    Route::post('discussion/comment', [EndUserController::class, 'discussionComment']);

});

Route::group(['prefix' => 'teacher', 'middleware' => ['jwt.token', 'roles:Teacher']], function () {
    Route::get('exams/types', [ExamController::class, 'examTypes']);
    Route::get('exams/all', [ExamController::class, 'allExams']);
    Route::post('exam/add', [ExamController::class, 'addExam']);
    Route::post('exam/update', [ExamController::class, 'updateExam']);
    Route::post('exam/delete', [ExamController::class, 'deleteExam']);
    Route::post('exam/status/update', [ExamController::class, 'updateExamStatus']);
    Route::get('exam/students', [ExamController::class, 'examStudents']);
    Route::get('exam/students/details', [ExamController::class, 'examStudentDetails']);

    Route::post('student/answers/mark', [ExamController::class, 'markStudentEssayExamAnswers']);

    /////////////////////////////Questions Routes///////////////////////
    Route::post('questions/all', [QuestionController::class, 'allQuestions']);
    Route::post('question/add', [QuestionController::class, 'addQuestion']);
    Route::post('question/update', [QuestionController::class, 'updateQuestion']);
    Route::post('question/delete', [QuestionController::class, 'deleteQuestion']);


});





