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


//This creates the functions for the routes inside RegisterController.php and other files in Http\Controllers\Auth
Auth::routes();

//Route::get('/', 'HomeController@index');
Route::get('/', 'GuestController@index')->name('guest');
Route::get('/home/ranking', 'RankingController@index');



Route::get('/home/policy', 'PolicyController@index');
Route::get('/home', 'HomeController@index')->name('home');
//Open admin
Route::get('/home/admin', 'HomeController@openAdmin');
Route::post('/home/admin', 'HomeController@sendAnnouncement');
Route::post('/home/admin/deleteMatch', 'HomeController@deleteMatch');
Route::post('/home/admin/addMatch', 'HomeController@record');
Route::post('/home/admin/editResult', 'HomeController@editMatch');

//Rank
Route::get('/home/userRank', 'UserRankController@index');

//Club
Route::get('/home/club', 'ClubController@index')->name('club')->middleware('auth');
Route::post('applyClub', 'ClubController@applyClub');
Route::get('/home/newclub', 'ClubController@showNewClubForm')->name('newClub')->middleware('auth');
Route::get('/home/{club_id}/editClubProfile', 'ClubController@showUpdateClubeForm')->name('editClub');
Route::post('updateClubProfile', 'ClubController@updateClubProfile')->name('updateClub');
Route::post('/home/clubMember', 'ClubController@clubMemberRanking');
Route::get('/home/clubFilter', 'ClubFilterController@index')->name('clubFilter')->middleware('auth');
Route::post('/home/clubFiltered', 'ClubFilterController@clubMemberRanking')->name('clubFiltered')->middleware('auth');
Route::post('/home/Club', 'ClubController@sendMessage');
Route::get('/home/{club_id}/playersearch', 'ClubController@playersearch');

//invitation
Route::post('invitePlayer', 'ClubController@invite');
Route::get('acceptInvatation', 'ClubController@acceptInvitation');
Route::get('declineInvataion', 'ClubController@declineInvitation');

//league
Route::get('/home/league', 'LeagueController@index')->name('league')->middleware('auth');

//Match
Route::post('applyMatch', 'ApplyMatchController@apply');
Route::post('home/club/recordMatch', 'ApplyMatchController@record');
Route::get('/home/applyNewMatch', 'ApplyMatchController@index')->name('applyNewMatch')->middleware('auth');
Route::get('/home/matchHistory', 'MatchController@index')->name('matchHistory')->middleware('auth');
Route::post('/home/filterMatch', 'MatchController@filter');
Route::get('/home/allMatch', 'MatchController@all');

//Password Reset Routes
Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ForgotPasswordController@reset');
Route::get('password/reset/{token}', 'Auth\ForgotPasswordController@showResetForm')->name('password.reset');

//for edit and delete users profiles
Route::get('/home/{id}/edituser', 'Auth\UsersController@showEditForm')->name('editUser');
Route::get('/home/{id}/deleteuser','Auth\UsersController@deleteUserInfo')->name('deleteUser');
Route::delete('/home/{id}/deleteuseraction','Auth\UsersController@deleteUserAction')->name('deleteUserAction');
Route::post('updateUserInfo', 'Auth\UsersController@updateUserInfo')->name('updateUser');