@extends('layouts.header')
@section('content')
<div class="content-wrapper" style="background-color:#d7d9e9">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb" style="background-color:white; margin-top:10px">
            <li class="breadcrumb-item">
                <a href="#">Taoex</a>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
            <!-- user card -->
        <br><div class="h3">Welcome, <span class="color-primary">{{ strtoupper(Auth::user()->firstName) }} {{ strtoupper(Auth::user()->lastName) }} </span></div>
        </ol>

        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3 text-center border-dark bg-secondary">
                    <div class="card-header h4" style="color:white">
                        Personal Information
                    </div>
                    <div class="card-body">
                        <div>
                        <?php
                            $image = App\Utility::get_image_fromTable(Auth::user()->id,'users');
                        ?>
                         
                        <img src="{{ "data:image/" . $image['type'] . ";base64," . $image['data'] }}" style="border-radius:50%; margin-bottom:15px;">

                        </div>
                        <ul class="list-group" style="color:gray">
                            <li class="list-group-item" style="font-weight: bold;">User Level: <span style="text-align: right;">{{ (Auth::user()->type == 1) ? 'Club Owner' : 'Normal' }}</span></li>
                            <li class="list-group-item" style="font-weight: bold;">Club: <span style="text-align: right;" >{{ isset($club) ? $club->name : 'None' }} 
                            <a class="btn btn-outline-success" style="display:@if (Auth::user()->club_id == null) '' @else none @endif; width:5rem" 	
                            href="{{ route('newClub') }}">Create</a></span></li>
                            <li class="list-group-item" style="font-weight: bold;">Total Score: <span style="text-align: right;">{{ $totalScore }}</span></li>
                            <li class="list-group-item" style="font-weight: bold;">Ranking: <span style="text-align: right;">
                            
                            <a href="/home/ranking">{{ $ranking }}</a></span></li>

                            <li class="list-group-item" style="font-weight: bold;">
                                <a class="btn btn-outline-secondary" style="width:5rem" href="{{ route('editUser',Auth::user()->id) }}">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-outline-secondary" style="width:5rem; text-align:center" href="{{ route('deleteUser',Auth::user()->id) }}">Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
            	<div class="row">
            	<div class="col-md-12">
            	 @if($errors->any())
        <div class="alert alert-danger" role="alert" style="margin-top:20px">
            <strong>{{$errors->first()}}</strong> &nbsp;&nbsp;
            <a class="btn btn-success" href="/home/newclub">Create a Club</a>
        </div>
      @endif  
    
            	<div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse1" style="width:100%">Messages</button>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr data-toggle="collapse" data-target=".contents">
                                <th>Messages</th>
                                <th>Time</th>
                                <th><a href="#">Click here to view messages</a></th>
                            </tr>
                        </thead>
                        <tbody>                          
                       
                        </tbody>
                    </table>
  
        </li>
      </ul>
    </div>
  </div>
<br>
    	<div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse2" style="width:100%">Recent Matches</button>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
        
        
            @if(isset($matches))
                                {{-- <p>Number of Matches Played: {!!$club_count!!}</p>--}}
                               <table class="table table-striped table-bordered" id="example" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                     <th>Match Name</th>
                                     <th>Address</th>
                                     <th>Start Time</th>
                                     <th>Start Date</th>
                                     <th>End Date</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                                   @foreach ($matches as $match)
                                   <tr>
                                     <td>{{$match->name}}</td>
                                     <td>{{$match->address}}</td>
                                     <td>{{$match->start_time}}</td>
                                     <td>{{$match->startDate}}</td>
                                     <td>{{$match->endDate}}</td>
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
                </div>
                
            @endif
        </li>
      </ul>
    </div>
  </div>

            @if (isset($club_id) && Auth::user()->approved_status == 2)
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header h4">Invitation</div>
                    <div class="card-body">
                        <div class="h5">You have an invitation</div>
                        <form method="GET" action="{{ action('ClubController@acceptInvitation') }}">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">Club Name:</div>
                                    <div class="col-6"> {{ $club->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Club Owner:</div>
                                    <div class="col-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Club Location:</div>
                                    <div class="col-6">{{ $club->city }}, {{ $club->province }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Accept</button>
                            </div>
                        </form>
                        <form method="GET" action="{{ action('ClubController@declineInvitation') }}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger btn-block">Decline</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

                
        <!--<div class="h4" style="display:@if (Auth::user()->club_id == null) none @else '' @endif">Club Tournaments <hr/></div>-->
	

	
    </div>
    </div>
    
</div>
    <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection