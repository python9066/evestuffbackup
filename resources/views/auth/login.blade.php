@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header align-middle">{{ __('Login') }}</div>

                <div class="card-body">
                    {{-- <form method="POST" action="{{ route('login') }}"> --}}
                        <a class="btn btn-primary" href="/oauth/login" role="button">LOGIN</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
