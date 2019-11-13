@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{route('home')}}">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Admin</li>
    </ol>

    <div class="row">

      <div class="col-md-12" style="max-height: 1000px; overflow:auto;">

        <div class="panel-group">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">
                <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse2" style="width:100%">Message</button>
              </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse show">
              <ul class="list-group">
                <li class="list-group-item" style="overflow:auto">
                  <form method="POST" action="{{ action('MessageController@sendAdminMessage') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="message">New message to user: {{$id}} - {{$fullname}}</label>
                      <input type="text" class="form-control" id='id' name='id' value={{$id}} readonly>
                      <input type="text" class="form-control" id="message" name="message" placeholder="Enter message here" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                  </form> @if(isset($announcement))
                  <div class="alert alert-success" role="alert" style="margin-top:20px">
                    <strong>Message has been sent!</strong> Please check landing page for the update.
                  </div>
                  @endif

                </li>
              </ul>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="panel-title">
                <button type="button" class="btn btn-secondary" data-toggle="collapse" href="#collapse3" style="width:100%">Messages</button>
              </h4>
        </div>





