<div class="card-body">
            <div id="accordion">
                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5>Filter</h5>
                      </button>
                    </h5>
                  </div>
                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                      <div class="card-body">
                            <form method="POST" action="{{ action('MatchController@filter') }}">
                                {{ csrf_field() }}
                            <div class="form-group">
                                    <small><b>Input a range <i>(Month and Year)</i></b></small>
                                    <hr>
                            
                                    <label for="start">Starting Range</label>
                                    <input type="month" id="date" name="date"
                                        min="2010-01" max="2018-05" value="" />
                                    <span class="validity"></span>   
                                    <label for="start">&emsp;&emsp;Ending Range</label>
                                    <input type="month" id="date2" name="date2"
                                        min="2010-01" max="2018-05" value="" />
                                    <span class="validity"></span>
                            </div>
                            <input type="submit" class="btn btn-primary">   
                            <a class="btn btn-link pull-right " href="filterMatch">Show All</a>
                        </form>

                        </div>
                  </div>
                </div>
                <br>