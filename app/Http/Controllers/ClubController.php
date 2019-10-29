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
        $date = date('Y');

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
            
            if ($clubGameCount == 0) {
                $rank = ($score/1) * $won;
            } else {
                $rank = ($score/$clubGameCount) * $won;
            }
        	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank'=>$rank);
        	$i++;

            //$string .= " id: " . $clubMember->id . " : " . $gameCount . " gamesWon: ". $won . "//\\";
        }

        return view('taoex.editClubProfile', compact('club'), array('memberData'=>$memberData));
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
        // $user_table = new User;
        // $uid = Auth::user()->id;
        // $player_id = $request->player;
        // $club_id = $request->club_id;
        // $status = Auth::user()->approved_status;
        // $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        // $user_table->where('id', $player_id)->update(['approved_status'=>2, 'club_id'=>$club_id]);
        // return view('/home', array('message'=>'invitation has been successly sent, please wait for reply!', 'totalScore'=>$totalScore,
        //                             'color'=>'alert-success', 'status'=>Auth::user()->approved_status));
        // $user_table = new User;
        // $uid = Auth::user()->id;
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
        $clubs = $club_table->join('users', 'owner_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'Club.*')->get();

        // $userinvite = $user_table->leftJoin('Invite', 'users.id', '=', 'Invite.id')->distinct()->first();
        // $userinvitesub = $user_table->leftJoin('Invite', 'users.id', '=', 'Invite.id')->groupBy('users.id')->having('Invite.club_id', '!=', $club_id)
        //     ->orhaving('Invite.club_id', '!=', NULL)->distinct()->first();
        
        $allinvites = $user_table->leftJoin('Invite', 'users.id', '=', 'Invite.id')->get();
         
        // $rankings = $userinvite->leftJoin($userinvitesub, $userinvitesub->id, $userinvite->id)->leftJoin($userinvitesub, $userinvitesub->club_id, $userinvite->club_id)
        //     ->where($userinvitesub->club_id,NULL)->orwhere($userinvite->club_id,NULL)->get();
        
        // $rankings = DB::select(" select distinct u.firstname, u.lastname, u.id, i.club_id, u.score, u.image, u.image_type
        // FROM users as u
        // LEFT JOIN invite as i
        // ON u.id = i.id
        // LEFT JOIN ( select distinct u.firstname, u.lastname, u.id, i.club_id, u.score
        // FROM users as u
        // LEFT JOIN invite as i
        // ON u.id = i.id
        // GROUP BY u.id
        // HAVING i.club_id != $club_id
        // OR i.club_id != NULL) as t
        // ON t.id = i.id
        // AND t.club_id = i.club_id
        // WHERE i.club_id is NULL
        //     OR t.club_id is NULL
    
        // ORDER BY u.score DESC;");

        foreach($allinvites as $invites) {
            if ($invites->club_id == $club_id) {
                array_push($already_invited,$invites->id); 
            }
        }
        
       $rankings = $user_table->orderBy('score','desc')->get();
        // $rankings = $user_table->leftJoin('Invite', 'users.id', '=', 'Invite.id')->where('Invite.club_id',46)
        // ->orwhere('Invite.id', NULL)->orderBy('score','desc')->orderBy('users.id', 'asc')->distinct()->get();
        $playerCount = User::count();



        return view('taoex.playersearch')->with(['club_count'=> $club_count,
                                          'clubs' => $clubs, 'ranking'=> $rankings, 'player_count'=> $playerCount, 'club' => $club, 'already_invited' =>$already_invited]);
    }

}