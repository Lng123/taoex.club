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
    /**
     * Sends an administrator message directly to a user, tagged with [Admin]
     */
    public function sendAdminMessage(Request $request){
        $receiver_id = $request->id;
        $message = $request ->input('message');
        $sender_id = Auth::user()->id;
        DB::table('user_messages')->insert(['id'=>$receiver_id,'message'=>$message,'sender'=>$sender_id,'message_tag'=>'[Admin]']);
        return redirect('home/adminManageUser')->with('status','Message sent successfully');
    }

    /**
     * Sends a admin message through clubs to a user 
     */
    public function sendClubMemberMessage(Request $request) {
        $receiver_id = $request->id;
        $message = $request->input('message');
        $sender_id = Auth::user()->id;
        $tag = "[Club Owner]";
        DB::table('user_messages')->insert(['id'=>$receiver_id, 'message'=>$message, 'sender'=>$sender_id, 'message_tag'=>$tag]);
        return redirect()->route('manageClub');
    }
    /**
     * Replies to a send message with the tag reply
     */
    public function replyMessage($uid,$sender_id,$message_time){
        $init_message = DB::table('user_messages')->where('id','=', $uid)->where('message_time','=', $message_time)->where('sender', '=', $sender_id)->get();
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $sender_id)->value('firstname');
        $lname = DB::table('users')->where('id', $sender_id)->value('lastname');
        $fullname = "{$name} {$lname}";
        return view('taoex.replyMessage', array('fullname' => $fullname, 'id' => $sender_id, 'list_of_announcements' => $list_of_announcements));
    }

    public function sendReplyMessage(Request $request) {
        $receiver_id = $request->id;
        $message = $request->input('message');
        $sender_id = Auth::user()->id;
        $tag = "[Reply]";
        DB::table('user_messages')->insert(['id'=>$receiver_id, 'message'=>$message, 'sender'=>$sender_id, 'message_tag'=>$tag]);
        return redirect()->route('home');
    }
}