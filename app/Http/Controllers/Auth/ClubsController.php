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