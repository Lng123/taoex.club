<div class="card border-light mb-3">
        	<div class="card-header">
                   <div class="h4">Top Players</div>
                </div>
                        <div class="card-body" style="overflow:auto">
                                {{-- <p>Number of Players: {!!$playerCount!!}</p>--}}
                               <table class="table table-striped table-bordered" id="example" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                     <th>
                                     </th>
                                     <th>Name</th>
                                     <th>Score</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                           
                                   @foreach ($ranking as $ranking)
                                   <tr>
                                     <td>
                                     @if (isset($user->image))
              <img style="max-width:60px;" src="{{ "data:image/" . $ruser->image_type . ";base64," . $user->image }}">
          @else
              <img style="max-width:60px;" src="images/empty_profile.png" alt="Avatar">
          @endif
                                     </td>
                                     <td>{{$ranking->firstName}} {{$ranking->lastName}}</td>
                                     <td><big><i>{{ $ranking->score}}</i></big></td>
                                   </tr>
                               @endforeach
                                 </tbody>
                               </table>
                               <!--<span style="float:right">
                               <a class="btn btn-outline-info" style="float:left;margin-right:3px" href="/home/applyNewMatch">Create a Match</a>

                        	<a class="btn btn-outline-info" style="float:right" href=/home/allMatch>more...</a>
                        

                </span>-->
                </div>
	    </div>
                

<div class="card border-light mb-3">
        	<div class="card-header">
                   <div class="h4">Top Clubs</div>
                </div>
                        <div class="card-body" style="overflow:auto">
                                {{-- <p>Number of Clubs: {!!$club_count!!}</p>--}}
                               <table class="table table-striped table-bordered" id="example" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                     <th>
                                     </th>
                                     <th>Name</th>
                                     <th>Location</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                           
                                   @foreach ($clubs as $club)
                                   <tr>
                                     <td>
                                     @if (isset($club->image))
              <img style="max-width:60px;" src="{{ "data:image/" . $club->image_type . ";base64," . $club->image }}">
          @else
              <img style="max-width:60px;" src="images/empty_profile.png" alt="Avatar">
          @endif
                                     </td>
                                     <td>{{$club->name}}</td>
                                     <td><big><i>{{ $club->city}}</i></big></td>
                                   </tr>
                               @endforeach
                                 </tbody>
                               </table>
                              <!-- <span style="float:right">
                               <a class="btn btn-outline-info" style="float:left;margin-right:3px" href="/home/applyNewMatch">Create a Match</a>

                        	<a class="btn btn-outline-info" style="float:right" href=/home/allMatch>more...</a>
                        

                </span>-->
                </div>
	    </div>