<?php
/**
 * ClubsController
 * Club management functions for admin level users
 */
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

class ClubsController extends Controller
{
    public function index($club_id) {
        $clubs = DB::table('club')
            ->select('id', 'name')
            ->get();
        $clubMembers = DB::table('UserClubs')
            ->join('users','users.id','=','UserClubs.id')
            ->select('*')
            ->where('UserClubs.club_id', $club_id)
            ->get();
        $memberData = [];
        $clubData = [];

        foreach ($clubMembers as $clubMember) {       	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'id' => $clubMember->id);
        	$i++;
        }

        foreach ($clubs as $club) {
            $clubData[$i] = array('club_id' => $clubs->id, 'club_name' => $clubs->name);
            $i++;
        }

        return view('taoex.adminManageClubMembers', array('memberData'=>$memberData, 'clubData'=>$clubData, 'club_owner'=>$club->owner_id));
    }
}