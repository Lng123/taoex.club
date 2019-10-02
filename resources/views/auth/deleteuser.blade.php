@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Unsubscribe</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('deleteUserAction', $userinfo->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                            <div class="form-group">
                                <p> Caution: When you click the confirm button below 
                                    which means you agree to delete any register information from Taoex and you can't log on anymore.</p>
                                <button type="submit" class="btn btn-primary">Confirm</button> 
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

