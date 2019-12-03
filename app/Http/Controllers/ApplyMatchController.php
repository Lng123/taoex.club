<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Match;
use App\Club;
use App\User;
use App\MatchResult;
use Illuminate\Support\Facades\DB;

class ApplyMatchController extends Controller
{
    //


    public function index()
    {
    	return view('taoex.applyNewMatch');
    }

    public function apply(Request $request)
    {
    	$name = $request->name;
    	$address = $request->address;
    	$startDate = $request->startDate;
    	$endDate = $request->endDate;
    	$start_time= $request->start_time;
    	        $approved_status = Auth::user()->approved_status;

    	    	$uid = Auth::user()->id;
    	$club_id = Auth::user()->club_id;
    	$user_table = new User;
    	$result_table = new MatchResult;
        $club_table = new Club;

    	$match_table = new Match;
    	$match_table->name = $name;
    	$match_table->address = $address;
    	$match_table->startDate = $startDate;
    	$match_table->endDate = $startDate;
    	$match_table->start_time = $start_time;
    	$match_table->club_id = $club_id;

    	$match_table->save();
	
	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();
        
        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->get();
        $club_table = new Club;
        $club_score = $club_table->join('users','Club.id' ,'=','users.club_id' )->select("club_score")->where('users.id',$uid)->get();

      $club = $club_table->where('id', $club_id)->first();
            $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();

            $nearPlayers = $user_table->where('approved_status', 0)->get();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();


            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();

            $numberMembers = $clubMembers->count();
            
            $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $createSuccess = 1;

            return view('taoex.club', array('club'=>$club, 'clubMembers'=>$clubMembers, 'matches'=>$matches, 'allPlayers'=>$allPlayers, 'numberMembers'=>$numberMembers, 'allMatches'=>$allMatches, 'clubOwner'=>$clubOwner, 'totalScore'=>$totalScore, 'createSuccess'=>$createSuccess, 'clubScore' => $club_score));
    }

    /**
     * Records a match score for a player.
     */
    public function record(Request $request)
    {
        $club_table = new Club;

        $result_table = new MatchResult;
	    $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
        $approved_status = Auth::user()->approved_status;
    	$HOK = 5;
        $ELI = 2;
        $CAP = 1;
        $LEB = 5;
        $ART = 10;
        $WIN = 6;
        $match_result = new MatchResult;
        $match_table = new Match;
        $match_id = $request->match_id;
        $player_id = $request->player_id;
        $numberPlayers = $request->numberPlayers;
        $current_match = $match_table->where('id', $match_id)->get();

        $elimination = $request->elimination;
        $capture = $request->capture;
        $hook = $request->hook;
        $winBonus = $request->winBonus;
        if (($current_match[0]->winner_id == $player_id || $current_match[0]->winner_id == NULL)&&($winBonus == 5 || $winBonus == 6 || $winBonus == 7) || $winBonus == 0) {
        if ($winBonus == 0) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI;
            $winBonusR = 0;
        } else if ($winBonus == 5) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + $LEB*$numberPlayers + 6 * $numberPlayers;
            $winBonusR = $LEB*$numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        } else if($winBonus == 6) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + 6 * $numberPlayers;
            $winBonusR =  6 * $numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        } else {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + $ART + 6 * $numberPlayers;
            $winBonusR = $ART;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        }

        $check = $match_result->where('player_id', $player_id)->where('match_id', $match_id)->get()->count();
        $totalScore = DB::table('MatchResult')->where('player_id', Auth::user()->id)->sum('total');
       
            
        if ($check == 0) {
            
        $match_result->match_id = $match_id;
        $match_result->player_id = $player_id;
        $match_result->elimination = $elimination;
        $match_result->capture = $capture;
        $match_result->hook = $hook;
        $match_result->winBonus = $winBonusR;
        $match_result->total = $total;
        $match_result->place = 0;

        $match_result->save();
        
        } else {
            $existing_match = $match_result->where('player_id', $player_id)->where('match_id', $match_id)->update(['elimination'=>$elimination, 'capture'=>$capture, 'hook'=>$hook, 'winBonus'=>$winBonusR, 'total'=>$total,'place'=>0]);
                if($current_match[0]->winner_id == $player_id && $winBonus == 0) {
                    DB::table('Match')->where('id', $match_id)->update(['winner_id'=>NULL]);
                }
        }

            
        $this->updateCScore($club_id);
        $this->updateSeason();
        $club_score = $club_table->join('users','Club.id' ,'=','users.club_id' )->select("club_score")->where('users.id',$uid)->get();
    	$user_table = new User;

	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();
        
        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->get();


        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
	$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();

            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        	$clubMembers = $user_table->get();
    	
    	$club = $club_table->where('id', $club_id)->first();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
            $numberMembers = $clubMembers->count();
            $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();
	$total_score = $result_table->where('player_id', $uid)->sum('total');

            if ($check == 0) {
    	$recordSuccess = 1;
        $winnerExist = 0;
        $updateSuccess = 0;
            }else {
                $recordSuccess = 0;
        $winnerExist = 0;
        $updateSuccess = 1;
            }
        } else {
            $user_table = new User;

	    $total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();
        
        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->get();


        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
	    $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();

            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        	$clubMembers = $user_table->get();
    	
    	$club = $club_table->where('id', $club_id)->first();
        $allPlayers = $user_table->where('id', '!=', Null)->get();
        $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
        $numberMembers = $clubMembers->count();
        $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();
	$total_score = $result_table->where('player_id', $uid)->sum('total');
            $recordSuccess = 0;
            $winnerExist = 1;
            $updateSuccess = 0;
        }
    	return view('taoex.club', array('club'=>$club, 'clubMembers'=>$clubMembers, 'matches'=>$matches, 'allPlayers'=>$allPlayers, 'numberMembers'=>$numberMembers, 'allMatches'=>$allMatches, 'clubOwner'=>$clubOwner, 'totalScore'=>$totalScore, 'recordSuccess'=>$recordSuccess, 'winnerExist'=>$winnerExist, 'updateSuccess'=>$updateSuccess, 'clubScore' => $club_score));
        }

    /**
     * Updates the club score for that club based off the match results.
     */
    public function updateCScore($club_id) {
        $date = date("Y");

        //User Table
    	$user_table = new User;
        //Club Table
        $club_table = new Club;
        //Match Table
        $match_table = new Match;
        //Match Result Table
        $result_table = new MatchResult;
        
        //Obtain user ID
        $uid = Auth::user()->id;

        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;
        
        $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();

        $string ="";
        
        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
                $clubGameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->get()->count();

        $memberData = [];
        $i = 0;
        $total_score = 0;
        $rank = 0;

        foreach ($clubMembers as $clubMember) {
        	
        	$gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->get()->count();
        	
        	$won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->where('winner_id',$clubMember->id)->get()->count();
        	
            $score = DB::select("SELECT SUM(score.total) as tscore
            FROM (SELECT total
            FROM MatchResult
            JOIN `Match`
            ON `Match`.`id` = MatchResult.match_id
            WHERE club_id = $club_id
            AND player_id = $clubMember->id
            AND endDate >= '$date-01-1'
            AND endDate <= '$date-12-31'
            ORDER BY total DESC
            LIMIT 10) AS score")[0]->tscore;

        	$total_score += $score;
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank'=>$rank);
        	$i++;
        }
        $total_score = $total_score / $i;
        $total_score = round($total_score);
        $club_table->where('id',$club_id)->update(['club_score'=>$total_score]);

    }

    /***
     * Compares the current year with the year in the database. 
     * If it is different, it updates club score of all clubs.
     */
    public function updateSeason() {
        $date = date("Y");
        $club_table = new Club;

        $clubs = $club_table->get();
        foreach($clubs as $club) {
            if($club->season != $date) {
                $this-> updateCScore($club->id);
                $club_table->where('id',$club->id)->update(['season'=>$date]);
            }
        }
    }
    
              
}