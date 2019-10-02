@extends('layouts.header')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/home">Taoex</a>
      </li>
      <li class="breadcrumb-item active">Club</li>
    </ol>
    <div class="h3">Club Members</div>
            <div class="card">
              @include('layouts.clubMember')
              </div>
            </div>
  </div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection