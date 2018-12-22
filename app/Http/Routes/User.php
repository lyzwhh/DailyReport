<?php
/**
 * Created by PhpStorm.
 * User: yuse
 * Date: 2018/12/6
 * Time: 20:27
 */
use Illuminate\Support\Facades\Route;
Route::group([
    'prefix' => 'user'
],function (){
    Route::post('register','UserController@register');
    Route::post('login','UserController@login');

});