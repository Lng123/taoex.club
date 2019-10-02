@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <!-- Breadcrumbs end -->
    <div class="row">
      <div class="col-8">
        <div class="card mb-3">
          <div class="card-header">Apply For Club Form</div>
          <div class="card-body">
            <form method="POST" action="{{ action('ClubController@applyClub') }}">
              <div class="form-group">
                <div class="form-row">
                  <div class="col-12">
                    <label for="clubName">Club Name</label>
                    <input class="form-control" id="clubName" name="clubName" type="text" placeholder="Enter your club name">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-row">
                  <div class="col-md-6">
                    <label for="province">Province</label>
                    <input type="text" class="form-control" id="province" name="province" value="{{ Auth::user()->province }}" readonly="true">
                  </div>
                  <div class="col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ Auth::user()->city }}" readonly="true">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-row">
                  <div class="col-md-12">
                    {{-- <label for="province">Members</label>
                    <select class="form-control" id="player_id" name="player_id">
                      @foreach ($nearPlayers as $nearPlayer)
                      <option value="{{ $nearPlayer->id }}">{{ $nearPlayer->firstName }} {{ $nearPlayer->lastName }}</option>
                      @endforeach
                    </select> --}}
                  </div>
                </div>
              </div>
              <input type="submit" class="btn btn-primary btn-block register-btn" value="Apply">
            </form>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-header"><h3>Note</h3></div>
          <div class="card-body">
            <div class="alert alert-danger">
              @if (isset($error))
              {{ $error }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- /.container-fluid-->
  </div>
  <!-- /.content-wrapper-->
</div>
@endsection