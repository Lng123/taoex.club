
## Changed Files for development
### third_build/resources/views/layouts/header.blade.php
======
header.blade.php 
	Changed hrefs in line 73,79,85,97,100
    href="{{ route('newClub') }}">Create</a></span></li>


### third_build/routes/web.php
======
Route::get('/home/newclub', 'ClubController@showNewClubForm')->name('newClub')->middleware('auth');

### third_build/app/Http/Controllers/Auth/RegisterController.php
======
	#'g-recaptcha-response' => 'required|string|min:1',

