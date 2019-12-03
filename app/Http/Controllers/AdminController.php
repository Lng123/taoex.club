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

    
    public function deleteUserAdmin($id)
    {
        $user_table = new User;
        $match_table = new Match;
        $result_table = new MatchResult;

        $remove_matchresult = $result_table->where('player_id', $id)->delete();
        $remove_invite = DB::table('Invite')->where('id', $id)->delete();
        $remove_match = $match_table->where('winner_id', $id)->update(['winner_id' => NULL]);
        $remove_messages = DB::table('user_messages')->where('id',$id)->delete();
        $remove_sent_messages = DB::table('user_messages')->where('sender',$id)->delete();
        $remove_user_clubs = DB::table('UserClubs')->where('id',$id)->delete();
        $remove = $user_table->where('id', $id)->delete();
        return redirect('/home/adminManageUser');
    }

    public function sendMessage($uid,$message,$sender_id){
        DB::table('user_messages')->insert(['id'=>$uid,'message'=>$message,'sender'=>$sender_id]);
        return redirect('/home');
    }

    /**
     * Deletes the given club.  This also deletes any match and match records related
     * to this club, sets any user's active club_id that shares the given club id into
     * null from the users table and deletes it from the UserClubs table before
     * actually deleting the club from the clubs table.
     *
     * @return admin club page
     */
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
    //opens the ban page
    public function banUser($id){
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $fullname = "{$name} {$lname}";
        $banned_users = DB::table('banned_users')->join('users', 'users.id', '=', 'banned_users.banned_id')->select('*')->get();
        return view('taoex.adminBannedUsers', array('fullname' => $fullname, 'ban_id' => $id, 'list_of_announcements' => $list_of_announcements,'bannedUsers' =>$banned_users));
    }

    //submits a ban with a message 
    public function submitUserBan(Request $request){
        $ban_id = $request->ban_id;
        $message = $request ->input('message');
        $admin_id = Auth::user()->id;
        
        DB::table('banned_users')->insert(['banned_id'=>$ban_id,'admin_id'=>$admin_id,'reason'=>$message]);
        return redirect('home/adminBannedUsers')->with('status','User has been banned successfully');
    }

    //lifts the ban on a user by deleting the matching id on the ban table 
    public function unbanUser($ban_id){
        $admin_id = Auth::user()->id;
        DB::table('banned_users')->where('banned_id','=',$ban_id)->delete();
        return redirect('/home/adminBannedUsers');
    }

    //deletes the entire match
    public function deleteMatch($match_id){
        
        $admin_id = Auth::user()->id;
        DB::table('MatchResult')->where('match_id','=',$match_id)->delete();
        DB::table('Match')->where('id','=',$match_id)->delete();
        return redirect('/home/matchHistory');
    }

        // deletes a match record (it's a subset within a match) and the winner needs to be set to null
    public function deleteMatchRecord($match_record_id){

      
        $admin_id = Auth::user()->id;
        $match_id = DB::table('MatchResult')->where('id',$match_record_id)->select('match_id')->value('match_id');
        $deleted_user_record = DB::table('MatchResult')->where('id','=',$match_record_id)->select('player_id')->value('player_id');
        DB::table('MatchResult')->where('id','=',$match_record_id)->delete();
        $winner_id = DB::table('Match')->where('id', $match_id)->select('winner_id')->value('winner_id');
        if($winner_id == $deleted_user_record ){
            DB::table('Match')->where('id', $match_id)->update(['winner_id' => NULL]);
        }
        return redirect('/home/matchHistory');
    }

    //inserts an announcement into the announcement table, only a message and a time sent as it's global messaging
    public function sendAnnouncement(Request $request)
    {
        $announcement = $request->input('announcement');
        $t =time();
        $data = array(
            'announcement' => $announcement,
            'time_sent' => date("Y-m-d h:i:s",$t) 
        );

        DB::table('announcements')->insert($data);

        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        return view('taoex.adminAnnouncement', array('list_of_announcements' => $list_of_announcements, 'announcement' => $announcement));
    }

    // opens the announcement page with a list of announcements, no input as it's just opening
    public function openAnnouncement(){
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        return view('taoex.adminAnnouncement', array('list_of_announcements' => $list_of_announcements));
    }

    // deletes an announcement based on matching the announcement string and the time sent 
    public function deleteAnnouncement(Request $request)
    {
        DB::table('announcements')->where('announcement', '=', $request->announcement)->where('time_sent', '=', $request->time_sent)->delete();
        return redirect('/home/admin');
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
    
    //opens the message page for the selected member id, an id is passed and the typed in message will send to that member
    public function openAdminMessage($id)
    {
        $list_of_announcements = DB::table('announcements')->select('announcements.*')->get();
        $name = DB::table('users')->where('id', $id)->value('firstname');
        $lname = DB::table('users')->where('id', $id)->value('lastname');
        $fullname = "{$name} {$lname}";
        return view('taoex.adminSendMessage', array('fullname' => $fullname, 'id' => $id, 'list_of_announcements' => $list_of_announcements));
    }

    //opens a list of the banned users, used in the header blade 
    public function openBannedUsers()
    {
        $banned_users = DB::table('banned_users')->join('users', 'users.id', '=', 'banned_users.banned_id')->select('*')->get();
        return view('taoex.adminBannedUsers')->with([
            'bannedUsers' => $banned_users
        ]);
    }



    

}