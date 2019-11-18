<?php

namespace App\Http\Controllers;

use App\Match;
use App\MatchResult;
use App\User;
use App\Club;
use App\Clubuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $club_list = DB::table('UserClubs')->join('club', 'club.id', '=', 'UserClubs.club_id')->select('club.*')->where('UserClubs.id', $uid)->get();
        $all_clubs = DB::table('UserClubs')->join('club', 'club.id', '=', 'UserClubs.club_id')->select('club.*')->distinct()->get();


        //$clubusers = $clubuser_table->get();
        $total_score = $result_table->where('player_id', $uid)->sum('total');

        //$sumString = "";
        //for($i = 37; $i < 62; $i++) {
        //$total_score = $result_table->where('player_id', '=', $i)->sum('total');
        //$sumString .= "id: " . $i . "     sum: ". $total_score . "; \r\n";
        //}
        //return $sumString;

        //***** Number of players in the database ****
        $player_count = $user_table->orderBy('score', 'desc')->get()->count();
        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();
        //return $ranking;

        $users = $user_table->get();
        foreach ($users as $user) {
            $totalScore = $result_table->where('player_id', $user->id)->sum('total');
            User::where('id', $user->id)->update(array('score' => $totalScore));
        }



        //Session::put('totalScore', $totalScore);
        $club = $club_table->where('id', $club_id)->first();

        $club_list = DB::table('Club')->where('owner_id', $uid)->get();
        $club_list_in = DB::table('UserClubs')->join('club', 'club.id', '=', 'UserClubs.club_id')->select('club.*')->where('UserClubs.id', $uid)->get();
        $userClubID = Auth::user()->club_id;

        $userClubName = DB::table('Club')
            ->select(DB::raw('name'))
            ->where('id', $userClubID)
            ->get();

        $test = (string) $userClubName;

        $userMessages = DB::table('messages')
            ->select('message', 'message_id')
            ->where('club_name', $test)
            ->get();
        //$personal_messages = DB::table('user_messages')->join('users','users.id','=','user_messages.sender')->select('message','message_time','users.firstname')->get();
        $personal_messages = DB::table('user_messages')->join('users', 'users.id', '=', 'user_messages.sender')->select('user_messages.id', 'user_messages.sender', 'message', 'message_time', 'users.firstname', 'users.lastname')->where('user_messages.id', '=', $uid)->get();
        $sent_messages = DB::table('user_messages')->join('users', 'users.id', '=', 'user_messages.id')->select('user_messages.id', 'user_messages.sender', 'message', 'message_time', 'users.firstname', 'users.lastname')->where('user_messages.sender', '=', $uid)->get();
        $clubMembers = $user_table->get();
        $pending_invites = DB::table('Invite')->join('Club', 'Club.id', '=', 'Invite.club_id')->join('users', 'Club.owner_id', '=', 'users.id')->select('Invite.id', 'Invite.club_id', 'Club.name', 'Club.city', 'Club.province', 'users.firstname', 'users.lastname')->where('Invite.id', $uid)->get();

        $pending_applications = DB::table('club_application')
            ->select('club_application.user_id', 'users.firstname', 'users.lastname', 'users.city', 'users.province', 'club_application.club_id', 'club.name', 'club.owner_id')
            ->join('club', 'club_application.club_id', '=', 'club.id')
            ->join('users', 'users.id', '=', 'club_application.user_id')
            ->where('club_application.status', '=', 'applied')
            ->where('club.owner_id', '=', $uid)
            ->get();

        //$clubuser = $clubuser_table->where('user_id', Auth::user()->id)->first();
        //$club = $club_table->where('id', $clubuser->club_id)->first();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        return view('home', array('sent_messages' => $sent_messages, 'list_of_announcements' => $list_of_announcements, 'club_list_in' => $club_list_in, 'personal_messages' => $personal_messages, 'pending_invites' => $pending_invites, 'pending_club_applications' => $pending_applications, 'club_list' => $club_list, 'club' => $club,  'club_id' => $club_id, 'status' => $status, 'matches' => $matches, 'totalScore' => $total_score, 'ranking' => $ranking, 'userMessages' => $userMessages, 'results' => $results, 'clubMembers' => $clubMembers));
    }
    public function changeActiveClub($club_id)
    {
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;
        $uid = Auth::user()->id;
        $club_table = new Club;
        $clubuser_table = new Clubuser;
        $club_list = DB::table('Club')->where('owner_id', $uid)->get();
        $user_table->where('id', $uid)->update(['club_id' => $club_id]);
        $club = $club_table->where('id', $club_id)->first();
        $status = Auth::user()->approved_status;
        $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
        $total_score = $result_table->where('player_id', $uid)->sum('total');
        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();
        $userClubID = Auth::user()->club_id;

        $userClubName = DB::table('Club')
            ->select(DB::raw('name'))
            ->where('id', $userClubID)
            ->get();

        $test = (string) $userClubName;
        $userMessages = DB::table('messages')
            ->select('message', 'message_id')
            ->where('club_name', $test)
            ->get();
        $clubMembers = $user_table->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();

        return redirect('/home');
    }


    public function sendAnnouncement(Request $request)
    {

        $this->validate($request, [
            'announcement' => 'required'
        ]);

        $announcement = $request->input('announcement');
        $data = array(
            'announcement' => $announcement,
        );

        DB::table('announcements')->insert($data);

        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        return view('taoex.adminAnnouncement', array('list_of_announcements' => $list_of_announcements, 'announcement' => $announcement));
    }

    public function deleteAnnouncement($announcement, $time_sent)
    {
        DB::table('announcements')->where('announcement', '=', $announcement)->where('time_sent', '=', $time_sent)->delete();
        return redirect('/home/admin');
    }

    public function openAdmin()
    {

        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;

        $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;

        $status = Auth::user()->approved_status;
        $club_table = new Club;
        $clubuser_table = new Clubuser;


        $matches = $match_table->orderBy('endDate', 'desc')->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $clubMembers = $user_table->get();
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();

        return view('taoex.adminAnnouncement', array('list_of_announcements' => $list_of_announcements, 'matches' => $matches, 'results' => $results, 'clubMembers' => $clubMembers));
    }

    public function openClubAdmin()
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
        $club_list = DB::table('UserClubs')->join('club', 'club.id', '=', 'UserClubs.club_id')->select('club.*')->where('UserClubs.id', $uid)->get();
        $all_clubs = DB::table('UserClubs')->join('club', 'club.id', '=', 'UserClubs.club_id')->select('club.*')->distinct()->get();


        //$clubusers = $clubuser_table->get();
        $total_score = $result_table->where('player_id', $uid)->sum('total');

        //$sumString = "";
        //for($i = 37; $i < 62; $i++) {
        //$total_score = $result_table->where('player_id', '=', $i)->sum('total');
        //$sumString .= "id: " . $i . "     sum: ". $total_score . "; \r\n";
        //}
        //return $sumString;

        //***** Number of players in the database ****
        $player_count = $user_table->orderBy('score', 'desc')->get()->count();
        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();
        //return $ranking;

        $users = $user_table->get();
        foreach ($users as $user) {
            $totalScore = $result_table->where('player_id', $user->id)->sum('total');
            User::where('id', $user->id)->update(array('score' => $totalScore));
        }
        //Session::put('totalScore', $totalScore);
        $club = $club_table->where('id', $club_id)->first();

        //$club_list = DB::table('Club')->where('owner_id', $uid)->get();
        $club_list = DB::table('Club')
            ->select('Club.name', 'Club.id', 'Club.owner_id', 'Club.created_at', 'users.firstName', 'users.lastName')
            ->join('users', 'Club.owner_id', '=', 'users.id')
            ->get();

        $userClubID = Auth::user()->club_id;

        $userClubName = DB::table('Club')
            ->select(DB::raw('name'))
            ->where('id', $userClubID)
            ->get();

        $test = (string) $userClubName;

        $userMessages = DB::table('messages')
            ->select('message', 'message_id')
            ->where('club_name', $test)
            ->get();
        $clubMembers = $user_table->get();

        //$clubuser = $clubuser_table->where('user_id', Auth::user()->id)->first();
        //$club = $club_table->where('id', $clubuser->club_id)->first();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();

        return view('taoex.adminClubBrowser', array('club_list' => $club_list, 'club' => $club,  'club_id' => $club_id, 'status' => $status, 'matches' => $matches, 'totalScore' => $total_score, 'ranking' => $ranking, 'userMessages' => $userMessages, 'results' => $results, 'clubMembers' => $clubMembers));
    }

    public function openUserAdmin()
    {
        $club_table = new Club;
        $club_count = Club::count();
        $clubs = $club_table->join('users', 'owner_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'Club.*')->get();
        $banned_users = DB::table('banned_users')->pluck('banned_id')->all();
        $rankings = DB::table('users')->select('*')->whereNotIn('id', $banned_users)->orderBy('score', 'desc')->get();
        $playerCount = User::count();



        return view('taoex.adminUserBrowser')->with([
            'club_count' => $club_count,
            'clubs' => $clubs, 'ranking' => $rankings, 'player_count' => $playerCount
        ]);
    }

    public function editName(Request $request)
    {
        $user_table = new User;
        $id = $request->id;
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $user_table->where('id', $id)->update(['firstname' => $firstname]);
        $user_table->where('id', $id)->update(['lastname' => $lastname]);
        return redirect('/home/adminManageUser');
    }

    public function openBannedUsers()
    {
        $banned_users = DB::table('banned_users')->join('users', 'users.id', '=', 'banned_users.banned_id')->select('*')->get();
        return view('taoex.adminBannedUsers')->with([
            'bannedUsers' => $banned_users
        ]);
    }




    public function openAdminMessage($id)
    {
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $fullname = "{$name} {$lname}";
        return view('taoex.adminSendMessage', array('fullname' => $fullname, 'id' => $id, 'list_of_announcements' => $list_of_announcements));
        //return view('taoex.adminSendMessage', array('id'=>$id,'sender'=>$sender));
    }

    public function deleteUserAdmin($id)
    {
        $user_table = new User;
        $match_table = new Match;
        $result_table = new MatchResult;

        $remove_matchresult = $result_table->where('player_id', $id)->delete();
        $remove_invite = DB::table('Invite')->where('id', $id)->delete();
        $remove_match = $match_table->where('winner_id', $id)->update(['winner_id' => NULL]);
        $remove = $user_table->where('id', $id)->delete();

        return redirect('/home/adminManageUser');
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

    public function record(Request $request)
    {
        $result_table = new MatchResult;

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

        $elimination = $request->elimination;
        $capture = $request->capture;
        $hook = $request->hook;
        $winBonus = $request->winBonus;

        if ($winBonus == 0) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI;
            $winBonusR = 0;
        } else if ($winBonus == 5) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                + $LEB * $numberPlayers + 6 * $numberPlayers;
            $winBonusR = $LEB * $numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id' => $player_id]);
        } else if ($winBonus == 6) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                + 6 * $numberPlayers;
            $winBonusR =  6 * $numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id' => $player_id]);
        } else {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                + $ART + 6 * $numberPlayers;
            $winBonusR = $ART;
            $match_table->where('id', $match_id)->update(['winner_id' => $player_id]);
        }

        $check = $match_result->where('player_id', $player_id)->where('match_id', $match_id)->get()->count();
        $totalScore = DB::table('MatchResult')->where('player_id', Auth::user()->id)->sum('total');
        if ($check != 0) {
            return view('home', array(
                'message' => 'You can only insert on recorder for same player at same match!', 'color' => 'alert-danger',
                'totalScore' => $totalScore
            ));
        }

        $match_result->match_id = $match_id;
        $match_result->player_id = $player_id;
        $match_result->elimination = $elimination;
        $match_result->capture = $capture;
        $match_result->hook = $hook;
        $match_result->winBonus = $winBonusR;
        $match_result->total = $total;
        $match_result->place = 0;

        $match_result->save();


        $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
        $user_table = new User;

        $total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();

        $userMessages = DB::table('messages')
            ->select('message', 'message_id')
            ->get();


        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();

        $matches = $match_table->orderBy('endDate', 'desc')->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $clubMembers = $user_table->get();


        $recordSuccess = 1;


        return view('taoex.admin', array('matches' => $matches, 'results' => $results, 'clubMembers' => $clubMembers, 'recordSuccess' => $recordSuccess));
    }




    public function editMatch(Request $request)
    {
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;
        $resultTarget = $request->resultName;
        $hook = $request->hook;
        $capture = $request->capture;
        $elimination = $request->elimination;
        $winBonus = $request->winBonus;
        MatchResult::where('id', $resultTarget)->update(array('hook' => $hook, 'capture' => $capture, 'elimination' => $elimination, 'winBonus' => $winBonus, 'total' => $hook + $capture + $elimination + $winBonus));
        $uid = Auth::user()->id;
        $total_score = $result_table->where('player_id', $uid)->sum('total');


        $matches = $match_table->orderBy('endDate', 'desc')->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $clubMembers = $user_table->get();
        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();

        $editSuccess = 1;
        $match_id =  $result_table->where('id', $resultTarget)->get();

        $club_id = getClubId($match_id);
        updateCScore($club_id);
        dd($match_id);

        return view('taoex.admin', array('matches' => $matches, 'results' => $results, 'clubMembers' => $clubMembers, 'ranking' => $ranking, 'editSuccess' => $editSuccess));
    }

    public function getClubId($match_id)
    {
        $match_table = new Match;

        $club_id = $match_table->where('match_id', $match_id)->select('club_id');

        return $club_id;
    }

    public function updateCScore($club_id)
    {
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
        //Obtain club id
        //$club_id = Auth::user()->club_id;
        //Obtain status
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;

        $clubGames = $match_table->where('club_id', $club_id)->get();

        $club = $club_table->where('id', $club_id)->first();

        $clubMembers = DB::table('UserClubs')->join('users', 'users.id', '=', 'UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();

        $string = "";

        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
        $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->get()->count();

        $memberData = [];
        $i = 0;
        $total_score = 0;

        foreach ($clubMembers as $clubMember) {

            $gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->where('player_id', $clubMember->id)->get()->count();

            $won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->where('player_id', $clubMember->id)->where('winner_id', $clubMember->id)->get()->count();

            $score = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->where('player_id', $clubMember->id)->sum('total');

            if ($clubGameCount == 0) {
                $rank = ($score / 1) * $won;
            } else {
                $rank = ($score / $clubGameCount) * $won;
            }
            $total_score += rank;
            $memberData[$i] = array('name' => $clubMember->firstName . " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank' => $rank);
            $i++;
        }
        $club_table->where('id', $club_id)->update(['club_score' => ranking]);
    }
}
