@extends('layouts.header')
@section('content')
<div class="content-wrapper" style="background-color:#d7d9e9">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb" style="background-color:white; margin-top:10px">
      <li class="breadcrumb-item">
        <a href="#">Taoex</a>
      </li>
      <li class="breadcrumb-item active">League</li>
      <!-- user card -->
      <br>
      <div class="h3">Welcome, <span class="color-primary">{{ strtoupper(Auth::user()->firstName) }} {{ strtoupper(Auth::user()->lastName) }} </span></div>
      <div class="h5">League section is currently under developement. Please contact administrator for more detail.<div>
    </ol>


    
      </div>
          <!-- /.content-wrapper-->
    </div>
          @endsection