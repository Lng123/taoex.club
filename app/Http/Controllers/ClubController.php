<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Club;
use App\Clubuser;
use App\Match;
use App\MatchResult;
use App\Utility;

class ClubController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $user_table = new User;
        $club_table = new Club;
        $clubuser_table = new Clubuser;
        $match_table = new Match;
        $result_table = new MatchResult;
        $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;

        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');

        //these are default values entered as a band-aid fix by Jose.
        /*$ranking = 0;
        $totalScore = 0;
        //you can remove them if needed but they make sure that new users don't cause errors.*/

        if ($club_id != null && $approved_status == 1) {
            $club = $club_table->where('id', $club_id)->first();
            $clubMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->get();
            $nearPlayers = $user_table->where('approved_status', 0)->get();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();


            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();

            $numberMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->count();
            
            $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();

        
        $total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();

            return view('taoex.club', array('club'=>$club, 'clubMembers'=>$clubMembers, 'matches'=>$matches, 'allPlayers'=>$allPlayers, 'numberMembers'=>$numberMembers, 'allMatches'=>$allMatches, 'clubOwner'=>$clubOwner, 'totalScore'=>$totalScore));
        } else if ($club_id != null && $approved_status == 0) {
            return view('/home', array('message'=>'Wait for Club ownner approving.', 'totalScore'=>$totalScore, 'color'=>'alert-warning', 'status'=>$status));
        }
        //$matches = $tournament->where('club_id', $club->id);
        if ($club_id == null)
        {
            return redirect()->route("home")->withErrors(['You currently do not belong to any club! Please join a club or create one.', 'You currently not belong to any Club! Please Join a Club or Create one.']);
           // return view('/home', array('message'=>'You currently not belong to any Club! Please Join a Club or Create your own one :)','totalScore'=>$totalScore, 'color'=>'alert-warning', 'status'=>Auth::user()->approved_status, 'ranking'=>$ranking));
        }

    }
    
    public function clubMemberRanking(Request $request)
    {
    	
	$date = $request->year;

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
        //Obtain club id
        $club_id = Auth::user()->club_id;
        //Obtain status
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;
        
        $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $clubMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->get();

        $string ="";
        
        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
                $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->get()->count();

        $memberData = [];
        $i = 0;

        foreach ($clubMembers as $clubMember) {
        	
        	$gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->get()->count();
        	
        	$won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->where('winner_id',$clubMember->id)->get()->count();
        	
        	$score = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->sum('total');
        	
        	$rank = ($score/$clubGameCount) * $won;
        	
        	
        	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank'=>ceil($rank));
        	$i++;

            //$string .= " id: " . $clubMember->id . " : " . $gameCount . " gamesWon: ". $won . "//\\";
        }
            return view('taoex.clubFilter', array('memberData'=>$memberData));

    }

    public function showNewClubForm()
    {
        $nearPlayers = DB::table('users')->where('approved_status', 0)->get();
        return view('taoex.applyForClub', array('nearPlayers'=>$nearPlayers));
    }
    
    /*
     * Click on "edit' link will route to this method
     * Show the view of "editClubProfile"
     */
    public function showUpdateClubeForm($club_id)
    {
        $club = Club::findOrFail($club_id);
        return view('taoex.editClubProfile', compact('club'));
    }

    /*
     * save club profile into database table "club"
     */
    public function updateClubProfile(Request $request)
    {
        
        $this->validate($request, [
            'image' => 'sometimes|mimes:jpeg,jpg,png,gif',   
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255'
        ]);
        
        /* get image type and image data before update to database*/
        if( $request->hasFile('image')) {
           $image = Utility::get_image_fromFile($request->file('image'));
        } else {
           $image = Utility::get_image_fromTable($request->id,'Club');
        }
        /* update image to database */
        try {    
                $clubinfo = Club::findOrFail($request->id);
                $clubinfo->update([
                    'name' => $request->Name,
                    'image' => $image['data'],
                    'image_type' => $image['type'],
                    /*'country'=> $request->country,*/
                    'province'=> $request->province,
                    'city'=> $request->city,
                    ]);

        } catch(\Illuminate\Database\QueryException $ex){ 
                return ['error' => 'error: modify club image fail (001)']; 
                //return \Response::json(['status' => 'error', 'error_msg' => var_dump($ex)]);
        }

        return redirect()->route("club");
    }

    public function validateForm(Request $request)
    {
        $clubName = $request->clubName;
        $nearPlayers = DB::table('users')->where('approved_status', 0)->get();
        if (!isset($clubName)) {
            return view('/home/newclub', array('message'=>'Please Enter Your Club Name!'));
        }
    }
    public function applyClub(Request $request)
    {
        $ranking = 99; // To be fixed 123
        $club = new Club;
        $user_table = new User;
        $uid = Auth::user()->id;
        $nearPlayers = DB::table('users')->where('approved_status', 0)->get();
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        $clubName = $request->clubName;
        if (!isset($clubName)) {
            return view('home', array('message'=>'Please Enter Your Club Name!', 'nearPlayers'=>$nearPlayers,'club'=>$club, 'color'=>'alert-danger', 'ranking'=>$ranking,'totalScore'=>$totalScore));
        }
        $club->name = $clubName;
        $club->province = $request->province;
        $club->city = $request->city;
        $checkResult = DB::table('Club')->where('owner_id', $uid)->get();
        if (Auth::user()->club_id !== null) {
            return view('home', array('message'=>'You can only own one club', 'color'=>'alert-danger','club'=>$club, 'ranking'=>$ranking, 'totalScore'=>$totalScore, 'status'=>Auth::user()->approved_status));
        }
        $club->owner_id = $uid;
        $club->save();

        $newClub = new Club;
        $club_id = $newClub->where('owner_id', $uid)->value('id');

        $status = Auth::user()->approved_status;
        

        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        //User::updateUserToClubOwner($uid, $club_id);
        $user_table->where('id', $uid)->update(['type'=>1, 'approved_status'=>1, 'club_id'=>$club_id, 'club_owner'=>1]);
        //$user_table->save();
        return view('/home', Array('message'=>'Club is successly created!', 'totalScore'=>$totalScore, 'ranking'=>$ranking,'color'=>'alert-success', 'club_id'=>$club_id, 'club_name'=>$clubName, 'uid'=>$uid, 'status'=>Auth::user()->approved_status));
    }

    public function invite(Request $request)
    {
        $user_table = new User;
        $uid = Auth::user()->id;
        $player_id = $request->player;
        $club_id = $request->club_id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        $user_table->where('id', $player_id)->update(['approved_status'=>2, 'club_id'=>$club_id]);
        return view('/home', array('message'=>'invitation has been successly sent, please wait for reply!', 'totalScore'=>$totalScore,
                                    'color'=>'alert-success', 'status'=>Auth::user()->approved_status));

    }


    public function acceptInvitation(Request $request)
    {
        $uid = Auth::user()->id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        DB::table('users')->where('id', $uid)->update(['approved_status'=>1]);
        return view('/home', array('color'=>'alert-success', 'message'=>'You have accepted the invitation', 'totalScore'=>$totalScore, 'status'=>Auth::user()->approved_status));
    }

    public function declineInvitation(Request $request)
    {
        $uid = Auth::user()->id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        DB::table('users')->where('id', $uid)->update(['approved_status'=>0, 'club_id'=>NULL]);
        return view('/home', array('color'=>'alert-success', 'message'=>'You have declined the invitation', 'totalScore'=>$totalScore, 'status'=>Auth::user()->approved_status));
    }
    
    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $uid = Auth::user()->id;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        //Finding the club id associated to current club owner THIS QUERY IS FUCKED
        $club_name = DB::table('Club')
                                ->select(DB::raw('name'))
                                ->where('owner_id', $uid)
                                ->get();
        $message = $request->input('message');


        $data = array(
            'club_name'=> $club_name,
            'message'=> $message,
        );
        DB::table('messages')->insert($data);
        
        return view('/yes', array('color'=>'alert-success', 'message'=>'Your message was sent', 'totalScore'=>$totalScore));

    }

}