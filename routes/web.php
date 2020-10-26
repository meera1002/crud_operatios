<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/register', 'Auth\AuthController@register')->name('register');
Route::post('/register', 'Auth\AuthController@storeUser');

Route::get('/', 'Auth\AuthController@login')->name('login');
Route::post('/login', 'Auth\AuthController@authenticate');
Route::get('logout', 'Auth\AuthController@logout')->name('logout');
Route::get('reload-captcha', 'Auth\AuthController@reloadCaptcha');

Route::middleware('can:isUser')->group(function() {
    Route::get('/activity-list', 'ActivityController@index')->name('activity-list');
    Route::get('/activity/{id}/edit', 'ActivityController@edit');
    Route::post('/activity/{id}', 'ActivityController@update');
    Route::post('fetch-activity', 'ActivityController@moreActivity');
});

Route::middleware('can:isAdmin')->group(function() {
    Route::get('/admin-dashboard', 'AdminController@index')->name('activity-list');
    Route::get('/admin/activity/{id}', 'AdminController@show');
    Route::post('/admin/activity/delete/{id}', 'AdminController@destroy');
});

