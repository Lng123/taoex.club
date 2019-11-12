<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

 namespace App\Http\Controllers;
 use App\Match;
 use App\MatchResult;
 use App\User;
 use App\Club;
 use App\Clubuser;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('taoex.policy');
    }

    public function sendMessage($uid,$message,$sender_id){
        DB::table('user_messages')->insert(['id'=>$uid,'message'=>$message,'sender'=>$sender_id]);
        return redirect('/home');
    }

    public function deleteClub($club_id){
        DB::table('club')->where('id','=', $club_id)->delete();
        return redirect('/home/adminManageClub');
    }   

    public function banUser($ban_id){
        $admin_id = Auth::user()->id;
        DB::table('banned_users')->insert(['banned_id'=>$ban_id,'admin_id'=>$admin_id,'reason'=>'Banned']);
        return redirect('/home/adminBannedUsers');
    }

    public function unbanUser($ban_id){
        $admin_id = Auth::user()->id;
        DB::table('banned_users')->where('banned_id','=',$ban_id)->delete();
        return redirect('/home/adminBannedUsers');
    }
}