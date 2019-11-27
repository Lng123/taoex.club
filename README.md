# Taoex Club readme

test

## Changed Files for development
### resources/views/layouts/header.blade.php
------
 
	
    Changed hrefs in line 73,79,85,97,100 
    href="{{ route('newClub') }}">Create</a></span></li>


### routes/web.php

    Route::get('/home/newclub', 'ClubController@showNewClubForm')->name('newClub')->middleware('auth');

### app/Http/Controllers/Auth/RegisterController.php

	#'g-recaptcha-response' => 'required|string|min:1',

