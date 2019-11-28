@extends('layouts.header')
@section('content')
<div class="content-wrapper" style="background-color:#d7d9e9">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb" style="background-color:white; margin-top:10px">
      <li class="breadcrumb-item">
        <a href="/home">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Club</li>
    </ol>

    <div class="row">
      <div class="col-md-4">
        <div class="card mb-3 text-center border-dark bg-secondary">
          <div class="card-header h4" style="color:white">
            Club Information
          </div>
          <div class="card-body">
          @if(isset($club))
            <div>
              <?php
              $image = App\Utility::get_image_fromTable($club->id, 'Club');
              ?>

              <img src="{{ "data:image/" . $image['type'] . ";base64," . $image['data'] }}" style="border-radius:50%; margin-bottom:15px;">

            </div>
            <ul class="list-group" style="color:gray">

              <li class="list-group-item" style="font-weight: bold;">Club Name: <span style="text-align: right;">{{ isset($club) ? $club->name : 'No-name' }}</span></li>

              <li class="list-group-item" style="font-weight: bold;">Club Owner: <span style="text-align: right;">{{ $clubOwner->firstName }} {{ $clubOwner->lastName }}</span></li>

              <li class="list-group-item" style="font-weight: bold;">Location: <span style="text-align: right;"> {{ $club->city }}, {{ $club->province }}
                </span></li>
              <li class="list-group-item" style="font-weight: bold;">Members: <span style="text-align: right;">{{ $numberMembers }}
                  <a class="btn btn-outline-success" style="width:7rem" href="{{ route('clubFilter',[$club->id]) }}">More Info</a>
                </span></li>
              <li class="list-group-item" style="font-weight: bold;">Ranking: <span style="text-align: right;">

                  <a href="/home/ranking">5</a></span></li>
              @if($club->owner_id == Auth::user()->id)
              <li class="list-group-item" style="font-weight: bold;">
                <a class="btn btn-outline-secondary" href="{{ route('manageClub') }}">Manage Club</a>
              </li>
              @endif
              <li class="list-group-item" style="font-weight: bold;">
                <a class="btn btn-outline-secondary" style="width:5rem" href="{{ url("home/club/playersearch") }}">Invite</a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-8" style="max-height: 1000px; overflow:auto;">
      @endif
      @if(isset($recordSuccess) and $recordSuccess == 1)
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Match Successfully Recorded!</strong>

        </div>
      @endif
          @if(isset($updateSuccess) and $updateSuccess == 1)
        <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Match Successfully Updated!</strong>

        </div>
      @endif
    @if(isset($winnerExist) and $winnerExist == 1)
        <div class="alert alert-danger" role="alert" style="margin-top:20px">
            <strong>There already is another winner for the match,<br>Delete their record and try again!</strong>

        </div>
          @endif
          @if(isset($createSuccess)) <div class="alert alert-success" role="alert" style="margin-top:20px">
            <strong>Match Successfully Created!</strong>

        </div>
      @endif  
    
      <div class="panel-group">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse1" style="width:100%">Club Messages</button>
                  </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse show">
                  <ul class="list-group">
                    <li class="list-group-item" style="overflow:auto">
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr data-toggle="collapse" data-target=".contents">
                            <th>Sender</th>
                            <th>Messages</th>
                            <th>Time</th>
                          </tr>
                          @if(isset($club_messages))
                          @foreach ($club_messages as $message)
                          <tr>
                            <td>{{$message->club_name}}</td>
                            <td>{{$message->message}}</td>
                            <td>{{$message->message_id}}</td>
                           
                          </tr>
                          @endforeach
                          @endif
                        </thead>
                        <tbody>

                        </tbody>
                      </table>

                    </li>
                  </ul>
                </div>
              </div>
              <div class="panel-group">
              <div class="panel panel-primary">
      
      
        <div class="panel-group">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse1" style="width:100%">Recent Club Matches</button>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
                   @if(isset($matches))
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
                               <a class="btn btn-outline-info" style="float:left;margin-right:3px" href="/applyNewMatch">Create a Match</a>
                               @endif
                                   @if (Auth::user()->id == $club->owner_id)
                          <a class="btn btn-outline-info" style="margin-right:3px" data-toggle="collapse" href="#collapse3">Record a Match</a>
                          @endif
                          <a class="btn btn-outline-info" style="float:right" href=/home/allMatch>View more...</a> </span> @endif </li> </ul> </div> </div> </div> <br>
                            <div id="collapse3" class="panel-collapse collapse">
                              <div class="panel-group">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                    <h4 class="panel-title">
                                      <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse3" style="width:100%">Record A Club Match</button>
                                    </h4>
                                  </div>
                                  <ul class="list-group">
                                    <li class="list-group-item" style="overflow:auto">
                                      <form method="POST" action="{{ action('ApplyMatchController@record') }}">
                                        <div class="form-group">
                                          <label class="label-control">Select Match</label>
                                          <select class="form-control" id="match_id" name="match_id">
                                            @foreach ($allMatches as $match)
                                            <option value="{{ $match->id }}">{{ $match->name }}</option>
                                            @endforeach
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
                                @if ('a' != 'a' )
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                                @else
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                @endif
                          </form>
                        
        </li>
      </ul>
    </div>
  </div>
</div>

                            <div class="panel-group">
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse4" style="width:100%">Club Management</button>
                                  </h4>
                                </div>
                                <div id="collapse4" class="panel-collapse collapse show">
                                  <ul class="list-group">
                                    <li class="list-group-item" style="overflow:auto">
                                      <div class="card">
                                        <div class="card-header" id="headingFive">
                                          <h5 class="mb-0">
                                            <button class="btn btn-outline-secondary" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"><i class="fa fa-fw fa-list-alt">
                                                <b style="color:gray">&nbsp;&nbsp; &nbsp;&nbsp;Club Owner Functions</b></i></button>
                                          </h5>
                                        </div>
                                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                                          <div class="card-body">
                                            <div class="card-body">
                                              <h6>Send message to club members</h6>
                                              <br />
                                              @if(Auth::user()->club_owner == 1)
                                              <div class="col=md-8">
                                                You are a club owner
                                                <br />
                                                You can send messages to your club members!
                                                <br />
                                                <br />
                                              </div>
                                              <form method="POST" action="{{ action('ClubController@sendMessagePage') }}">
                                                {{ csrf_field() }}
                                                <div class="form-group">
                                                  <label for="message">Message:</label>
                                                  <input type="text" class="form-control" id="message" name="message" placeholder="Enter message here">
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block">Send</button>
                                              </form>
                                              @endif
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                </div>

                      </li>
                    </ul>
                  </div>
                </div>

              </div>
        </div>
      </div>
    </div>
  </div>
  @endsection