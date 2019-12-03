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
Route::get('/home/adminManageClub', 'HomeController@openClubAdmin')->name('openClubAdmin')->middleware('is_admin');
Route::get('/home/adminManageUser', 'HomeController@openUserAdmin')->name('openUserAdmin')->middleware('is_admin');
Route::post('/home/adminManageUser', 'HomeController@editName')->name('editName');
Route::get('home/adminBannedUsers','HomeController@openBannedUsers')->name('openBannedUsers')->middleware('is_admin');
Route::get('/home/admin', 'HomeController@openAdmin')->name('openAdmin');
Route::post('/home/admin/deleteMatch', 'HomeController@deleteMatch');
Route::post('/home/admin/addMatch', 'HomeController@record');
Route::post('/home/admin/editResult', 'HomeController@editMatch');

//Delete User Admin
Route::get('/deleteuseradmin/{id}', 'HomeController@deleteUserAdmin')->name('deleteUserAdmin');

//Rank
Route::get('/home/userRank', 'UserRankController@index');

//Change Active Club
Route::get('/changeclub/{club_id}', 'HomeController@changeActiveClub')->name('changeClub');

//Club
Route::get('/home/club', 'ClubController@index')->name('club')->middleware('auth');


Route::get('/home/club/showAllClub', 'ClubController@showAllClub')->name('clubBrowser')->middleware('auth');
Route::post('/home', 'ClubController@applyClub');


Route::get('/home/clubBrowser', 'ClubController@showAllClub')->name('clubBrowser')->middleware('auth');
//Route::post('/home', 'ClubController@applyClub')->name('applyClub');
Route::post('applyClub',array('uses' =>'ClubController@applyClub'));


Route::get('/home/newclub', 'ClubController@showNewClubForm')->name('newClub')->middleware('auth');
Route::get('/home/manageClub', 'ClubController@showManageClub')->name('manageClub')->middleware('is_club_owner');
Route::post('updateClubProfile', 'ClubController@updateClubProfile')->name('updateClub');
Route::get('/removeMember/{id}', 'ClubController@removeClubMember')->name('removeMember');
Route::get('/adminRemoveMember/{club_id}/{id}', 'ClubController@adminRemoveMember')->name('adminRemoveMember');
Route::get('/changeClubOwner/{id}', 'ClubController@changeClubOwner')->name('changeClubOwner');
Route::get('/adminChangeClubOwner/{club_id}/{id}', 'ClubController@adminChangeClubOwner')->name('adminChangeClubOwner');
Route::post('/home/clubMember', 'ClubController@clubMemberRanking');
Route::get('/home/clubFilter/{id}', 'ClubFilterController@index')->name('clubFilter')->middleware('auth');
Route::post('/home/clubFiltered', 'ClubFilterController@clubMemberRanking')->name('clubFiltered')->middleware('auth');
Route::post('/home/Club', 'ClubController@sendMessagePage');
Route::get('/home/club/playersearch', 'ClubController@playersearch');

//club application
Route::post('/home/club/showAllClub', 'ClubController@playerApply')->name('playerApplyToClub');


//club invitation
Route::post('/home/club/playersearch', 'ClubController@invite')->name('invitePlayer');
//Route::post('invitePlayer', 'ClubController@invite')->name('invitePlayer');
//Route::get('/invitePlayer/{user_id}','ClubController@invite')->name('invitePlayer');
Route::get('/acceptInvitation/{id}', 'ClubController@acceptInvitation')->name('acceptInvite');
Route::get('acceptInvatation', 'ClubController@acceptInvitation');
Route::get('declineInvataion', 'ClubController@declineInvitation');
Route::get('acceptClubApplication/{userid}/{clubid}', 'ClubController@acceptClubApplication')->name('acceptClubApplication');
Route::get('declineClubApplication/{userid}/{clubid}', 'ClubController@declineClubApplication')->name('declineClubApplication');


//league
Route::get('/home/league', 'LeagueController@index')->name('league')->middleware('auth');

//Match
Route::post('/home', 'ApplyMatchController@apply');
Route::post('home/club', 'ApplyMatchController@record');
Route::get('/applyNewMatch', 'ApplyMatchController@index')->name('applyNewMatch')->middleware('auth');
Route::get('/home/matchHistory', 'MatchController@index')->name('matchHistory')->middleware('auth');
Route::post('/home/filterMatch', 'MatchController@filter');
Route::get('/home/allMatch', 'MatchController@all');
Route::get('/home/deleteMatch/{match_id}','AdminController@deleteMatch')->name('deleteMatch');
Route::get('/home/deleteMatchRecord/{match_record_id}','AdminController@deleteMatchRecord')->name('deleteMatchRecord');

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

//messaging
Route::get('/home/deleteMessage/{id}/{sender}/{message_time}', 'MessageController@deleteMessage')->name('deleteMessage');
Route::get('/home/replyMessage/{id}/{sender}/{message_time}', 'MessageController@replyMessage')->name('replyMessage');
Route::get('/home/clubOwnerSendMessage/{id}','ClubController@openClubOwnerMessage')->name('openClubOwnerMessage');
Route::post('/home/clubOwnerSendMessage/', 'MessageController@sendClubMemberMessage');
Route::post('/home/replyMessage/', 'MessageController@sendReplyMessage');
Route::post('/home/admin/sendMessageRequest', 'MessageController@sendMessageRequest')->name('sendMessageRequest');

//admin functions

Route::get('home/adminManageClub/{club_id}','AdminController@deleteClub')->name('adminDeleteClub');
Route::get('/home/{club_id}/manageClubMembers', 'Auth\ClubsController@adminManageMembers')->name('manageClubMembers')->middleware('is_admin');
Route::post('updateClubMembers', 'Auth\ClubsController@updateClubMembers')->name('updateClubMembers');



//Announcement controls
Route::get('/home/adminSendMessage/{id}','AdminController@openAdminMessage')->name('openAdminMessage');
Route::get('/home/adminBanUser/{id}','AdminController@banUser')->name('banUser');
Route::get('/home/adminUnbanUser/{id}','AdminController@unbanUser')->name('unbanUser');
Route::post('/home/adminSendMessage/', 'MessageController@sendAdminMessage');
Route::get('/home/adminBanUser/{id}','AdminController@banUser')->name('adminBanUser');
Route::post('/home/admin/sendAnnouncement', 'AdminController@sendAnnouncement')->name('sendAnnouncement');
Route::get('/home/admin/announcements', 'AdminController@openAnnouncement')->name('openAnnouncement');
Route::post('/home/admin/deleteAnnouncement', 'AdminController@deleteAnnouncement')->name('deleteAnnouncement');
Route::post('/home/admitSubmitBan/', 'AdminController@submitUserBan');
