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
            $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
            $nearPlayers = $user_table->where('approved_status', 0)->get();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();


            $clubOwner = $clubMembers->where('id', $club->owner_id)->first();

            $numberMembers = $clubMembers->count();
            
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
    
    public function showAllClub()
    {
       
    	$match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;

        $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
           
        $status = Auth::user()->approved_status;
        $club_table = new Club;
        $clubuser_table = new Clubuser;
        
        //$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
	//$matches = $match_table->paginate(3);
	
	//$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
    	//$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        
        $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
    	$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
    	$club_list = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->where('UserClubs.id',$uid)->get();
    	$all_clubs = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->distinct()->get();
    	
       
	//$clubusers = $clubuser_table->get();
	$total_score = $result_table->where('player_id', $uid)->sum('total');
       
       //$sumString = "";
       //for($i = 37; $i < 62; $i++) {
       //$total_score = $result_table->where('player_id', '=', $i)->sum('total');
       //$sumString .= "id: " . $i . "     sum: ". $total_score . "; \r\n";
       //}
       //return $sumString;
       
       //***** Number of players in the database ****
       $player_count = $user_table->orderBy('score','desc')->get()->count();
       $ranking = $user_table->where('score','>=', $total_score)->get()->count();
       //return $ranking;
       
       $users = $user_table->get();
        foreach ($users as $user) { 
                $totalScore = $result_table->where('player_id',$user->id)->sum('total');
    		User::where('id', $user->id)->update(array('score'=>$totalScore));
         }
        //Session::put('totalScore', $totalScore);
        $club = $club_table->where('id', $club_id)->first();
        
        //$club_list = DB::table('Club')->where('owner_id', $uid)->get();
        $club_list = DB::table('Club')
        ->select('Club.name', 'Club.id', 'Club.owner_id','Club.created_at', 'users.firstName', 'users.lastName')
        ->join('users', 'Club.owner_id', '=', 'users.id')
        ->get();
        
        $userClubID = Auth::user()->club_id;

        $userClubName = DB::table('Club')
        ->select(DB::raw('name'))
        ->where('id', $userClubID)
        ->get();

        $test = (String) $userClubName;

        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->where('club_name', $test)
                            ->get();
	$clubMembers = $user_table->get();

        //$clubuser = $clubuser_table->where('user_id', Auth::user()->id)->first();
        //$club = $club_table->where('id', $clubuser->club_id)->first();
        	$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        
        return view('taoex.clubBrowser', array('club_list'=>$club_list, 'club'=>$club,  'club_id'=>$club_id, 'status'=>$status, 'matches'=>$matches, 'totalScore'=>$total_score, 'ranking'=>$ranking, 'userMessages'=>$userMessages, 'results'=>$results, 'clubMembers'=>$clubMembers));
    }

    /*
     * Click on "edit' link will route to this method
     * Show the view of "editClubProfile"
     */
    public function showManageClub()
    {
        $date = date('Y');
        //Obtain club id
        $club_id = Auth::user()->club_id;
        //User Table
        //$club_list = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->where('UserClubs.id',$uid)->get();
    	$clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
        //Club Table
        $club_table = new Club;
        //Match Table
        $match_table = new Match;
        //Match Result Table
        $result_table = new MatchResult;
        
        //Obtain user ID
        $uid = Auth::user()->id;
        
        $club = Club::findOrFail($club_id);
        //Obtain status
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;
        
        $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        //$clubMembers = $user_table->where('club_id', $club_id)->get();

        $string ="";
        
        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
                $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->get()->count();

        $memberData = [];
        $i = 0;

        foreach ($clubMembers as $clubMember) {
        	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'id' => $clubMember->id);
        	$i++;

            //$string .= " id: " . $clubMember->id . " : " . $gameCount . " gamesWon: ". $won . "//\\";
        }

        return view('taoex.manageClub', compact('club'), array('memberData'=>$memberData, 'club_owner'=>$club->owner_id));
    }

    public function removeClubMember($id)
    {
        $club_id = Auth::user()->club_id;
        User::where('id', $id)->where('club_id', $club_id)->update(['club_id' => null]);
        DB::table('userclubs')->where('id', $id)->where('club_id', $club_id)->delete();
        return redirect()->route('manageClub');
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

    // public function removeClubMember($id) {
    //     UserClubs::where('id', $id)->delete()
    // }

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

        $ranking = 99;
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
        $club->owner_id = $uid;
        $club->save();
        
        $newClub = new Club;
        $club_d = $newClub->where('owner_id', $uid)->value('id');
        
        $status = Auth::user()->approved_status;
        
        $club_id = $club->id;

        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        //User::updateUserToClubOwner($uid, $club_d);
        $user_table->where('id', $uid)->update(['type'=>1, 'approved_status'=>1, 'club_id'=>$club_id, 'club_owner'=>1]);
        
        
        $club_list = DB::table('Club')->where('owner_id', $uid)->get();
        //$user_table->save();
        
        $match_table = new Match();
        $result_table = new MatchResult;
        $matches = $match_table->where('club_id', $club_d)->orderBy('endDate', 'desc')->take(3)->get();
    	$results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        DB::table('userclubs')->insert(['id'=>$uid,'club_id'=>$club_id]);
        return redirect('/home/club');
        //return view('/home', Array('message'=>'Club is successly created!', 'totalScore'=>$totalScore, 'ranking'=>$ranking,'color'=>'alert-success', 'club_id'=>$club_id, 'club_name'=>$clubName, 'uid'=>$uid, 'club'=>$club, 'club_list'=>$club_list, 'status'=>Auth::user()->approved_status, 'matches'=>$matches));
    }

    public function invite(Request $request)
    {

        // $status = Auth::user()->approved_status;

        $userid = $request->input('ranking');
        $uid = (int)$userid;
        $club_id = Auth::user()->club_id;
        DB::table('Invite')->insert(['id' => $uid, 'club_id' =>$club_id]);
        return $this->playersearch();
        // return view('/home', array('message'=>'invitation has been successly sent, please wait for reply!', 'totalScore'=>$totalScore,
        //                              'color'=>'alert-success', 'status'=>Auth::user()->approved_status));

    }


    public function acceptInvitation($id)
    {
        $uid = Auth::user()->id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        DB::table('users')->where('id', $uid)->update(['approved_status'=>1]);
        $ranking = 0;
        $club_list = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->where('UserClubs.id',$uid)->get();
        $userClubID = Auth::user()->club_id;
        DB::table('invite')->where('id','=',$uid)->where('club_id','=',$id)->delete();
        DB::table('userclubs')->insert(['id'=>$uid,'club_id'=>$id]);
        $userClubName = DB::table('Club')
        ->select(DB::raw('name'))
        ->where('id', $userClubID)
        ->get();
        DB::table('users')->where('id',$uid)->update(['club_id'=>$id]);
        $test = (String) $userClubName;
        $messages = DB::table('messages')
        ->select('message', 'message_id')
        ->where('club_name', $test)
        ->get();
        return redirect('/home/club');
        //return view('/home', array('color'=>'alert-success','messages'=> $messages, 'message'=>'You have accepted the invitation', 'totalScore'=>$totalScore, 'status'=>Auth::user()->approved_status,'club_list' =>$club_list,'ranking' => $ranking));
    }

    public function declineInvitation(Request $request)
    {
        $uid = Auth::user()->id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        DB::table('users')->where('id', $uid)->update(['approved_status'=>0, 'club_id'=>NULL]);
        DB::table('invite')->where('id','=',$uid)->where('club_id','=',$id)->delete();
        return view('/home', array('color'=>'alert-success', 'message'=>'You have declined the invitation', 'totalScore'=>$totalScore, 'status'=>Auth::user()->approved_status));
    }
    
    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $uid = Auth::user()->id;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        //Finding the club id associated to current club owner THIS QUERY IS MESSED
        #$club_name = DB::table('Club')
        #                        ->select(DB::raw('name'))
        #                        ->where('owner_id', $uid)
        #                        ->get();
        $club_name = DB::table('users')->select('name')->join('club','club.id', '=','users.club_id')->where('users.id',$uid)->get();
        #$club_list = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->where('UserClubs.id',$uid)->get();
        $message = $request->input('message');
        $ranking = 0;
        $club_list = DB::table('UserClubs')->join('club','club.id','=','UserClubs.club_id')->select('club.*')->where('UserClubs.id',$uid)->get();
        $userClubID = Auth::user()->club_id;

        $userClubName = DB::table('Club')
        ->select(DB::raw('name'))
        ->where('id', $userClubID)
        ->get();

        $test = (String) $userClubName;
        $messages = DB::table('messages')
        ->select('message', 'message_id')
        ->where('club_name', $test)
        ->get();

        #$data = array(
        #    'club_name'=> $club_name->name,
        #    'message'=> $message,
        #);
        DB::table('messages')->insert(['club_name' => $club_name,'message'=>$message]);
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        #return view('/yes', array('color'=>'alert-success', 'message'=>'Your message was sent', 'totalScore'=>$totalScore));
        return view ('/home',array('totalScore'=>$totalScore,'ranking'=>$ranking,'messages'=>$messages));
    }

    public function playersearch() {
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;
        $club_table = new Club;
        $uid = Auth::user()->id;
        $club_count = Club::count();
        $club_id = Auth::user()->club_id;
        $club = $club_table->where('id', $club_id)->first();
        $already_invited = [];
        $club_members = [];
        $clubs = $club_table->join('users', 'owner_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'Club.*')->get();
        
        $allinvites = $user_table->leftJoin('Invite', 'users.id', '=', 'Invite.id')->get();
        $usersinclubs = DB::table('userclubs')->where('club_id',$club_id)->get();

        foreach($usersinclubs as $cmembers) {
            array_push($club_members, $cmembers->id);
        }
         

        foreach($allinvites as $invites) {
            if ($invites->club_id == $club_id) {
                array_push($already_invited,$invites->id); 
            }
        }
        
        $rankings = $user_table->orderBy('score','desc')->get();

        $playerCount = User::count();



        return view('taoex.playersearch')->with(['club_count'=> $club_count,
                                          'clubs' => $clubs, 'ranking'=> $rankings, 'player_count'=> $playerCount, 'club' => $club, 
                                          'already_invited' =>$already_invited,
                                          'club_members' => $club_members]);
    }

    public function adminManageMembers($club_id) {
        $clubs = new Club;
        $clubMembers = DB::table('UserClubs')
            ->join('users','users.id','=','UserClubs.id')
            ->select('*')
            ->where('UserClubs.club_id', $club_id)
            ->get();
        $currentClub = $clubs->where('id', $club_id)->first();
        $memberData = [];
        $clubData = [];
        $i = 0;
        foreach ($clubMembers as $clubMember) {       	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'id' => $clubMember->id);
        	$i++;
        }
        $i = 0;
        foreach ($clubs as $club) {
            $clubData[$i] = array('club_id' => $clubs->id, 'club_name' => $clubs->name);
            $i++;
        }

        return view('taoex.adminManageClubMembers', array('memberData'=>$memberData, 'clubData'=>$clubData, 'club_owner'=>$currentClub->owner_id, 'club_id'=>$club_id));
    }

    public function adminRemoveMember($club_id, $id)
    {
        User::where('id', $id)->where('club_id', $club_id)->update(['club_id' => null]);
        DB::table('userclubs')->where('id', $id)->where('club_id', $club_id)->delete();
        return redirect()->route('manageClubMembers', ['club_id'=>$club_id]);
    }

}