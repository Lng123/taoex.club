<script>
   $(document).ready(function() {
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
                                        min="2010" max="2018" value="2018" value="" />
                                    <span class="validity"></span>   
                                     <input type="submit" class="btn btn-primary">
                            </div> 
                             <hr>  
                        </form>
                        
                       
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
                            </tr>
                          </thead>
                          <tbody>
                          
                          @foreach($memberData as $memberDatum)	
                            <tr>
                            <td>{{ $memberDatum['rank'] }}</td>
                              <td>{{ $memberDatum['name'] }}</td>
                              
                              <td>@if ($memberDatum['role'] == 1) Club Owner @else Club Member @endif</td>
                              <td>{{ $memberDatum['games'] }}</td>
                              <td>{{ $memberDatum['won'] }}</td>
                              <td>{{ $memberDatum['score'] }}</td>

                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>