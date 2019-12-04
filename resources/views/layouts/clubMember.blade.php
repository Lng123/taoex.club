<script>
   $(document).ready(function() {
     var year = new Date().getFullYear();
     document.getElementById("year").setAttribute("max", year);
    $('#member').DataTable()( {
     aaSorting: [[0, 'asc']]
});
} );
</script>
<div class="card-body">
  <div class="card-body">
    <form method="POST" action="{{ action('ClubController@clubMemberRanking') }}">
                                {{ csrf_field() }}
      <div class="form-group">
        <small><b>Rankings for the Year of:</b></small>
        <input type="number" id="year" name="year"
                                        min="1990" max="" value={{$date}} />
        <input type="hidden" name ="club_id" value={{$club_id}} >
        <span class="validity"></span>   
        <input type="submit" class="btn btn-primary">
      </div> 
    </form>
    <span><b>Club Score: {{$total_score}} </b></span>
    </div>
</div>
<div class="table-responsive data-table">
  <table id="member" class="table table-bordered" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Role</th>
        <th>Total Games</th>
        <th>Won</td>
        <th>Score</td>
        <!-- <th>Manage Members</td> -->
      </tr>
    </thead>
    <tbody>
    @foreach($memberData as $index=>$memberDatum)	
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $memberDatum['name'] }}</td>
        <td>@if ($memberDatum['role'] == 1) Club Owner @else Club Member @endif</td>
        <td>{{ $memberDatum['games'] }}</td>
        <td>{{ $memberDatum['won'] }}</td>
        <td>{{ $memberDatum['score'] }}</td>
        <!-- <td>@if ($memberDatum['role'] != 1)<input class="btn btn-primary" value="Kick"/><input class="btn btn-primary" value="Message"/>@endif</td> -->
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
