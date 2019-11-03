@extends('layouts.header')
@section('content')
<script>
$(document).ready(function() {
    // $(".edit").click(function() {
    //     console.log("test");
    //     $(this).siblings().attr('contenteditable',true).focus();
    // });
});

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
                   <div class="h4">Manage Users</div>
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
                                     <td><span contenteditable = "true" class = "playername">{{$ranking->firstName}} {{$ranking->lastName}}</span><a href="#" style="color:grey;" class="edit"> &#9998;</a></td>
                                     <td><big><i>{{ $ranking->score}}</i></big></td>
                                     <td style = "width:100px"><a class="btn btn-outline-success" style="width:4rem">Remove</a></td>
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