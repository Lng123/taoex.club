@extends('layouts.header')
@section('content')
<script>
   $(document).ready(function() {
        $('#member').DataTable()( {
            aaSorting: [[0, 'asc']]
        });
    } );
</script>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/home">Taoex</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('club') }}">Club</a>
          </li>
          <li class="breadcrumb-item active">Manage Club</li>
        </ol>
        <form method="POST" id="club_dropdown">
            <select class="form-control" name="select_id" onchange="top.location.href = this.options[this.selectedIndex].value">
                <option>{{$currentClub->name}}</option>
                @foreach($clubData as $clubDatum)
                @if ($clubDatum['club_id'] != $club_id)
                <option value="{{route('manageClubMembers', $clubDatum['club_id'])}}">{{$clubDatum['club_name']}}</option>
                @endif
                @endforeach
            </select>
        </form>
        <div class="col-md-8">
            <div class="h3">Club Members</div>
            <div class="table-responsive data-table" >
                <table id="member" class="table table-bordered" width ="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Manage Members</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($memberData as $memberDatum)	
                        <tr>
                            <td>{{ $memberDatum['name'] }}</td>
                            <td>@if ($memberDatum['id'] == $club_owner) Club Owner @else Club Member @endif</td>
                            <td>
                            @if ($memberDatum['id'] != $club_owner)
                                <a class="btn btn-primary"	
                                    href="{{ route('adminRemoveMember', ['id'=>$memberDatum['id'],'club_id'=>$club_id]) }}" onclick="return confirm('Are you sure to want to remove this member?')">Remove</a>
                                <input class="btn btn-primary" type="button"value="Message"/>
                            </td>
                            @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
