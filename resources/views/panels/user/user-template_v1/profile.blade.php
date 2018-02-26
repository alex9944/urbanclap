@extends('layouts.'.$user_layout)

@section('head')

@stop

@section('content')
<style type="text/css">
	input,select{
     background: transparent !important;
    border: none !important;
    border-bottom: 1px solid #d9d3d3 !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border-radius: 0!important;
}
label{
	color: #087aae;
    font-weight: 500 !important;
    font-size: 18px;

}
.error{font-size: 12px; color:red;}
</style>
	<form class="form-horizontal" method="post" action="{{url('user/update-profile')}}" role="form" enctype="multipart/form-data">	
 <div class="main-content">
 	<section class="profile__img_block">
 		<div class="container">
 			<div class="row">
	 		<div class="col-md-12">
	 		<div class="profile__img">
	 		  <div class="card hovercard">
                <div class="avatar">
				
				<?php if(Auth::user()->image){?>				
				  <img alt="" src="{{ url('uploads/thumbnail') }}/{{ Auth::user()->image }}" class="profile-pic">
				<?php }else{?>
                   <img alt="" src="{{ asset($user_layout . '/images/user-icon.png') }}" class="profile-pic">
				   	<?php }?>
                                            <!-- plus icons start -->
						<div class="profile_plus_icon">
						<input  class="file-upload" type="file" name="photo" accept="image/*" style="display:none"/> 
						<a href="#" class="upload-button">
						<i class="fa fa-plus" aria-hidden="true"></i>
						</a>
					
						</div>
					
	 			        <!-- plus icons end -->
                    <!-- profile info -->
                    <div class="info">
                    <div class="profile_title">
                        <a target="_blank" href="#">{{ Auth::user()->first_name }}</a>
                    </div>
                    <div class="desc"><?php if(Auth::user()->bio){?> <p>{{ Auth::user()->bio }}</p><?php }?></div>
                    
                    </div>
                    <!-- profile info end -->                  
                </div>
            </div>
	 		 
	 	 	 			
	 		</div>
	 		</div>
	 	 
         </div>        
         </div>
 	</section>
 	<div class="clearfix"></div>
 	<!-- profile  end -->
<!-- tabs start -->
 	<section>
 	<div class="container">
 			<div class="row mt10">
	 		 	<div class="col-md-12"> 

                        				
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 

								
      <div class=" col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="form-elem-1">Full Name</label>
            <input type="text" id="form-elem-1" class="form-control"  name="first_name" id="first_name"  value="<?php echo old('first_name', $user->first_name); ?>"/>
			<span class="error">{{ ($errors->has('first_name')) ? $errors->first('first_name') : ''}}</span>
          </div>
           <div class="form-group">
            <label for="form-elem-1">E-mail Address</label>
            <input type="text" id="form-elem-1" class="form-control" disabled value="<?php echo $user->email; ?>" />
          </div>
         <div class="form-group">
            <label for="form-elem-2">Gender</label>
            <select class="form-control" name="gender">
				<option value="">Choose</option>
            	<option <?php if($user->gender=='Male'){?> selected <?php }?>>Male</option>
            	<option <?php if($user->gender=='Female'){?> selected <?php }?>>Female</option>
            </select>
			<span class="error">{{ ($errors->has('gender')) ? $errors->first('gender') : ''}}</span>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="form-elem-2">Web Site</label>
				<input type="text" class="form-control" name="website" id="last_name" value="<?php echo old('website', $user->website); ?>"/>
				<span class="error">{{ ($errors->has('website')) ? $errors->first('website') : ''}}</span>
          </div>
           <div class="form-group">
            <label for="form-elem-2">Phone Number</label>
           <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?php echo old('mobile_no', $user->mobile_no); ?>"/>
		   <span class="error">{{ ($errors->has('mobile_no')) ? $errors->first('mobile_no') : ''}}</span>
          </div>
           <div class="form-group">
            <label for="form-elem-2">Bio</label>
			<input type="text" class="form-control" name="bio" id="bio" value="<?php echo old('bio', $user->bio); ?>"/>
			<span class="error">{{ ($errors->has('bio')) ? $errors->first('bio') : ''}}</span>
          
          </div>
        </div>
     
      </div>

<div class=" ">
        <div class="col-md-3 col-md-offset-4 text-right">
          <div class="form-group">
            <button type="submit" class="btn btn-default green" >Update</button>
          </div>
        </div>
       </div> 
  	<input type="hidden" name="_token" value="{{csrf_token()}}">
								
    


                        	</div>

	</div>
			</div>
			</div>
 	</section>
 	<!-- tabs end -->



</div><!-- Main content end -->
</form>
@stop

@section('footer')

@stop