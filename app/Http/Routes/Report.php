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
    Route::get('date/{day}','ReportController@getByDay')->middleware('token');

});