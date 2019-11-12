@extends('layouts.header')
@section('content')
<script>
  $(document).ready(function() {
    $(".edit").click(function() {
      $(this).parent().css("display", "none");
      $(this).parent().next().css("display", "block");
    });
    $('[name = "cancel"]').click(function() {
      $(this).parent().css("display", "none");
      $(this).parent().prev().css("display", "block");
    });

    $('#member').DataTable()({
      aaSorting: [
        [0, 'asc']
      ]
    });
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
              <img style="max-width:60px" src="{{ "data:image/" . $ranking->image_type . ";base64," . $ranking->image }}">

          @else
              <img style="max-width:60px" src="/images/empty_profile.png" alt="Avatar">
          @endif
                                     </td>
                                     <td width="55%">
                                        <span class = "playername">{{$ranking->firstName}} {{$ranking->lastName}}
                                                <a style="color:grey;" class="edit"> &#9998;</a>
                                        </span>
                                        
                                        <form method="POST" style="display: none;" class="form" action="{{ route('editName') }}">
                                            {{ csrf_field() }}
                                            <input type="number" style="display: none;" value="{{$ranking->id}}"
                                               name="id">
                                            <input type="text" class = editplayername value="{{$ranking->firstName}}" name="firstname" style="width:150px" >
                                            <input type="text" class = editplayername value="{{$ranking->lastName}}" name="lastname" style="width:150px">
                                            <button class="btn btn-outline-secondary" style="width:3rem; font-size:10px" type="submit" value="updateName" >Update</button>
                                            <button class="btn btn-outline-secondary" style="widtch:5rem; font-size:10px" type="button" name="cancel">Cancel</button>
                                        </form>
                                    </td>
                                     <td><big><i>{{ $ranking->score}}</i></big></td>
                                       @if(($clubs->where('owner_id', $ranking->id))->count() == 0)
                                     <td style = "width:100px"><a class="btn btn-outline-success" style="width:5rem" onclick = "return confirm('Are you sure you want to remove this member?')"href="{{ route('deleteUserAdmin', [$ranking->id]) }}">Remove</a></td>
                                       @else
                                       <td style = "width:100px"><a class="btn btn-outline-success" style="width:5rem" onclick = "return alert('This user owns one or more clubs.  Please re-assign the Club Owner for these club(s) from the Admin Club page first.')">Remove</a></td>
                                        @endif
                                                   <td>
              <a class="btn btn-outline-success" style="width:5rem" href="{{route('banUser',['id'=>$ranking->id])}}">Ban User</a>
            </td>
                        <td>
              <a class="btn btn-outline-success" style="width:5rem" href="{{route('banUser',['id'=>$ranking->id])}}">Ban User</a>
            </td>
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