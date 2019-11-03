<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// namespace App\Http\Controllers;
// use App\Match;
// use App\MatchResult;
// use App\User;
// use App\Club;
// use App\Clubuser;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        return view('taoex.policy');
    }

    public function sendMessage($uid,$message,$sender_id){
        DB::table('user_messages')->insert(['id'=>$uid,'message'=>$message,'sender'=>$sender_id]);
    }
}