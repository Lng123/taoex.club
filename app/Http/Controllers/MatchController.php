<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Match;
use App\MatchResult;
use App\User;
//use DB;

class MatchController extends Controller
{
    public function index()
    {
	$match_table = new Match;
	$result_table = new MatchResult;
		
        $user_table = new User;
    	$uid = Auth::user()->id;
		$club_id = Auth::user()->club_id;
		$owner_id = DB::table('Club')->where('id',$club_id)->select('owner_id')->value('owner_id');
		$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
    	$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
	return view('taoex.matchHistory', array('owner_id'=>$owner_id,'results'=>$results, 'matches'=>$matches));
	}
	
	public function filter(Request $request)
	{
		$date = $request->date;
		$date2 = $request->date2;
		$errorMsg = "hi";
		if($date === NULL || $date2 === NULL) {
			$errorMsg = "No matches could be found";
		} 

		$match_table = new Match;
		$result_table = new MatchResult;
		
        $user_table = new User;
    	$uid = Auth::user()->id;
		$club_id = Auth::user()->club_id;
		//if ($asc == 1) { }
		$matches = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date."-1")->where('endDate', '<=', $date2."-31")->orderBy('endDate', 'desc')->get();
		$owner_id = DB::table('Club')->where('id',$club_id)->select('owner_id')->value('owner_id');
		$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
		return view('taoex.matchHistory', array('owner_id'=>$owner_id,'results'=>$results, 'matches'=>$matches, $errorMsg));
	}

	public function deleteMatch(Request $request)
    {
        $matchTarget = $request->matchName;
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;

        $matches = $match_table->orderBy('endDate', 'desc')->get();

        $matchID = $match_table->where('name', $matchTarget)->pluck('id');
        $targetMatchResults = $result_table->where('match_id', $matchID)->delete();
        $matchID = $match_table->where('name', $matchTarget)->delete();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $clubMembers = $user_table->get();

        $deleteSuccess = 1;
        return view('taoex.admin', array('matches' => $matches, 'results' => $results, 'clubMembers' => $clubMembers, 'deleteSuccess' => $deleteSuccess));
    }


	public function all()
	{
		$match_table = new Match;
		$result_table = new MatchResult;
		
        $user_table = new User;
    	$uid = Auth::user()->id;
    	$club_id = Auth::user()->club_id;
		$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
		$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
		$owner_id = DB::table('Club')->where('id',$club_id)->select('owner_id')->value('owner_id');
		return view('taoex.matchHistory', array('owner_id'=>$owner_id,'results'=>$results, 'matches'=>$matches, 'matches'=>$matches));
	}
}