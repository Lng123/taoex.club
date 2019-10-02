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



Auth::routes();
Route::get('/login', 'HomeController@index');
Route::get('/', 'GuestController@index');
//Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/club', 'ClubController@index')->name('club')->middleware('auth');
Route::get('/home/newclub', 'ClubController@showNewClubForm')->middleware('auth');
Route::get('/home/applyNewMatch', 'ApplyMatchController@index')->middleware('auth');
Route::get('/home/matchHistory', 'MatchController@index')->middleware('auth');
Route::get('/home/league', 'LeagueController@index')->middleware('auth');
Route::get('acceptInvatation', 'ClubController@acceptInvitation');
Route::get('declineInvataion', 'ClubController@declineInvitation');

Route::get('/policy', 'PolicyController@index');

Route::post('applyClub', 'ClubController@applyClub');
Route::post('applyMatch', 'ApplyMatchController@apply');
Route::post('invitePlayer', 'ClubController@invite');
Route::post('recordMatch', 'ApplyMatchController@record');
Route::post('/home/filterMatch', 'MatchController@filter');
Route::get('/home/allMatch', 'MatchController@all');