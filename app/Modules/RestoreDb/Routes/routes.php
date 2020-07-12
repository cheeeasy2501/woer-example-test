<?php

Route::group(['namespace' =>'App\Modules\RestoreDb\Controllers', 'prefix' => 'backup'], function () {
    Route::get('/', 'RestoreController@index');
    Route::post('/restore', 'RestoreController@restore');
    Route::get('/get_table_data/page={page}&limit={limit}','ApiController@getData');
    Route::get('/headers','ApiController@getHeadNames');
    Route::post('/filter','ApiController@getDataByFilter');
 });


