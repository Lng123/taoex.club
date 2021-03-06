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

        if ($club_id != null) {
            $club = $club_table->where('id', $club_id)->first();
            $clubMembers = DB::table('UserClubs')->join('users', 'users.id', '=', 'UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
            $nearPlayers = $user_table->where('approved_status', 0)->get();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
            $club_score = $club_table->join('users', 'Club.id', '=', 'users.club_id')->select("club_score")->where('users.id', $uid)->get();

            $clubOwner = $clubMembers->where('id', $club->owner_id)->first();

            $numberMembers = $clubMembers->count();

            $clubGames = $match_table->where('club_id', $club_id)->get();

            $club = $club_table->where('id', $club_id)->first();

            $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();


            $total_score = $result_table->where('player_id', $uid)->sum('total');

            $ranking = $user_table->where('score', '>=', $total_score)->get()->count();


            $userClubID = Auth::user()->club_id;

            $club_name = DB::table('users')->select('name')->join('Club', 'Club.id', '=', 'users.club_id')->where('users.id', $uid)->value('name');

            $club_messages = DB::table('messages')
                ->select('message', 'message_id', 'club_name')
                ->where('club_name', $club_name)
                ->get();
            return view('taoex.club', array('club_messages' => $club_messages, 'club' => $club, 'clubMembers' => $clubMembers, 'matches' => $matches, 'allPlayers' => $allPlayers, 'numberMembers' => $numberMembers, 'allMatches' => $allMatches, 'clubOwner' => $clubOwner, 'totalScore' => $totalScore, 'clubScore' => $club_score));
        }
        if ($club_id == null) {
            return redirect()->route("home")->withErrors(['You currently do not belong to any club! Please join a club or create one.', 'You currently not belong to any Club! Please Join a Club or Create one.']);
        }
    }
    /**
     * Displays the club members with their scores for the selected year.
     */
    public function clubMemberRanking(Request $request)
    {

        $date = $request->year;
        $club_id = $request->club_id;

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
        $clubGameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')
            ->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->get()->count();

        $memberData = [];
        $i = 0;
        $total_score = 0;
        $rank = 0;
        foreach ($clubMembers as $clubMember) {

            $gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->where('player_id', $clubMember->id)->get()->count();

            $won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->where('player_id', $clubMember->id)->where('winner_id', $clubMember->id)->get()->count();
            $sDate  = $date . "-01-1";
            $eDate = $date . "-12-31";
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

            if ($score == NULL) {
                $score = 0;
            }
            $total_score += $score;
            $memberData[$i] = array('name' => $clubMember->firstName . " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank' => $rank);
            $i++;
        }
        $ranksort = array_column($memberData, 'score');

        array_multisort($ranksort, SORT_DESC, $memberData);
        $total_score = $total_score / $i;
        $total_score = round($total_score);
        return view('taoex.clubFilter', array('memberData' => $memberData, 'total_score' => $total_score, 'club_id' => $club_id, 'date' => $date));
    }

    public function showNewClubForm()
    {
        $nearPlayers = DB::table('users')->where('approved_status', 0)->get();
        return view('taoex.applyForClub', array('nearPlayers' => $nearPlayers));
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
        $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        $club_list = DB::table('UserClubs')->join('Club', 'Club.id', '=', 'UserClubs.club_id')->select('Club.*')->where('UserClubs.id', $uid)->get();
        $all_clubs = DB::table('UserClubs')->join('Club', 'Club.id', '=', 'UserClubs.club_id')->select('Club.*')->distinct()->get();


        $total_score = $result_table->where('player_id', $uid)->sum('total');

        //***** Number of players in the database ****
        $player_count = $user_table->orderBy('score', 'desc')->get()->count();
        $ranking = $user_table->where('score', '>=', $total_score)->get()->count();

        $users = $user_table->get();
        foreach ($users as $user) {
            $totalScore = $result_table->where('player_id', $user->id)->sum('total');
            User::where('id', $user->id)->update(array('score' => $totalScore));
        }
        $club = $club_table->where('id', $club_id)->first();
        $applied_inclub = DB::table('Club')
            ->select('Club.name', 'Club.id', 'Club.club_score', 'Club.owner_id', 'Club.created_at', 'users.id as user_id', 'users.firstName', 'users.lastName', 'club_application.status')
            ->join('users', 'Club.owner_id', '=', 'users.id')
            ->leftjoin('club_application', function ($join) {
                $join->on('Club.id', '=', 'club_application.club_id');
            })
            ->where('club_application.user_id', '=', $uid)
            ->orderBy('Club.id');



        $applied_inclub2 = DB::table('Club')
            ->select('Club.name', 'Club.id', 'Club.club_score', 'Club.owner_id', 'Club.created_at', 'users.id as user_id', 'users.firstName', 'users.lastName',  DB::raw("'' as status"))
            ->join('users', 'Club.owner_id', '=', 'users.id')
            ->whereNotIn('Club.id', function ($q) use ($uid) {
                $q->select('Club.id')
                    ->from('Club')
                    ->join('users', 'Club.owner_id', '=', 'users.id')
                    ->leftjoin('club_application', function ($join) {
                        $join->on('Club.id', '=', 'club_application.club_id');
                    })
                    ->where('club_application.user_id', '=', $uid)
                    ->orderBy('Club.id');
            })
            ->orderBy('Club.id');

        $club_list = ($applied_inclub)->union($applied_inclub2)->distinct()->get();

        $userClubID = Auth::user()->club_id;

        $club_name = DB::table('users')->select('name')->join('Club', 'Club.id', '=', 'users.club_id')->where('users.id', $uid)->value('name');

        $userMessages = DB::table('messages')
            ->select('message', 'message_id', 'club_name')
            ->where('club_name', $club_name)
            ->get();
        $clubMembers = $user_table->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();

        return view('taoex.clubBrowser', array('club_list' => $club_list, 'club' => $club,  'club_id' => $club_id, 'status' => $status, 'matches' => $matches, 'totalScore' => $total_score, 'ranking' => $ranking, 'userMessages' => $userMessages, 'results' => $results, 'clubMembers' => $clubMembers));
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

        $clubMembers = DB::table('UserClubs')->join('users', 'users.id', '=', 'UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();
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


        $string = "";

        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
        $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date . "-01-1")->where('endDate', '<=', $date . "-12-31")->get()->count();

        $memberData = [];
        $i = 0;

        foreach ($clubMembers as $clubMember) {

            $memberData[$i] = array('name' => $clubMember->firstName . " " . $clubMember->lastName, 'id' => $clubMember->id);
            $i++;
        }

        return view('taoex.manageClub', compact('club'), array('memberData' => $memberData, 'club_owner' => $club->owner_id));
    }

    public function removeClubMember($id)
    {
        $club_id = Auth::user()->club_id;
        User::where('id', $id)->where('club_id', $club_id)->update(['club_id' => null]);
        DB::table('UserClubs')->where('id', $id)->where('club_id', $club_id)->delete();
        $club_name = DB::table('Club')->where('id', $club_id)->value('name');
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $message = "{$name} {$lname} has been kicked from {$club_name}";
        $club_owner_id = DB::table('Club')->where('id', $club_id)->value('owner_id');
        DB::table('club_application')->where('user_id', $id)->where('club_id', $club_id)->where('status', 'inClub')->delete();
        DB::table('user_messages')->insert(['id' => $id, 'message' => $message, 'sender' => $club_owner_id]);
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
        if ($request->hasFile('image')) {
            $image = Utility::get_image_fromFile($request->file('image'));
        } else {
            $image = Utility::get_image_fromTable($request->id, 'Club');
        }
        /* update image to database */
        try {
            $clubinfo = Club::findOrFail($request->id);
            $clubinfo->update([
                'name' => $request->Name,
                'image' => $image['data'],
                'image_type' => $image['type'],
                /*'country'=> $request->country,*/
                'province' => $request->province,
                'city' => $request->city,
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return ['error' => 'error: modify club image fail (001)'];
        }

        return redirect()->route("club");
    }

    public function validateForm(Request $request)
    {
        $clubName = $request->clubName;
        $nearPlayers = DB::table('users')->where('approved_status', 0)->get();
        if (!isset($clubName)) {
            return view('/home/newclub', array('message' => 'Please Enter Your Club Name!'));
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
            return view('home', array('message' => 'Please Enter Your Club Name!', 'nearPlayers' => $nearPlayers, 'club' => $club, 'color' => 'alert-danger', 'ranking' => $ranking, 'totalScore' => $totalScore));
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
        $user_table->where('id', $uid)->update(['type' => 1, 'approved_status' => 1, 'club_id' => $club_id, 'club_owner' => 1]);


        $club_list = DB::table('Club')->where('owner_id', $uid)->get();

        $match_table = new Match();
        $result_table = new MatchResult;
        $matches = $match_table->where('club_id', $club_d)->orderBy('endDate', 'desc')->take(3)->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        DB::table('UserClubs')->insert(['id' => $uid, 'club_id' => $club_id]);
        DB::table('club_application')->insert(['user_id' => $uid, 'club_id' => $club_id, 'status' => 'inClub']);
        return redirect('/home/club');
    }
    /**
     * Executes when the invite button of a player is clicked.
     * Uses the player id of the player invited and selected club id and inserts a row in the invite table.
     * Returns playersearch to refresh the status of the invite button.
     */
    public function invite(Request $request)
    {
        $userid = $request->input('ranking');
        $uid = (int) $userid;
        $club_id = Auth::user()->club_id;
        DB::table('Invite')->insert(['id' => $uid, 'club_id' => $club_id]);
        return $this->playersearch();
    }

    public function playerApply(Request $request)
    {

        $user_id = Auth::user()->id;
        $club_id = $request->input('club_id');
        DB::table('club_application')->insert(['user_id' => $user_id, 'club_id' => $club_id, 'status' => 'applied']);
        return $this->showAllClub();
    }


    public function acceptInvitation($club_id)
    {
        $uid = Auth::user()->id;
        DB::table('users')->where('id', $uid)->update(['approved_status' => 1]);

        $ranking = 0;
        $club_list = DB::table('UserClubs')->join('Club', 'Club.id', '=', 'UserClubs.club_id')->select('Club.*')->where('UserClubs.id', $uid)->get();
        $userClubID = Auth::user()->club_id;
        DB::table('Invite')->where('id', '=', $uid)->where('club_id', '=', $club_id)->delete();
        DB::table('UserClubs')->insert(['id' => $uid, 'club_id' => $club_id]);
        $userClubName = DB::table('Club')
            ->select(DB::raw('name'))
            ->where('id', $userClubID)
            ->get();
        DB::table('users')->where('id', $uid)->update(['club_id' => $club_id]);
        $test = (string) $userClubName;
        $messages = DB::table('messages')
            ->select('message', 'message_id')
            ->where('club_name', $test)
            ->get();


        //change the status of a user status to be inclub when the user accepts invitation
        if (DB::table('club_application')->select('user_id')->where('user_id', $uid)->where('club_id', $club_id)->get()->isEmpty()) {
            DB::table('club_application')->insert(['user_id' => $uid, 'club_id' => $club_id, 'status' => 'inClub']);
        } else {
            DB::table('club_application')->where('user_id', $uid)->where('club_id', $club_id)->update(['status' => 'inClub']);
        }

        return redirect('/home/club');
    }


    public function declineInvitation(Request $request)
    {
        $uid = Auth::user()->id;
        $status = Auth::user()->approved_status;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        DB::table('users')->where('id', $uid)->update(['approved_status' => 0, 'club_id' => NULL]);
        DB::table('Invite')->where('id', '=', $uid)->where('club_id', '=', $id)->delete();
        return view('/home', array('color' => 'alert-success', 'message' => 'You have declined the invitation', 'totalScore' => $totalScore, 'status' => Auth::user()->approved_status));
    }


    public function acceptClubApplication($applicant_id, $club_id)
    {
        DB::table('UserClubs')->insert(['id' => $applicant_id, 'club_id' => $club_id]);
        DB::table('users')->where('id', $applicant_id)->update(['club_id' => $club_id]);
        DB::table('club_application')->where('user_id', $applicant_id)->where('club_id', $club_id)->where('status', 'applied')->update(['status' => 'inClub']);
        return redirect('/home/club');
    }

    public function declineClubApplication($applicant_id, $club_id)
    {
        $clubinfo = DB::table('Club')->select('owner_id', 'name')->where('id', $club_id)->get();
        $applicant = DB::table('users')->select('firstname', 'lastname')->where('id', $applicant_id)->get();
        //Message
        $message = "{$applicant[0]->firstname}.{$applicant[0]->lastname}'s club application to {$clubinfo[0]->name} has been declined.";
        DB::table('user_messages')->insert(['id' => $applicant_id, 'message' => $message, 'sender' => $clubinfo[0]->owner_id]);

        DB::table('club_application')->where('user_id', $applicant_id)->where('club_id', $club_id)->where('status', 'applied')->delete();
        //return redirect('/home/club');

        return redirect('/home');
    }

    public function sendMessagePage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $uid = Auth::user()->id;
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        $club_name = DB::table('users')->select('name')->join('Club', 'Club.id', '=', 'users.club_id')->where('users.id', $uid)->value('name');
        $message = $request->input('message');
        $ranking = 0;
        $club_list = DB::table('UserClubs')->join('Club', 'Club.id', '=', 'UserClubs.club_id')->select('Club.*')->where('UserClubs.id', $uid)->get();
        $userClubID = Auth::user()->club_id;

        $messages = DB::table('messages')
            ->select('message', 'message_id')
            ->where('club_name', $club_name)
            ->get();


        $pending_invites = DB::table('Invite')->join('Club', 'Club.id', '=', 'Invite.club_id')->join('users', 'Club.owner_id', '=', 'users.id')->select('Invite.id', 'Invite.club_id', 'Club.name', 'Club.city', 'Club.province', 'users.firstname', 'users.lastname')->where('Invite.id', $uid)->get();

        $pending_applications = DB::table('club_application')
            ->select('club_application.user_id', 'users.firstname', 'users.lastname', 'users.city', 'users.province', 'club_application.club_id', 'Club.name', 'Club.owner_id')
            ->join('Club', 'club_application.club_id', '=', 'Club.id')
            ->join('users', 'users.id', '=', 'club_application.user_id')
            ->where('club_application.status', '=', 'applied')
            ->where('Club.owner_id', '=', $uid)
            ->get();

        DB::table('messages')->insert(['club_name' => $club_name, 'message' => $message]);
        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        return redirect('/home/club');
    }

    /**
     * Loads the invite page for club that the user is currently in. 
     * Displays the players in the system with invite buttons. 
     * Greys out players that are already in the club or invited.
     */
    public function playersearch()
    {
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
        $usersinclubs = DB::table('UserClubs')->where('club_id', $club_id)->get();

        foreach ($usersinclubs as $cmembers) {
            array_push($club_members, $cmembers->id);
        }


        foreach ($allinvites as $invites) {
            if ($invites->club_id == $club_id) {
                array_push($already_invited, $invites->id);
            }
        }

        $rankings = $user_table->orderBy('score', 'desc')->get();

        $playerCount = User::count();



        return view('taoex.playersearch')->with([
            'club_count' => $club_count,
            'clubs' => $clubs, 'ranking' => $rankings, 'player_count' => $playerCount, 'club' => $club,
            'already_invited' => $already_invited,
            'club_members' => $club_members
        ]);
    }


    public function adminManageMembers($club_id)
    {
        $clubs = new Club;
        $clubMembers = DB::table('UserClubs')
            ->join('users', 'users.id', '=', 'UserClubs.id')
            ->select('*')
            ->where('UserClubs.club_id', $club_id)
            ->get();
        $currentClub = $clubs->where('id', $club_id)->first();
        $memberData = [];
        $clubData = [];
        $i = 0;
        foreach ($clubMembers as $clubMember) {
            $memberData[$i] = array('name' => $clubMember->firstName . " " . $clubMember->lastName, 'id' => $clubMember->id);
            $i++;
        }
        $i = 0;
        foreach ($clubs as $club) {
            $clubData[$i] = array('club_id' => $clubs->id, 'club_name' => $clubs->name);
            $i++;
        }

        return view('taoex.adminManageClubMembers', array('memberData' => $memberData, 'clubData' => $clubData, 'club_owner' => $currentClub->owner_id, 'club_id' => $club_id));
    }

    public function adminRemoveMember($club_id, $id)
    {
        User::where('id', $id)->where('club_id', $club_id)->update(['club_id' => null]);
        DB::table('UserClubs')->where('id', $id)->where('club_id', $club_id)->delete();
        return redirect()->route('manageClubMembers', ['club_id' => $club_id]);
    }

    public function adminChangeClubOwner($club_id, $id)
    {
        $clubs = new Club();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $clubs->where('id', $club_id)->update(['owner_id' => $id]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->route('manageClubMembers', ['club_id' => $club_id]);
    }

    public function changeClubOwner($id)
    {
        $clubs = new Club();
        $club_id = Auth::user()->club_id;
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $clubs->where('id', $club_id)->update(['owner_id' => $id]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect('/home/club');
    }

    public function openClubOwnerMessage($id)
    {
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $fullname = "{$name} {$lname}";
        return view('taoex.clubOwnerSendMessage', array('fullname' => $fullname, 'id' => $id, 'list_of_announcements' => $list_of_announcements));
    }
}
