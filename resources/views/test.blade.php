@extends('layouts.app')

@section('content')
<div class="container">
<div>
{{-- @foreach ($system as $system)
<div>{{$system->region->region_name}} - {{$system->constellation->constellation_name}}  - {{$system->system_name}}</div>
@endforeach --}}
{{ now() }}
</div>
</div>
@endsection
