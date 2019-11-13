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
        <a href="/home">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Club</li>
    </ol>
    <div class="card-header">
    <div class="h4">View all clubs</div>
                </div>
                        <div class="card-body" style="overflow:auto">
                               <table class="table table-striped table-bordered" id="member" style="overflow-x: scroll">
                                 <thead>
                                   <tr>
                                    <th>Club Name</th>
                                    <th>Club Owner</th>
                                    <th>Created at</th>
                                    <th>Apply</th>
                                   </tr>
                                 </thead>
                                 <tbody>
                            @if(isset($club_list))
                            @foreach ($club_list as $cl)
                            <tr>
                                <td><a href="{{ route('clubFilter',[$cl->id]) }}">{{ $cl->name }}</a></td>
                                <td>{{ $cl->firstName}} {{ $cl->lastName}}</td>
                                <td>{{ $cl->created_at }}</td>

                                @if($cl->status=='applied')
                                <td><a class="btn btn-outline-success" style="width:5rem" 	
                                   disabled>Applied</a></td>
                                @elseif($cl->status=='inClub')
                                <td><a class="btn btn-outline-success" style="width:5rem" 	
                                   disabled>In Club</a></td>
                                @else
                                <td>
                                <form method="POST" action="{{route('playerApplyToClub')}}">
                                {{ csrf_field() }}
                                <button class="btn btn-outline-success" style="width:5rem" type="submit">Apply</button>
                                <input type="hidden" value={{$cl->id}} name="club_id" />
                                </form>
                                </td>
                                @endif
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