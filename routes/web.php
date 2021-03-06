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

Route::get('/', function () {
    return view('welcome');
});

Route::get ('/codes',                   'QCCodeController@show_k_list');
Route::get ('/codes/k/{k}',             'QCCodeController@show_p_list_of_k');
Route::get ('/codes/k/{k}/p/{p}',       'QCCodeController@show_code_list_by_k_p');
Route::get ('/codes/k/{k}/p/{p}/d/{d}', 'QCCodeController@show_code_list_by_k_p_d');
Route::post('/codes',                   'QCCodeController@add_code');
Route::get ('/codes/best-table',        'QCCodeController@get_best_table');
Route::get ('/codes/pending-list',      'QCCodeController@get_dual_pending_list');
Route::get ('/codes/{id}',              'QCCodeController@show_detail');
Route::put ('/codes/{id}',              'QCCodeController@update_detail');

Route::get ('/dual-codes/best-table',        'QCCodeController@get_dual_best_table');
Route::get ('/dual-codes/k/{k}/p/{p}/d/{d}', 'QCCodeController@get_dual_codes_by_kpd');

Route::get ('/login',  'LoginController@login');
Route::get ('/logout', 'LoginController@logout');
Route::get ('/login-status', 'LoginController@login_status');
