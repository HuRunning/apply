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

Route::get('/', 'ApplyController@home');
Route::get('Index/wenzhi', 'ApplyController@home');
Route::get('Index/login', 'ApplyController@login');
Route::get('Index/loginout', 'ApplyController@logout');
Route::get('Index/handle', 'ApplyController@apply');
Route::get('Index/getJsonOrg', 'ApplyController@orgsJson');
Route::get('Index/auditHandle', 'ApplyController@audit');
// New API added by oziore.
Route::post('classroom/{classroom}/notice', 'ApplyController@updateNotice');
Route::get('apply/{info}/cancel', 'ApplyController@cancelApply')->name('cancel');