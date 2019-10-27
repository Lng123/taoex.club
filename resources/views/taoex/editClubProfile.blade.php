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

        <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Club Profile</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('updateClub') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $club->id }}">
                        
                        <div class="form-group">
                            <label for="picture" class="col-md-4 control-label">Picture</label>
                            <div class="col-md-6">
                                <?php
                                      $image = App\Utility::get_image_fromTable($club->id,'Club');
                                ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;<img src="{{ "data:image/" . $image['type'] . ";base64," . $image['data'] }}">
                            </div>
                            <label for="selectfile" class="col-md-4 control-label">Select File</label>
                            <div class="col-md-6">
                                <input type="file" name="image" id="image"/>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="ClubName" class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input id="Name" type="text" class="form-control" name="Name" value="{{ $club->name }}" required autofocus>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="country" class="col-md-4 control-label">Country</label>
                            <div class="col-md-6">
                                <select id="country" name="country" class="form-control" disabled="disabled">
                                    
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="States" class="col-md-4 control-label">States/Province</label>
                            <div class="col-md-6">
                                <select id="province" name="province" class="form-control">
                                    
                                </select>
                            </div>
                        </div> 
                        
                        <input type="hidden" id="country_input" name="country_input" value="{{ Auth::user()->country }}">
                        <input type="hidden" id="province_input" name="province_input" value="{{ $club->province }}">

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">City</label>
                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" value="{{ $club->city }}" required>
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                        
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                                
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
        </div>
        <div class="h3">Club Members</div>
            <div class="card">
              @include('layouts.clubMember')
              </div>
            </div>
    </div>
</div>
<!-- Scripts -->

    <script src="{{ asset('js/countries.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
        function load()
        {                            
             var country =document.getElementById('country');
             var country_input =document.getElementById('country_input');
                             
             for(var i=0; i<country.options.length; i++) {
                if (country.options[i].value==country_input.value){
                    country.selectedIndex=i;
                }
            }
            country.value = country_input.value;
            
            var province =document.getElementById('province');
            //populateStates("country", "province"); 
            var selectedCountryIndex = country.selectedIndex;
            var stateElement = province;
            stateElement.length = 0;
            stateElement.options[0] = new Option('Select State', '');
            stateElement.selectedIndex = 0;
            var state_arr = s_a[selectedCountryIndex].split("|");
            for (var i = 0; i < state_arr.length; i++) {
                stateElement.options[stateElement.length] = new Option(state_arr[i], state_arr[i]);
            }
            
           
            var province_input =document.getElementById('province_input');
            for(var i=0; i<province.options.length; i++) {
                if (province.options[i].value==province_input.value){
                    province.selectedIndex=i;
                }
            }
            province.value = province_input.value;

        }
    </script>
@endsection

