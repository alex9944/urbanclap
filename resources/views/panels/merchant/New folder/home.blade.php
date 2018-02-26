@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
{{Auth::user()->email}}
    <h2 style="margin-bottom:40px;">Merchant User Panel Home Page</h2>

    <p><a href="{{ route('activated.protected') }}">Protected Page</a> - This page is protected with <code>activated</code> middleware.</p>

    <p><small>Users registered via Social providers are by default activated.</small></p>
@stop