@extends('layouts.main')

@section('head')
    {!! HTML::style('/assets/css/reset-form.css') !!}
@stop

@section('content')
	<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		<div class="panel panel-default resetForm">
		<h2 class="form-signin-heading text-center">Set New Password</h2>
        {!! Form::open(['url' => url('/password/reset/'), 'class' => 'form-signin', 'method' => 'post' ] ) !!}

        @include('includes.errors')
		@include('includes.status')

        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        

        <label for="inputEmail" class="sr-only">Email address</label>
        {!! Form::email('email', null, [
            'class'                         => 'form-control',
            'placeholder'                   => 'Email address',
            'required',
            'id'                            => 'inputEmail',
            'data-parsley-required-message' => 'Email is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-type'             => 'email',
            'autofocus'
        ]) !!}

        <label for="inputPassword" class="sr-only">Password</label>
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required',  'id' => 'inputPassword' ]) !!}


        <label for="inputPasswordConfirmation" class="sr-only">Password Confirmation</label>
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password confirmation', 'required',  'id' => 'inputPasswordConfirmation' ]) !!}


        <button class="btn btn-lg btn-primary btn-block" type="submit">Change</button>

        {!! Form::close() !!}
		
		</div>
		</div>
		<div class="col-md-4"></div>
		</div>

@stop