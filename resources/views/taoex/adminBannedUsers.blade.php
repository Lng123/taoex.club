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
      <li class="breadcrumb-item active">Banned Users</li>
    </ol>
    <div class="card-header">
      <div class="h4">List of Banned Users</div>
    </div>
    <div class="card-body" style="overflow:auto">
      {{-- <p>Number of Players: {!!$playerCount!!}</p>--}}
      <table class="table table-striped table-bordered" id="member" style="overflow-x: scroll">
        <thead>
          <tr>
            <th>
            </th>
            <th>Name</th>
            <th>Unban</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($bannedUsers as $user)
          <tr>
            <td style="width:70px">
              @if (isset($user->image))
              <img style="max-width:60px;" src="{{ "data:image/" . $user->image_type . ";base64," . $user->image }}">

              @else
              <img style="max-width:60px;" src="/images/empty_profile.png" alt="Avatar">
              @endif
            </td>
            <td width="55%">
              <span class="playername">{{$user->firstName}} {{$user->lastName}}
                <a style="color:grey;" class="edit"> &#9998;</a>
              </span>

              <form style="display: none;" class="form">
                <input type="text" class=editplayername value="{{$user->firstName}} {{$user->lastName}}">
                <button class="btn btn-outline-secondary" style="width:4rem; font-size:10px" type="submit" value="updateName">Update</button>
                <button class="btn btn-outline-secondary" style="widtch:5rem; font-size:10px" type="button" name="cancel">Cancel</button>
              </form>
            </td>
            <td>
              <a class="btn btn-outline-success" style="width:5rem" href="{{route('unbanUser',['id'=>$user->id])}}">Lift Ban</a>
            </td>
            <td>
              {{$user->reason}}
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