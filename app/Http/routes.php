<?php
/**
 * Created by PhpStorm.
 * User: yuse
 * Date: 2018/12/6
 * Time: 19:20
 */
Route::get('/', function () {
    return view('welcome');
});
include 'Routes/User.php';