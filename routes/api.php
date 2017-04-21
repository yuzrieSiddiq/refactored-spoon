<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to this item is only for authenticated user. Provide a token in your request!'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	$api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	$api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	$api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');

        // 1. Lecturer Unit
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('lecturerunits', 'App\\Api\\V1\\Controllers\\LecturerUnitController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');

        // // 2. Question
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 3. Quiz
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        $api->get('quizzes/unit/{unit_id}', 'App\\Api\\V1\\Controllers\\QuizController@index');
        $api->get('quizzes/{quiz_id}', 'App\\Api\\V1\\Controllers\\QuizController@show');
        $api->post('quizzes/submit/{quiz_id}', 'App\\Api\\V1\\Controllers\\QuizController@submit_answers');
        $api->get('quizzes/report/{quiz_id}', 'App\\Api\\V1\\Controllers\\QuizController@quiz_report');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 4. QuizAttempt
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 5. Student
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        $api->get('students', 'App\\Api\\V1\\Controllers\\StudentController@index');
        $api->get('student_units', 'App\\Api\\V1\\Controllers\\StudentController@students_units');
        $api->get('team_info/{unit_id}', 'App\\Api\\V1\\Controllers\\StudentController@team_info');
        $api->post('enlist/{student_id}', 'App\\Api\\V1\\Controllers\\StudentController@enlist_new_member');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 6. StudentAnswer
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 7. StudentInfo
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 8. Unit
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        $api->get('units', 'App\\Api\\V1\\Controllers\\UnitController@index');
        $api->get('units/{unit_id}', 'App\\Api\\V1\\Controllers\\UnitController@show');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
        //
        // // 9. UnitContent
        // $api->post('book/store', 'App\\Api\\V1\\Controllers\\BookController@store');
        // $api->get('books', 'App\\Api\\V1\\Controllers\\BookController@index');
    	// $api->get('books/{id}', 'App\\Api\\V1\Controllers\\BookController@show');
    	// $api->post('books', 'App\\Api\\V1\\Controllers\\BookController@store');
    	// $api->delete('books/{id}', 'App\\Api\\V1\Controllers\\BookController@destroy');
    });

});
