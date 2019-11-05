@extends('layouts.header')
@section('content')
<div class="content-wrapper" style="background-color:#d7d9e9">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb" style="background-color:white; margin-top:10px">
            <li class="breadcrumb-item">
                <a href="#">Taoex</a>
            </li>
            <li class="breadcrumb-item active">View all clubs</li>
            <!-- user card -->
        <br><div class="h3">Wecome, <span class="color-primary">{{ strtoupper(Auth::user()->firstName) }} {{ strtoupper(Auth::user()->lastName) }} </span></div>
        </ol>
                    <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse3" style="width:100%">All clubs</button>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse show">
      <ul class="list-group">
        <li class="list-group-item" style="overflow:auto">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr data-toggle="collapse" data-target=".contents">
                                <th></th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($user_list))
                            @foreach ($user_list as $ul)
                            <tr>
                                <td style="width:70px">
                                     @if (($ul->image) != NULL)
              <img style="max-width:60px;" src="{{ "data:image/" . $ul->image_type . ";base64," . $ul->image }}">
          @else
              <img style="max-width:60px;" src="/images/empty_profile.png" alt="Avatar">
          @endif
                                     </td>
                                <td>{{ $ul->id }}</td>
                                <td>{{ $ul->firstName}} {{ $ul->lastName}}</td>

                                <td><a class="btn btn-outline-success" style="width:5rem">Remove</a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
  
        </li>
      </ul>
    </div>
  </div>
                
        <!--<div class="h4" style="display:@if (Auth::user()->club_id == null) none @else '' @endif">Club Tournaments <hr/></div>-->
	

	
    </div>
    </div>
    
</div>
    <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection