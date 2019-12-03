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
        $user_table = new User;
        $match_table = new Match;
        $result_table = new MatchResult;
        
        $remove_invite = DB::table('Invite')->where('club_id', $club_id)->delete();
        $remove_application = DB::table('club_application')->where('club_id', $club_id)->delete();
        
        $matches_to_remove = $match_table->where('club_id', $club_id)->get();
        foreach ($matches_to_remove as $m) {
            $mid = $m->id;
            $remove_matchresult = $result_table->where('match_id', $mid)->delete();
        }
        $remove_match = $match_table->where('club_id', $club_id)->delete();
        
        $remove_cid_user = $user_table->where('club_id', $club_id)->update(['club_id' => NULL]);
        
        $remove_user_clubs = DB::table('UserClubs')->where('club_id',$club_id)->delete();
        
        DB::table('Club')->where('id','=', $club_id)->delete();
        return redirect('/home/adminManageClub');
    }   

    public function banUser($id){
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $fullname = "{$name} {$lname}";
        $banned_users = DB::table('banned_users')->join('users', 'users.id', '=', 'banned_users.banned_id')->select('*')->get();
        return view('taoex.adminBannedUsers', array('fullname' => $fullname, 'ban_id' => $id, 'list_of_announcements' => $list_of_announcements,'bannedUsers' =>$banned_users));
        //return redirect('taoex.adminBannedUsers');
        //return view('taoex.adminSendMessage', array('id'=>$id,'sender'=>$sender));
    }


    public function submitUserBan(Request $request){
        $ban_id = $request->ban_id;
        $message = $request ->input('message');
        $admin_id = Auth::user()->id;
        
        DB::table('banned_users')->insert(['banned_id'=>$ban_id,'admin_id'=>$admin_id,'reason'=>$message]);
        return redirect('home/adminBannedUsers')->with('status','User has been banned successfully');
    }

    public function unbanUser($ban_id){
        $admin_id = Auth::user()->id;
        DB::table('banned_users')->where('banned_id','=',$ban_id)->delete();
        return redirect('/home/adminBannedUsers');
    }

    
    public function deleteMatch($match_id){
        //$match_id= $request->match_id;
        $admin_id = Auth::user()->id;
        DB::table('MatchResult')->where('match_id','=',$match_id)->delete();
        DB::table('Match')->where('id','=',$match_id)->delete();
        return redirect('/home/matchHistory');
    }

    public function deleteMatchRecord($match_record_id){
      
        $admin_id = Auth::user()->id;
        $match_id = DB::table('MatchResult')->where('id',$match_record_id)->select('match_id')->value('match_id');
        $deleted_user_record = DB::table('MatchResult')->where('id','=',$match_record_id)->select('player_id')->value('player_id');
        DB::table('MatchResult')->where('id','=',$match_record_id)->delete();
        $winner_id = DB::table('Match')->where('id', $match_id)->select('winner_id')->value('winner_id');
        if($winner_id == $deleted_user_record ){
            DB::table('Match')->where('id', $match_id)->update(['winner_id' => NULL]);
        }
        
        //DB::table('match')->where('id','=',$match_id)->delete();
        return redirect('/home/matchHistory');
    }
}