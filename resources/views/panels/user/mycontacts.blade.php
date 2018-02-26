@extends('layouts.main')

@section('head')

@stop

@section('content')
<?php /*@foreach($users as $users)
    {{$users}}
@endforeach
*/?>
<style>
.well{
	background-color: #ffebd7;
	border: 1px solid #ff7f00;
}
.fa-window-close{
    float: right;
    margin-top: -15px;
    margin-right: -10px;
}
</style>
<section>
	<div class="container">
		<div class="row m-t30">
			<div class="col-sm-3">
				@include('panels.user.myaccount_left_menu')				
			</div>

			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">My Contacts</h2>


					@foreach($contacts as $contact)
					<div class="col-sm-3 well">
						<a href="{{url('')}}/user/removecontact/{{$contact->listing->id}}"><i class="fa fa-window-close" aria-hidden="true"></i></a>
						<address>
							<h3><a href="{{ url('') }}/{{$contact->listing->slug}}">{{$contact->listing->title}}</a></h3><br> 
							Visit at: <b>{{$contact->listing->website}}</b><br/>
							{{$contact->listing->address1}}<br/>
							pincode: {{$contact->listing->pincode}}<br/>
							Contact at: {{$contact->listing->phoneno}}
						</address>
					</div>
					<div class="col-sm-1">
					</div>
					@endforeach
				</div>

			</div><!--features_items-->
		</div>
	</div>
</section>
@stop