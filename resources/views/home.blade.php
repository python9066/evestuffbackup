@extends('layouts.app')

@section('content')

@if(Auth::check() or Auth::viaRemember())
<app :username="{{ json_encode(Auth::user()->name) }}"></app>
@else
YOU ARE NOT LOGGED IN
@endif
@endsection
