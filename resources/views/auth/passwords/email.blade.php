@extends('layouts.main')

@section('head')
    {!! HTML::style('/assets/css/reset.css') !!}
@stop

@section('content')
		<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		<div class="panel panel-default resetForm">
		<h2 class="form-signin-heading text-center">Password Reset</h2>
        {!! Form::open(['url' => url('/password/email'), 'class' => 'form-signin' ] ) !!}

        @include('includes.status')
		@include('includes.errors')

        <!--<h2 class="form-signin-heading">Password Reset</h2>-->
        <label for="inputEmail" class="sr-only">Email address</label>
        {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email address', 'required', 'autofocus', 'id' => 'inputEmail' ]) !!}

        <br />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Send me a reset link</button>

        {!! Form::close() !!}
		</div>
		</div>
		<div class="col-md-4"></div>
		</div>
@stop