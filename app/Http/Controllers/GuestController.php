<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Match;
use App\MatchResult;
use App\User;
use App\Club;

class GuestController extends Controller
{
    public function index() {
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;
        $club_table = new Club;
        $club_count = Club::count();
        $clubs = $club_table->join('users', 'owner_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'Club.*')->get();
        
        $rankings = $user_table->orderBy('score','desc')->take(5)->get();
        $playerCount = User::count();

	 $adminAnnouncements = DB::table('announcements')
           ->select('announcement', 'time_sent')
           ->latest('time_sent')
           ->get();

        return view('taoex.guest')->with(['club_count'=> $club_count,
                                          'clubs' => $clubs,
                                          'ranking'=> $rankings,
                                          'player_count'=> $playerCount,
                                          'adminAnnouncements' => $adminAnnouncements]);
    }
}