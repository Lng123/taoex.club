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

class MessageController extends Controller
{
    public function index()
    {
        return view('taoex.policy');
    }

    public function sendMessage($uid,$message,$sender_id){
        DB::table('user_messages')->insert(['id'=>$uid,'message'=>$message,'sender'=>$sender_id]);
        return redirect('/home');
    }

    public function deleteMessage($uid,$sender_id,$message_time){
        DB::table('user_messages')->where('id','=', $uid)->where('message_time','=', $message_time)->where('sender', '=', $sender_id)->delete();
        return redirect('/home');
    }   

    public function sendMessageRequest(Request $request){
        $uid = $request->id;
        $message = $request->message;
        $sender_id = $request->sender;
        DB::table('user_messages')->insert(['id'=>$uid,'message'=>$message,'sender'=>$sender_id]);
        return redirect('/home');
    }

    public function sendAdminMessage(Request $request){
        $receiver_id = $request->id;
        $message = $request ->input('message');
        $sender_id = Auth::user()->id;
        DB::table('user_messages')->insert(['id'=>$receiver_id,'message'=>$message,'sender'=>$sender_id]);
        return redirect('home/adminManageUser');
    }
}