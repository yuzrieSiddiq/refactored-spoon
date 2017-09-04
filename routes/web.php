<?php

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

Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::post('/csv/lecturers', 'LecturerUnitController@uploadLecturers')->name('csv.lecturers');
    Route::post('/csv/students',  'StudentController@uploadStudents')->name('csv.students');
    Route::post('/csv/questions/{quiz_id}', 'QuestionController@uploadQuestions')->name('csv.questions');

    Route::put('settings', 'SettingsController@update')->name('settings.update');

    Route::get('users/{user}/create/studentinfo', 'StudentInfoController@create')
        ->name('users.create.studentinfo');
    Route::get('users/{user}/edit/studentinfo', 'StudentInfoController@edit')
        ->name('users.edit.studentinfo');
    Route::post('users/{user}/store/studentinfo', 'StudentInfoController@store')
        ->name('users.store.studentinfo');
    Route::put('users/{user}/update/studentinfo', 'StudentInfoController@update')
        ->name('users.update.studentinfo');

    Route::get('users/{user}/edit/password', 'UserController@edit_password')
        ->name('users.edit.password');
    Route::put('users/{user}/update/password', 'UserController@update_password')
        ->name('users.update.password');

    Route::resource('users', 'UserController');

    Route::get('quizzes/upload', 'QuizController@create_upload')->name('quizzes.create.upload');
    Route::post('quizzes/upload', 'QuizController@store_upload')->name('quizzes.store.upload');
    Route::resource('quizzes', 'QuizController');

    // QUIZ QUESTION
    /** index  **/  Route::get('quizzes/{quiz}/questions', 'QuestionController@index')
                    ->name('quizzes.questions.index');
    /** post   **/  Route::post('quizzes/{quiz}/questions', 'QuestionController@store')
                    ->name('quizzes.questions.store');
    /** create **/  Route::get('quizzes/{quiz}/questions/create', 'QuestionController@create')
                    ->name('quizzes.questions.create');
    /** show   **/  Route::get('quizzes/{quiz}/questions/{question}', 'QuestionController@show')
                    ->name('quizzes.questions.show');
    /** edit   **/  Route::get('quizzes/{quiz}/questions/{question}/edit', 'QuestionController@edit')
                    ->name('quizzes.questions.edit');
    /** edit   **/  Route::get('quizzes/{quiz}/group_no/{group}/edit', 'QuizController@edit_tutorial_group')
                    ->name('quizzes.questions.edit.group');
    /** update **/  Route::put('quizzes/{quiz}/questions/answer_types', 'QuestionController@update_answer_type')
                    ->name('quizzes.questions.update.answer_types');
    /** update **/  Route::put('quizzes/{quiz}/questions/{question}', 'QuestionController@update')
                    ->name('quizzes.questions.update');
    /** update **/  Route::put('quizzes/{quiz}/group_no/{group}/update', 'QuizController@update_tutorial_group')
                    ->name('quizzes.questions.update.group');
    /** update **/  Route::put('quizzes/{quiz}/group_no/{group}/choose_questions', 'QuizController@choose_questions')
                    ->name('quizzes.questions.choose');
    /** delete **/  Route::delete('quizzes/{quiz}/questions/{question}', 'QuestionController@destroy')
                    ->name('quizzes.questions.destroy');
    /** delete **/  Route::delete('quizzes/{quiz}/deleteAll', 'QuestionController@destroy_all')
                    ->name('quizzes.questions.destroy_all');
    /** report **/  Route::get('quizzes/{quiz}/report', 'ReportingController@quiz_report')
                    ->name('quizzes.report');

    Route::resource('units', 'UnitController');
    // UNITS
                    Route::get('units/{unit}', 'UnitController@show')
                    ->name('units.show')->middleware('assigned');
    /** index  **/  Route::get('units/lecturer/index', 'UnitController@index_lecturer')
                    ->name('units.lecturer');
    /** index  **/  Route::get('units/{unit}/students', 'StudentController@index')
                    ->name('units.students.index');
    /** index  **/  Route::get('quizzes/{unit}/index', 'QuizController@index_unit')
                    ->name('units.quizzes.index');
    /** create **/  Route::get('units/{unit}/quizzes/create', 'QuizController@create_unit')
                    ->name('units.quizzes.create');
    /** post   **/  Route::post('units/{unit}/students', 'StudentController@store')
                    ->name('units.students.store');
    /** create **/  Route::get('units/{unit}/students/create', 'StudentController@create')
                    ->name('units.students.create');
    /** show   **/  Route::get('units/{unit}/students/{student}', 'StudentController@show')
                    ->name('units.students.show');
    /** edit   **/  Route::get('units/{unit}/students/{student}/edit', 'StudentController@edit')
                    ->name('units.students.edit');
    /** update **/  Route::put('units/{unit}/students/{student}', 'StudentController@update')
                    ->name('units.students.update');
    /** update **/  Route::put('units/{unit}/students/{student}/group', 'StudentController@update_group_no')
                    ->name('units.students.update_group_no');
    /** delete **/  Route::delete('units/{unit}/students/{student}', 'StudentController@destroy')
                    ->name('units.students.destroy');
    /** report **/  Route::get('units/{unit}/report', 'ReportingController@unit_report')
                    ->name('units.report');
    /** report **/  Route::post('student/{student_id}/quiz/{quiz}/report', 'ReportingController@student_report')
                    ->name('units.students.report');

    Route::resource('l_units', 'LecturerUnitController');

    Route::get('usersdatatable', 'DatatablesController@getUsersDatatable')->name('get.users.datatable');
    Route::get('unitsdatatable', 'DatatablesController@getUnitsDatatable')->name('get.units.datatable');
    Route::get('l_unitsdatatable', 'DatatablesController@getLUnitsDatatable')->name('get.l_units.datatable');
    Route::get('studentsdatatable/{unit_id}', 'DatatablesController@getStudentsDatatable')->name('get.students.datatable');
    Route::get('quizzesdatatable', 'DatatablesController@getQuizzesDatatable')->name('get.quizzes.datatable');
    Route::get('questionsdatatable/{quiz_id}', 'DatatablesController@getQuestionsDatatable')->name('get.questions.datatable');
    Route::get('groupquestionsdatatable/{quiz_id}/group/{group_no}', 'DatatablesController@getGroupQuestionsDatatable')
        ->name('get.questions.group.datatable');

    Route::get('results/{quiz}', 'ResultsController@overall_results')->name('results.quiz');
    Route::get('results/{quiz}/group/{group}', 'ResultsController@group_results')->name('results.quiz.group');
    Route::post('results/{quiz}/student/{student}', 'ResultsController@group_results')->name('results.get.answers');
});
