<?php

use App\Core\Core;
use App\Http\Route;

Route::get('/', 'HomeController@index');
Route::get('/show','HomeController@show');
Route::get('/about','HomeController@about');
Route::post('/create','HomeController@create');


Core::dispatch(Route::getRoute());