@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Profile</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('updateUser') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_token" maxlength="254" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" maxlength="254" value="{{ $userinfo->id }}">
                        
                        <div class="form-group">
                            <label for="picture" class="col-md-4 control-label">Picture</label>
                            <div class="col-md-6">
                                <?php
                                    $image = App\Utility::get_image_fromTable($userinfo->id,'users');
                                ?>
                         
                                &nbsp;&nbsp;&nbsp;&nbsp;<img src="{{ "data:image/" . $image['type'] . ";base64," . $image['data'] }}">
                            </div>
                            <label for="selectfile" class="col-md-4 control-label">Select File</label>
                            <div class="col-md-6">
                                <input type="file" name="image" id="image"/>
                            </div>
                        </div>
                        
                        
                        <div class="form-group{{ $errors->has('firstName') ? ' has-error' : '' }}">
                            <label for="firstName" class="col-md-4 control-label">First Name</label>
                            <div class="col-md-6">
                                <input id="firstName" type="text" class="form-control" name="firstName" maxlength="254" value="{{ $userinfo->firstName }}" required autofocus>
                                @if ($errors->has('firstName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastName') ? ' has-error' : '' }}">
                            <label for="lastName" class="col-md-4 control-label">Last Name</label>
                            <div class="col-md-6">
                                <input id="lastName" type="text" class="form-control" name="lastName" maxlength="254" value="{{ $userinfo->lastName }}" required autofocus>
                                @if ($errors->has('lastName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Address</label>
                            <div class="col-md-6">
                                <input id="address" type="address" class="form-control" name="address" maxlength="254" value="{{ $userinfo->address }}" required>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>
                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control" name="phone" maxlength="11" value="{{$userinfo->phone }}" required>
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" maxlength="254" value="{{ $userinfo->email }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country" class="col-md-4 control-label">Country</label>
                            <div class="col-md-6">
                                <select id="country" name="country" class="form-control">
                                    
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
                        
                        <input type="hidden" id="country_input" name="country_input" value="{{ $userinfo->country }}">
                        <input type="hidden" id="province_input" name="province_input" value="{{ $userinfo->province }}">

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">City</label>
                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" name="city" maxlength="254" value="{{ $userinfo->city }}" required>
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="hidden" name="optcheck" value="true">  
                                <?php 
                                    $opt =  $userinfo->opt;
                                    $r = "true"
                                ?>
                                @if (strcasecmp($r ,$opt ) == 0) 
                                    <label><input type="checkbox" name="optcheck" value="false">&nbsp;&nbsp;I do not want to recieve emails about offers and services from Pixelific Games Inc.</label>
                                @else 
                                    <label><input type="checkbox" name="optcheck" checked="checked" value="true">&nbsp;&nbsp;I do not want to recieve emails about offers and services from Pixelific Games Inc.</label>
                                @endif
                                <br /> 
                                        
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