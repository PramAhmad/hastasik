@extends('usermodule::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('usermodule.name') !!}</p>
@endsection
