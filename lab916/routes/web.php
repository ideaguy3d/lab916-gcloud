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

Route::get('/', function () {
    $name = "Julius Vitalius";
    $age = 29;
    $dailyTasks = [
        'Build an api back end',
        'Implement validation checks',
        'Write unit tests'
    ];

    $tasks = DB::table('tasks')->get();

    return $tasks; // return json

    /*
return view('welcome', [
        'name' => $name,
        'age' => $age,
        //'tasks' => $tasks
    ]);
    */

});

Route::get('/tasks/{id}', function ($id) {
//    dd($id);
    $task = DB::table('tasks')->find($id);

    dd($task);
});
