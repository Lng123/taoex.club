@extends('layouts.app')



@section('content')

<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">

                <div class="panel-heading">Register</div>



                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">

                        {{ csrf_field() }}



                        <div class="form-group{{ $errors->has('firstName') ? ' has-error' : '' }}">

                            <label for="firstName" class="col-md-4 control-label">First Name</label>



                            <div class="col-md-6">

                                <input id="firstName" type="text" class="form-control" name="firstName" maxlength="255"value="{{ old('firstName') }}" required autofocus>



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

                                <input id="lastName" type="text" class="form-control" name="lastName" maxlength="255" value="{{ old('lastName') }}" required autofocus>



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

                                <input id="address" type="address" class="form-control" name="address" maxlength="255" value="{{ old('address') }}" required>



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

                                <input id="phone" type="phone" class="form-control" name="phone" maxlength="11" value="{{ old('phone') }}" required>



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

                                <input id="email" type="email" class="form-control" name="email" maxlength="255" value="{{ old('email') }}" required>



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

                                    <option value="">Select your Country</option>

                                </select>

                            </div>

                        </div>



                        <div class="form-group">

                            <label for="States" class="col-md-4 control-label">States/Province</label>



                            <div class="col-md-6">

                                <select id="province" name="province" class="form-control">

                                    <option value="">Select your States/Province</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">

                            <label for="city" class="col-md-4 control-label">City</label>



                            <div class="col-md-6">

                                <input id="city" type="text" class="form-control" name="city" maxlength="255" value="{{ old('city') }}" required>



                                @if ($errors->has('city'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('city') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>



                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <label for="password" class="col-md-4 control-label">Password</label>



                            <div class="col-md-6">

                                <input id="password" type="password" class="form-control" name="password" maxlength="255" required>



                                @if ($errors->has('password'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('password') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>

                        <input type="hidden" id="type" name="type" value="0">

                        <div class="form-group">

                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>



                            <div class="col-md-6">

                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  maxlength="255"required>

                            </div>

                        </div>



                        <div class="form-group">

                            <div class="col-md-6 col-md-offset-4">
                                <div class="radio">
                                     <label><input type="radio" name="optradio">I do want to recieve emails about offers and services from Pixelific Games Inc.</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="optradio">I do not want to recieve emails about offers and services from Pixelific Games Inc.</label>
                                </div>
                                <br>
                                <!-- recaptcha ui -->
                                <div class="g-recaptcha" data-sitekey="6LeJX1cUAAAAAECUWEbKk5Rxg12ff6iy3Ww6l4TI"></div>

                                <button type="submit" class="btn btn-primary">

                                    Register

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection