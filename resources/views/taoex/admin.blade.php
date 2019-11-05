@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/home">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Admin</li>
    </ol>
    
    <div class="row">
        
    <div class="col-md-12" style="max-height: 1000px; overflow:auto;">
    
     <div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse2" style="width:100%">Update Announcements</button>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
             <form method="POST" action="{{ action('HomeController@sendAnnouncement') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="message">New message for announcement board:</label>
                    <input type="text" class="form-control" id="announcement" name="announcement" placeholder="Enter announcement here" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send</button>
            </form>      @if(isset($announcement))
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Announcement has been sent!</strong> Please check landing page for the update.
        </div>
      @endif             
                               
        </li>
      </ul>
    </div>
  </div>

      </div>
    
    
    
    
    
    
     @if(isset($recordSuccess))
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Match Recorded!</strong>
            <a class="btn btn-success" href=/home/admin>Reload</a>

        </div>
      @endif  
       @if(isset($editSuccess))
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Selected result has been updated.</strong> 
            <a class="btn btn-success" href=/home/admin>Reload</a>

        </div>
      @endif  
       @if(isset($deleteSuccess))
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Match successfully deleted.</strong>
            <a class="btn btn-success" href=/home/admin>Reload</a>
        </div>
      @endif          
      
        <div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse1" style="width:100%">Match Edit</button>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
                               <table class="table table-striped table-bordered" id="example" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                     <th>Delete</th>
                                     <th>Match Name</th>
                                     <th style="text-align: center">Edit</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                                   @foreach ($matches as $match)
                                   <tr>
                                    <form method="POST" action="{{action('HomeController@deleteMatch')}}">
                                                    {{ csrf_field() }}

                                     <td width="10%"><button type="submit" class="btn btn-outline-danger"><i class="fa fa-fw fa-remove"></i></button>
                                     <input type="text" class="form-control" id="matchName" name="matchName" value="{{$match->name}}" style="display:none; width:30px"></td>
                                     </form>
                                     <td>{{$match->name}}
                                     
                                     	<div class="panel-group">
					  <div class="panel panel-primary">
					    <div id="<?php echo $string= str_replace(' ', '',$match->name)?>" class="panel-collapse collapse">
					      <ul class="list-group">
					        <li class="list-group-item" style="overflow:auto">
					          <table class="table table-bordered" width="100%" cellspacing="0">
					          
              <thead>
                <tr>
                  <th>Player</th>
                  <th>Hook Tiles</th>
                  <th>Captures</th>
                  <th>Eliminated</th>
                  <th>Win Bonus</th>
                  <th>Edit</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($results as $result)
                @if ($result->match_id == $match->id)
                <tr>
                  <form method="POST" action="{{action('HomeController@editMatch')}}">
                                                    {{ csrf_field() }}

                  <td>{{ $result->firstName }} {{ $result->lastName }} @if ($result->winBonus != 0) <span style="color: red; font-size:14px">(Victory)</span>@endif</td>
                  <td><input type="text" class="form-control" id="hook" name="hook" value="{{ $result->hook }}"></td>
                  <td><input type="text" class="form-control" id="capture" name="capture" value="{{ $result->capture }}"></td>
                  <td><input type="text" class="form-control" id="elimination" name="elimination" value="{{ $result->elimination }}"></td>
                  <td><input type="text" class="form-control" id="winBonus" name="winBonus" value="{{ $result->winBonus }}"></td>
                 <td><button type="submit" class="btn btn-outline-info"><i class="fa fa-fw fa-pencil"></i></button></td>
                </form>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
                                      <a class="btn btn-outline-info" style="margin-right:3px" data-toggle="collapse" href="#<?php echo $string= str_replace(' ', '',$match->name) . "a"?>">Record a Match</a>
<div id="<?php echo $string= str_replace(' ', '',$match->name) . "a"?>" class="panel-collapse collapse">
    <div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#<?php echo $string= str_replace(' ', '',$match->name) . "a"?>" style="width:100%">Record A Club Match</button>
      </h4>
    </div>
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
                            <form method="POST" action="{{ action('HomeController@record') }}">
                            <div class="form-group">
                              <label class="label-control">Select Match</label>
                              <select class="form-control" id="match_id" name="match_id">
                                <option value="{{ $match->id }}">{{ $match->name }}</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label class="label-control">Select Player</label>
                              <select class="form-control" id="player_id" name="player_id">
                                @foreach ($clubMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->firstName }} {{ $member->lastName }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="numberPlayers">Number of Players</label>
                              <input type="number" class="form-control" id="numberPlayers" min="1" name="numberPlayers" placeholder="" Max="8" required="true">
                            </div>
                            <div class="form-group">
                              <label for="elimination">Elimination</label>
                              <input type="number" class="form-control" id="elimination" min="0" name="elimination" placeholder="" required="true">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                            <div class="form-group">
                              <label for="capture">Capture</label>
                              <input type="number" class="form-control" id="capture" min="0" name="capture" required="true">
                            </div>
                            <div class="form-group">
                              <label for="hook">Hook Tiles</label>
                              <input type="number" class="form-control" id="hook" min="0" name="hook" placeholder="" required="true">
                            </div>
                            <div class="form-group">
                              <label for="winBonus">Victory By:</label>
                              <Select class="form-control" id="winBonus" name="winBonus">
                                <option value="0">Not a winner</option>
                                <option value="6">Winner</option>
                                <option value="5">Liberation</option>
                                <option value="10">Arition</option>
                              </Select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                          </form>
                        
        </li>
      </ul>
    </div>
  </div>
</div>


					        
                                      </li>
      </ul>
    </div>
  </div>

      </div>
                                     
                                     
                                     
                                     
                                     </td>
                                     <td width=10%>
                                     <form method="POST" action="{{action('HomeController@openAdmin')}}">
                                                    {{ csrf_field() }}
                                     <button type="submit" class="btn btn-outline-info" data-toggle="collapse" href="#<?php echo $string= str_replace(' ', '',$match->name)?>" ><i class="fa fa-fw fa-pencil"></i></button>
                                     <input type="text" class="form-control" id="matchName" name="matchName" value="{{$match->name}}" style="display:none"></td>
                                     </form>
                                   </tr>
                               @endforeach
                                 </tbody>
                               </table>
                               <span style="float:right">
                               @if (Auth::user()->club_id != null)
                               <a class="btn btn-outline-info" style="float:left;margin-right:3px" href="/home/applyNewMatch">Create a Match</a>
                               @endif

                          <a class="btn btn-outline-info" style="float:right" href=/home/allMatch>View more...</a>
                 </span>
               
        
        </li>
      </ul>
    </div>
  </div>

      </div>

    
   
    
    
    </div>
    <!-- /.container-fluid-->
  </div>
  <!-- /.content-wrapper-->
</div>
@endsection