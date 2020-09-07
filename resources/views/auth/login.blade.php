@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header align-middle">{{ __('Login') }}</div>

                <div class="card-body">
                    <div>When you login for the first time ever, it will spit an error.  Just refresh and all will be fine.  This only ever happens once. (I have no idea why)</div>
                        <a class="btn btn-primary" href="/oauth/login" role="button">LOGIN</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
