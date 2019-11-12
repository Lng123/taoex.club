<?php
/**
 * ClubsController
 * Club management functions for admin level users
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

class ClubsController extends Controller
{
    public function adminManageMembers($club_id) {
        $club_table = new Club;
        $clubMembers = DB::table('UserClubs')
            ->join('users','users.id','=','UserClubs.id')
            ->select('*')
            ->where('UserClubs.club_id', $club_id)
            ->get();
        $currentClub = $club_table->where('id', $club_id)->first();
        $memberData = [];
        $clubData = [];
        $all_clubs = $club_table->get();
        $i = 0;
        foreach ($clubMembers as $clubMember) {       	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'id' => $clubMember->id);
        	$i++;
        }
        $j = 0;
        foreach ($all_clubs as $clubber) {
            $clubData[$j] = array('club_id' => $clubber->id, 'club_name' => $clubber->name);
            $j++;
        }

        return view('taoex.adminManageClubMembers', array('memberData'=>$memberData, 'club_owner'=>$currentClub->owner_id, 'clubData'=>$clubData, 'club_id'=>$club_id, 'currentClub'=>$currentClub));
    }

    public function adminRemoveMember($club_id, $id)
    {
        User::where('id', $id)->where('club_id', $club_id)->update(['club_id' => null]);
        DB::table('userclubs')->where('id', $id)->where('club_id', $club_id)->delete();
        return redirect()->route('manageClubMembers', ['club_id'=>$club_id]);
    }

    public function updateClubMembers(Request $request) {
        $club_id = $request->select_id;
        $club_table = new Club;
        $clubMembers = DB::table('UserClubs')
            ->join('users','users.id','=','UserClubs.id')
            ->select('*')
            ->where('UserClubs.club_id', $club_id)
            ->get();
        $currentClub = $club_table->where('id', $club_id)->first();
        $memberData = [];
        $clubData = [];
        $all_clubs = $club_table->get();
        $i = 0;
        foreach ($clubMembers as $clubMember) {       	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'id' => $clubMember->id);
        	$i++;
        }
        $j = 0;
        foreach ($all_clubs as $clubber) {
            $clubData[$j] = array('club_id' => $clubber->id, 'club_name' => $clubber->name);
            $j++;
        }

        return view('taoex.adminManageClubMembers', array('memberData'=>$memberData, 'club_owner'=>$currentClub->owner_id, 'clubData'=>$clubData, 'club_id'=>$club_id));
    }
}