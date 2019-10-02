@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Apply for a new Match</li>
    </ol>
    <div class="row">
      <div class="col-10">
        <div class="card mb-3">
          <div class="card-header">
            <div class="h3">Match Application Form</div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <form method="POST" action="{{ action('ApplyMatchController@apply') }}">
                <div class="form-group">
                  <label for="name">Match Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Name your new tournament">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Tell your club members where to meet">
                </div>
                <div class="form-group">
                  <label for="startDate">Start Time</label>
                  <input type="time" class="form-control" id="start_time" name="start_time" placeholder="">
                </div>
                <div class="form-group">
                  <label for="startDate">Start Date</label>
                  <input type="date" class="form-control" id="startDate" name="startDate" placeholder="">
                </div>
                <div class="form-group">
                  <label for="endDate">End Date</label>
                  <input type="date" class="form-control" id="endDate" name="endDate" placeholder="">
                </div>
                <input type="submit" class="btn btn-block btn-primary">
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-offset-2"></div>
    </div>
  </div>
  <!-- /.container-fluid-->
</div>
<!-- /.content-wrapper-->
@endsection