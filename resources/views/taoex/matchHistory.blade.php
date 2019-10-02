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
      <li class="breadcrumb-item active">Match History</li>
      <!--<input data-provide="datepicker" class="pull-right form-contorl">-->
    </ol>
    <div class="h1">Match History</div>
    <!--<div class="date-picker"></div>-->
    <hr>
    <div>
    @include('layouts.filter')
    </div>
    <!-- match history -->
    <div class = "card">
      @if ($results != null)
      @foreach ($matches as $match)
      <div class="card mb-2">
        <div class="card-header">
          Match: {{ $match->name }}
          <span class="float-right"><i>Dated: {{$match->endDate}}</i></span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="member" class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Player</th>
                  <th>Hook Tiles</th>
                  <th>Captures</th>
                  <th>Eliminated</th>
                  <th>Win Bonus</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($results as $result)
                @if ($result->match_id == $match->id)
                <tr>
                  <td>{{ $result->firstName }} {{ $result->lastName }} @if ($result->winBonus != 0) <span style="color: red; font-size:14px"><img src="/images/victory.png">(Victory)</span>@endif</td>
                  <td>{{ $result->hook }}</td>
                  <td>{{ $result->capture }}</td>
                  <td>{{ $result->elimination }}</td>
                  <td>{{ $result->winBonus }}</td>
                  <td>{{ $result->total }}</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      @endforeach
      @endif
    </div>
</div>
</div>
@endsection