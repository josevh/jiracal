<?php
Route::get(config('jiracal.path'),
    'Josevh\JiraCal\JiraCalController@index');

Route::get(config('jiracal.path').'/login',
    'Josevh\JiraCal\JiraCalController@login');

Route::get(config('jiracal.path').'/logout',
    'Josevh\JiraCal\JiraCalController@logout');

Route::post(config('jiracal.path').'/auth',
    'Josevh\JiraCal\JiraCalController@auth');

Route::pattern('year', '[0-9]{4}');                 // years are only 4 digit n$
Route::pattern('month', '[0-9]{1,2}');              // months, 1 - 2 digits
Route::pattern('day', '[0-9]{1,2}');                // days, 1 - 2 digits
Route::pattern('pkey', '[a-z,A-Z]{2,10}');          // project key
Route::pattern('ikey', '[a-z,A-Z]{2,10}-[0-9]+');   // issue key


// Route::group(['middleware' => 'jiraAuth'], function ()
// {
    Route::get(config('jiracal.path').'/{pkey}', 'Josevh\JiraCal\JiraCalController@pkey');
    Route::get(config('jiracal.path').'/{pkey}/{year}', 'Josevh\JiraCal\JiraCalController@year');
    Route::get(config('jiracal.path').'/{pkey}/{year}/{month}', 'Josevh\JiraCal\JiraCalController@month');
    Route::get(config('jiracal.path').'/{pkey}/{year}/{month}/{day}', 'Josevh\JiraCal\JiraCalController@day');
// });
