@extends('layouts.header')
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
                   <div class="h4">Top Players</div>
                </div>
                        <div class="card-body" style="overflow:auto">
                                {{-- <p>Number of Players: {!!$playerCount!!}</p>--}}
                               <table class="table table-striped table-bordered" id="member" style="overflow-x: scroll">
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
                                     <td style="width:70px">
                                     @if (isset($ranking->image))
              <img style="max-width:60px;" src="{{ "data:image/" . $ranking->image_type . ";base64," . $ranking->image }}">
          @else
              <img style="max-width:60px;" src="/images/empty_profile.png" alt="Avatar">
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
	      </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection