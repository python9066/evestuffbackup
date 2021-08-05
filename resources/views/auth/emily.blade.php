@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header align-middle">{{ __('Login') }}</div>

                <div class="card-body">
                    <div>STOP PRESSING THE WRONG BUTTON EMILY</div>
                        <a class="btn btn-primary" href="/mcampaigns" role="button">PRESS THIS</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
