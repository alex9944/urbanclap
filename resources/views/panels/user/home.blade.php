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
                    <h2 class="title text-center">Profile</h2>

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
										<label for="name" class="cols-sm-2 control-label">Email</label>
										<input type="text" class="form-control" name="email" id="email" disabled value="<?php echo $user->email; ?>"/>
									</div>
									
									<div class="form-group">
										<label for="name" class="cols-sm-2 control-label">First Name<span style="color:#e04b4a;">*</span></label>
										<input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo old('first_name', $user->first_name); ?>"/>
									</div>
									<div class="form-group">
										<label for="name" class="cols-sm-2 control-label">Last Name<span style="color:#e04b4a;">*</span></label>
										<input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo old('last_name', $user->last_name); ?>"/>
									</div>										
									<div class="form-group">
										<label for="name" class="cols-sm-2 control-label">Phone Number<span style="color:#e04b4a;">*</span></label>
										<input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?php echo old('mobile_no', $user->mobile_no); ?>"/>
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