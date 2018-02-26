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

                        		<form class="form-horizontal" method="post" action="{{url('user/change-password')}}" role="form">				
								@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 

								
      <div class=" col-md-12">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="form-elem-1">Current Password</label>
           <input type="password" class="form-control" name="cur_password" id="cur_password" value=""/>
			<span class="error">{{ ($errors->has('cur_password')) ? $errors->first('cur_password') : ''}}</span>
          </div>
           <div class="form-group">
          <label for="form-elem-2">Confirm New Password</label>
          <input type="password" class="form-control" name="password_confirmation" value="" />
		   <span class="error">{{ ($errors->has('password_confirmation')) ? $errors->first('password_confirmation') : ''}}</span>
          </div>
    
        </div>
        <div class="col-md-6 col-sm-6">
         
           <div class="form-group">
		    <label for="form-elem-1">New Password</label>
           <input type="password" class="form-control" name="password" id="password" value=""/>
		    <span class="error">{{ ($errors->has('password')) ? $errors->first('password') : ''}}</span>
            
          </div>
          <div class="form-group">
            <label for="form-elem-2"><br /></label>
				<br /><br /><br />
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
								</form>
    


                        	</div>

	</div>
			</div>
			</div>
 	</section>
 	<!-- tabs end -->



</div><!-- Main content end -->
@stop

@section('footer')

@stop