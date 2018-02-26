@extends('layouts.main')

@section('head')
    {!! HTML::style('/assets/css/reset.css') !!}
    {!! HTML::style('/assets/css/parsley.css') !!}
	<style>
	.forgot{
		color: #16a9ec;
		font-size: 15px;
		font-weight: 600;
		font-style: italic;}
		.error{font-size: 11px;
		line-height: 24px;
		color: #a94442;
	}
	</style>	
@stop

@section('content')

        
		<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		<div class="panel panel-default resetForm">
			<h2 class="form-signin-heading text-center">Verify Account</h2>
			
			{!! Form::open(['url' => url('signup-verify'), 'class' => 'form-signin', 'data-parsley-validate' ] ) !!}

			@include('includes.errors')
			<?php /*
			@if (!$errors->any())
			<div class="alert alert-success">Your verification code sent to your email id</div>
			@endif
			*/?>

			<div class="form-group ">
				<label class="sr-only">Verification Code</label>
				<input type="text" class="form-control" name="verification_code" id="verification_code" value="" placeholder="Verification Code">
			</div>
			<div class="form-group">
				<a href="{{ url('resend-verification-code') }}" class="forgot pull-right">Resend Verification Code</a>
		   </div>

			<button class="btn btn-lg btn-primary btn-block register-btn" type="submit">Verify</button>


			{!! Form::close() !!}
		
		</div>
		</div>
		<div class="col-md-4"></div>
		</div>


@stop

@section('footer')

@stop