@extends('layouts.main')

@section('content')

    @include('partials.status-panel')

    <div class="jumbotron" style="margin-top:-20px;">
        <h1>Contact Us</h1>
		@if(Session::has('message'))
			<div class="alert alert-info">
			  {{Session::get('message')}}
			</div>
		@endif
		<div class="col-md-3" ></div>
		<div class="col-md-6" >
				 <form name="actionForm" action="{{url('store')}}" method="post" class="form"/>   

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
						<div class="form-group">
							{!! Form::label('Your Name') !!}
							{!! Form::text('name', null, 
								array('required', 
									  'class'=>'form-control', 
									  'placeholder'=>'Your name')) !!}
						</div>

						<div class="form-group">
							{!! Form::label('Your E-mail Address') !!}
							{!! Form::text('email', null, 
								array('required', 
									  'class'=>'form-control', 
									  'placeholder'=>'Your e-mail address')) !!}
						</div>

						<div class="form-group">
							{!! Form::label('Your Message') !!}
							{!! Form::textarea('message', null, 
								array('required', 
									  'class'=>'form-control', 
									  'placeholder'=>'Your message')) !!}
						</div>

						<div class="form-group">
							{!! Form::submit('Contact Us!', 
							  array('class'=>'btn btn-primary')) !!}
						</div>
			</form>
        </div> 
		<div class="col-md-3" ></div>
       <div style="clear:both"></div>      
    </div>

@stop