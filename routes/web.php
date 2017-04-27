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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
   // return JWTAuth::parseToken()->authenticate();
   try {

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

        return response()->json(['token_expired'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        return response()->json(['token_invalid'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

        return response()->json(['token_absent'], $e->getStatusCode());

    }

    // the token is valid and we have found the user via the sub claim
    return response()->json(compact('user'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/csv/lecturers', 'LecturerUnitController@uploadLecturers')->name('csv.lecturers');
Route::post('/csv/students',  'StudentController@uploadStudents')->name('csv.students');
Route::post('/csv/questions/{quiz_id}', 'QuestionController@uploadQuestions')->name('csv.questions');
Route::resource('users', 'UserController');
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
/** update **/  Route::put('quizzes/{quiz}/questions/{question}', 'QuestionController@update')
                ->name('quizzes.questions.update');
/** delete **/  Route::delete('quizzes/{quiz}/questions/{question}', 'QuestionController@destroy')
                ->name('quizzes.questions.destroy');

Route::resource('units', 'UnitController');
// UNTIS
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
/** delete **/  Route::delete('units/{unit}/students/{student}', 'StudentController@destroy')
                ->name('units.students.destroy');

Route::resource('l_units', 'LecturerUnitController');

Route::get('usersdatatable', 'DatatablesController@getUsersDatatable')->name('get.users.datatable');
Route::get('unitsdatatable', 'DatatablesController@getUnitsDatatable')->name('get.units.datatable');
Route::get('l_unitsdatatable', 'DatatablesController@getLUnitsDatatable')->name('get.l_units.datatable');
Route::get('studentsdatatable', 'DatatablesController@getStudentsDatatable')->name('get.students.datatable');
Route::get('quizzesdatatable', 'DatatablesController@getQuizzesDatatable')->name('get.quizzes.datatable');
