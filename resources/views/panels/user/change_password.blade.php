@extends('layouts.main')

@section('head')

@stop

@section('content')
<?php /*@foreach($users as $users)
    {{$users}}
@endforeach
*/?>
<section>
    <div class="container">
        <div class="row m-t30">
            <div class="col-sm-3">
                @include('panels.user.myaccount_left_menu')				
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Change Password</h2>

                        <div class="col-sm-6">
                            <div class="">
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 

								@if ($errors->any())
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
								@endif
							
								<form class="form-horizontal" method="post" action="">								
									<div class="form-group">
										<label for="cur_password" class="cols-sm-2 control-label">Current Password</label>
										<input type="password" class="form-control" name="cur_password" id="cur_password" value=""/>
									</div>
									
									<div class="form-group">
										<label for="password" class="cols-sm-2 control-label">New Password</label>
										<input type="password" class="form-control" name="password" id="password" value=""/>
									</div>
									
									<div class="form-group">
										<label for="password_confirmation" class="cols-sm-2 control-label">Confirm New Password</label>
										<input type="password" class="form-control" name="password_confirmation" value="" />
									</div>
									
									<div class="form-group">											
										<button type="submit" class="btn btn--ys btn--md">Update</button>
									</div>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
								</form>
							</div>
                        </div>


                </div>

			</div><!--features_items-->
		</div>
	</div>
</section>
@stop