@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/home">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Admin Announcements</li>
    </ol>

    <div class="row">

      <div class="col-md-12" style="max-height: 1000px; overflow:auto;">

        <div class="panel-group">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">
                <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse2" style="width:100%">Update Announcements</button>
              </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse show">
              <ul class="list-group">
                <li class="list-group-item" style="overflow:auto">
                  <form method="POST" action="{{ action('HomeController@sendAnnouncement') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="message">New message for announcement board:</label>
                      <input type="text" class="form-control" id="announcement" name="announcement" placeholder="Enter announcement here" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                  </form> @if(isset($announcement))
                  <div class="alert alert-success" role="alert" style="margin-top:20px">
                    <strong>Announcement has been sent!</strong> Please check landing page for the update.
                  </div>
                  @endif

                </li>
              </ul>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">
                <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse3" style="width:100%">Show Announcements</button>
              </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse show">
              <ul class="list-group">

                <li class="list-group-item" style="overflow:auto">

                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr data-toggle="collapse" data-target=".contents">
                        <th>Messages</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                    @if(isset($list_of_announcements))

                    @foreach($list_of_announcements as $ann)
                    <tr>
                      <td>{{$ann->announcement}}</td>
                      <td>{{$ann->time_sent}}</td>
                      <td><a href="{{ route('deleteAnnouncement',['announcement'=>$ann->announcement,'time_sent'=>$ann->time_sent]) }}"> x </a></td>
                      @endforeach
                      @endif
                    </tr>

                  </table>

                </li>
              </ul>
            </div>
          </div>
        </div>





