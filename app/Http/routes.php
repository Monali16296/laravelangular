<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::post('create', 'StudInsertController@Insert');
Route::post('edit', 'StudInsertController@Edit');
Route::post('change', 'StudInsertController@ChangePassword');
Route::post('forgot', 'StudInsertController@ForgotPassword');
Route::post('list', 'StudInsertController@UserList');
Route::post('view', 'StudInsertController@UserView');
Route::post('delete', 'StudInsertController@UserDelete');
Route::post('userEdit', 'StudInsertController@UserEdit');
Route::post('loginAdmin', 'StudInsertController@AdminLogin');
Route::post('userAdd', 'StudInsertController@UserAdd');
Route::post('userEditForm', 'StudInsertController@UserEditForm');
Route::post('list2', 'StudInsertController@UserSortingList');
Route::post('list3', 'StudInsertController@UserSearchingList');
Route::post('activeAll', 'StudInsertController@ActiveAll');
Route::post('inActiveAll', 'StudInsertController@InActiveAll');
Route::post('deleteRecords', 'StudInsertController@DeleteRecords');
Route::post('activeRecords', 'StudInsertController@ActiveRecords');
Route::post('inactiveRecords', 'StudInsertController@InActiveRecords');
