@extends('layouts.header')
@inject('clubinvite', 'App\Http\Controllers\ClubController')
@section('content')
<script>
   $(document).ready(function() {
    $('#member').DataTable()( {
     aaSorting: [[0, 'asc']]
});
} );
</script>
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{route('home')}}">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Manage Clubs</li>
    </ol>
    <div class="card-header">
    <div class="h4">Manage all clubs</div>
                </div>
                        <div class="card-body" style="overflow:auto">
                               <table class="table table-striped table-bordered" id="member" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                   <th>Club ID</th>
                                <th>Club Name</th>
                                <th>Club Owner</th>
                                <th>Created at</th>
                                <th>Club Score</th>
                                <th>Remove</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                            @if(isset($club_list))
                            @foreach ($club_list as $cl)
                            <tr>
                                <td>{{ $cl->id }}</td>
                                <td><a href="{{ route('manageClubMembers',[$cl->id]) }}">{{ $cl->name }}</a><a href='' style="color:grey;"> &#9998;</a></td>
                                <td>{{ $cl->firstName}}, {{ $cl->lastName}}</td>
                                <td>{{ $cl->created_at }}</td>
                                <td>{{ $cl->club_score }}</td>

                                <!-- @if($club_id == $cl->id)
                                <td><a class="btn btn-outline-success" style="width:5rem" 	
                                   disabled>Selected</a></td>
                                @else
                                <td><a class="btn btn-outline-success" style="width:5rem" 	
                            href="{{ route('changeClub', [$cl->id]) }}">Apply</a></td>
                                @endif -->
                                <td><a class="btn btn-outline-danger" style="width:5rem" href="{{ route('adminDeleteClub',['club_id'=>$cl->id]) }}" onclick = "return confirm('Are you sure you want to remove this club?')">Remove</a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                               </table>
                               <!--<span style="float:right">
                               <a class="btn btn-outline-info" style="float:left;margin-right:3px" href="/home/applyNewMatch">Create a Match</a>

                        	<a class="btn btn-outline-info" style="float:right" href=/home/allMatch>more...</a>
                        

                </span>-->
                </div>
	    </div>
	      </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection