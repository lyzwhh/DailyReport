<?php
/**
 * Created by PhpStorm.
 * User: yuse
 * Date: 2018/12/22
 * Time: 9:49
 */
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'report'
],function (){
    Route::post('add','ReportController@add')->middleware('token');
    Route::get('date/{date}','ReportController@getByDate')->middleware('token');
    Route::get('name/{name}/{limit}/{offset}','ReportController@getByUser')->middleware('token');
    Route::get('frequency/date/{date}','ReportController@getDateSegmentation')->middleware('token');


});