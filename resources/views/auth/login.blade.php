@extends('layouts.main')

@section('head')
   {!! HTML::style('/assets/css/signin.css') !!}
    {!! HTML::style('/assets/css/parsley.css') !!}

<style type="text/css">
.row{
	margin-right:0 !important;  
}
 .nav-tabs li{
 	    width: 50%;
 }
 .nav-tabs li.active a, .nav-tabs li.active a:hover, .nav-tabs li.active a:focus{
 	    background: #ffffff;
    /* border-left-color: #EEE; */
    /* border-right-color: #EEE; */
    /* border-top: 3px solid #CCC; */
    /* color: #CCC; */
    border-bottom: 3px solid #ef6c4f;
 }
 .panel-default>.panel-heading{
 	    color: #333;
    background-color: #ffffff;
    border-color: #fff
 }
 .nav-tabs li a:hover{
 	    border-bottom-color: transparent;
    border-top: 3px solid #fff;
    box-shadow: none;
 }
 .nav-tabs li a, .nav-tabs li a:hover{
 	 background: #ffffff;
    border-bottom: none;
    border-left: 1px solid #fff;
    border-right: 1px solid #fff;
    border-top: 3px solid #fff;
    color:#acacac;
    text-align: -webkit-center;
    font-size: 18px;
    font-weight: 600;
 }
 .nav-tabs li.active a, .nav-tabs li.active a:hover, .nav-tabs li.active a:focus{
 	    background: #FFF;
    border-left-color: #fff;
    border-right-color: #fff;
    border-top: 3px solid #fff;
      color: #6e6d6d;
 }

 input,select{
     background: transparent !important;
    border: none !important;
    border-bottom: 1px solid #d9d3d3 !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border-radius: 0!important;
}

input[type="text"]:focus,
select.form-control:focus {
  -webkit-box-shadow: none;
  box-shadow: none;
}
label{
	
		    color: #9d9999;
    font-weight: 500;
    font-size: 16px;

}
.tab-content{
	border: none !important;
}
 
input:focus {
    border-bottom: 1px solid #f05736 !important;
}
.divbox{
	 height: 128px;
    border: 2px solid #3a8acb;
    border-radius: 13px;
    font-size: 21px;
    padding: 22px;
    text-align: -webkit-center;
    line-height: 33px;
    color: #898686;
    background-color: #e3e0e08f;
   <!--  width: 167px; -->
    cursor: pointer;
}
.divbox i{
	    font-size: 58px;
    color: #3a8acb;
}
 .required:after { content:" *"; }
 .socialsign{
     padding: 10px;
    width: 66px;
    height: 54px;
    font-size: 41px;
    border-radius: 4px
 }
 .or{
 	    font-size: 16px;
    font-weight: 700;
    color: #817d7d;
    padding: 50px;
 }
 .socialfb{
 	background-color:#3b5998;
 }
 .socialsign i{
color:white;
 }
 .socialtw{
 	background-color: #1da1f2;
 }
 .socialgo{
 	background-color: #db4437;
 }
 .forgot
 {    color: #16a9ec;
    font-size: 15px;
    font-weight: 600;
    font-style: italic;}
	.error{font-size: 11px;
    line-height: 24px;
    color: #a94442;}
</style>	

@stop

@section('content')


		
		    
			
   <div class="main-content">
        <div class="row">
			<div class="col-md-6 col-md-offset-3 mt10">
 	             <div class=" ">
                    <div class="panel with-nav-tabs panel-default">
                         <div class="panel-heading">
                              <ul class="nav nav-tabs">
                                 <li class="active" id="signin"><a href="#tab1default" data-toggle="tab">SIGN IN</a></li>
                                 
                               </ul>
                            </div>
                          <div class="panel-body">
                             <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                  <div class="col-md-10 col-md-offset-1">
								   @include('includes.errors')
                   {!! Form::open(['url' => url('login'), 'class' => 'form-signin', 'data-parsley-validate' ] ) !!}
                          <div class="form-group">
                           <label for="inputEmail" class="control-label">Email Address:	</label>
						       {!! Form::email('email', null, [
                                 'class'                         => 'form-control',
                                 'placeholder'                   => '',
                                 'required',
                                 'id'                            => 'inputEmail',
                                 'data-parsley-required-message' => 'Email is required',
                                 'data-parsley-trigger'          => 'change focusout',
                                 'data-parsley-type'             => 'email'
                                    ]) !!}
						   
                                 <!--<input type="email" name="email" class="form-control" id="name" value=" "> -->
                           </div>
						   
                       <div class="form-group">
                          <label for="name" class="control-label">Password</label>
                               <!-- <input type="password" name="password" class="form-control" id="password" value=""> -->
						    {!! Form::password('password', [
                             'class'                         => 'form-control',
                             'placeholder'                   => '',
                             'required',
                             'id'                            => 'inputPassword',
                             'data-parsley-required-message' => 'Password is required',
                             'data-parsley-trigger'          => 'change focusout',
                             'data-parsley-minlength'        => '6',
                             'data-parsley-maxlength'        => '20'
        ]) !!}
                            </div>
							
                           <div class="form-group">
                               <!-- <a href="{{ url('password/reset') }}" class="pull-right forgot ">Forgot password</a>-->
                           </div>
                          <div class="form-group">
                               <button  class="form-control btn btn-primary " >Sign in</button>
                           </div>
						   
                        	{!! Form::close() !!}
                        </div>

                        </div>
                        <div class="tab-pane fade" id="tab2default">
                        	<div class="col-md-12 signupbtndiv">
                        	<div class="col-md-12  col-sm-12   col-xs-12">
                        		 <div class="col-md-5 col-sm-4 col-xs-12 divbox userbtn">
                        		<div>	<i class="fa fa-id-card-o" aria-hidden="true"></i></div>
                        		User
                        	</div>
                        	<div class=" col-md-5 col-sm-4 col-xs-12 pull-right divbox  vendorbtn" >
                        	<div>	<i class="fa fa-id-card-o" aria-hidden="true"></i></div>
                        		Vendor
                        	</div>
                        	</div>
                        		

                        	</div>

                <div class="col-md-12 userform" style="display: none;">
                    <div class="col-md-10 col-md-offset-1">
				 @include('includes.errors')
                        {!! Form::open(['url' => url('register'), 'class' => 'form-signin', 'data-parsley-validate' ] ) !!}
						
                        <div class="form-group">
                           <label for="inputFirstName" class="control-label">Name</label>
                             <!--<input type="text" name="name" class="form-control" id="name" value=" "> -->
							 {!! Form::text('first_name', null, [
                             'class'                         => 'form-control',
                             'placeholder'                   => '',
                             'required',
                             'id'                            => 'inputFirstName',
                             'data-parsley-required-message' => 'Name is required',
                             'data-parsley-trigger'          => 'change focusout',
                             'data-parsley-minlength'        => '2',
                             'data-parsley-maxlength'        => '32'
                               ]) !!}
							   <span class="error">{{ ($errors->has('first_name')) ? $errors->first('first_name') : ''}}</span>
                         </div>
                <div class="form-group">
                     <label for="inputEmail" class="control-label">Email</label>
                           <!--<input type="email" name="email" class="form-control" id="email" value=" "> -->
						     {!! Form::email('email', null, [
                                 'class'                         => 'form-control',
                                 'placeholder'                   => '', 
								 'required',
                                 'id'                            => 'inputEmail',
                                 'data-parsley-required-message' => 'Email is required',
                                 'data-parsley-trigger'          => 'change focusout',
                                 'data-parsley-type'             => 'email'
                               ]) !!}
							
                        </div>
                <div class="form-group">
                   <label for="inputMobile" class="control-label">Mobile</label>
                     <!-- <input type="text" name="mobile" class="form-control" id="mobile" value=""> -->
					  {!! Form::text('mobile', null, [
            'class'                         => 'form-control',
            'placeholder'                   => '',
			'required',
            'id'                            => 'inputMobile',
            'data-parsley-required-message' => 'Mobile No is required',
            'data-parsley-trigger'          => 'change focusout'
        ]) !!}

                    </div>
               <div class="form-group">
                    <label for="inputreferal" class="control-label">Referal ID (Optional)</label>
                        <!--<input type="text" name="referal" class="form-control" id="referal" value=""> -->
						{!! Form::text('referral_vendor_code', null, [
                          'class'                         => 'form-control',
                          'placeholder'                   => '',
						  
                          'id'                            => 'inputreferal',
                          'data-parsley-required-message' => 'Referral Code is required',
                          'data-parsley-trigger'          => 'change focusout',
                          'data-parsley-minlength'        => '2',
                          'data-parsley-maxlength'        => '32'
                           ]) !!}
                 </div>
                 <div class="form-group">
                     <label for="inputPassword" class="control-label">Password</label>
                       <!--<input type="password" name="password" class="form-control" id="password" value=""> -->
					   
					   {!! Form::password('password', [
                        'class'                         => 'form-control',
                        'placeholder'                   => '',
                        'required',
                        'id'                            => 'inputuserPassword',
                        'data-parsley-required-message' => 'Password is required',
                        'data-parsley-trigger'          => 'change focusout',
                        'data-parsley-minlength'        => '6',
                        'data-parsley-maxlength'        => '20'
                         ]) !!}
					
                    </div>
					  <div class="form-group">
                     <label for="inputPassword" class="control-label">Confirm Password</label>
                       <!--<input type="password" name="password" class="form-control" id="password" value=""> -->
					   
					  {!! Form::password('password_confirmation', [
            'class'                         => 'form-control',
            'placeholder'                   => '',
            'required',
            'id'                            => 'inputPasswordConfirm',
            'data-parsley-required-message' => 'Password confirmation is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-equalto'          => '#inputuserPassword',
            'data-parsley-equalto-message'  => 'Not same as Password',
        ]) !!}
					  
                    </div>
                 <div class="form-group">
				 <?php /*<input type="submit" name="user_register" value="Register" class="form-control btn btn-primary" style="background-color: #CCC !important;" >                */?>
				 <button type="submit" class="form-control btn btn-primary " name="user_register" >Register</button>
                  </div>
                        	{!! Form::close() !!}
                    </div>
                </div>
                        	
							
	<div class="col-md-12 vendorform" style="display: none;">
              <div class="col-md-10 col-md-offset-1">
			   @include('includes.errors')
                {!! Form::open(['url' => url('register'), 'class' => 'form-signin', 'data-parsley-validate' ] ) !!}
				
				
              <div class="form-group">
               <label for="category" class="control-label">Category</label>
			   
			   <select name="category_id" id="category_id" class="form-control category">
			<option value="">Category</option>
			@foreach($categories as $category)
			<option value="{{$category->c_id}}" @if(old('category_id') == $category->c_id) selected @endif>{{$category->c_title}} </option>
			@endforeach
		</select>
			   
			  
			</div>
            <div class="form-group">
                 <label for="inputFirstName" class="control-label">Name</label>
                      <!--<input type="text" name="name" class="form-control" id="name" value=" "> -->
			{!! Form::text('first_name', null, [
            'class'                         => 'form-control',
            'placeholder'                   => '',
			'required',
            'id'                            => 'inputFirstName',
            'data-parsley-required-message' => 'Name is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-minlength'        => '2',
            'data-parsley-maxlength'        => '32'
        ]) !!}
             </div>
             <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <!--<input type="email" name="email" class="form-control" id="email" value=" "> -->
			{!! Form::email('email', null, [
            'class'                         => 'form-control',
            'placeholder'                   => '',
			'required',
            'id'                            => 'inputEmail',
			'data-parsley-required-message' => 'Email is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-type'             => 'email'
            ]) !!}
             </div>
               <div class="form-group">
                <label for="inputMobile" class="control-label">Mobile</label>
                    <!-- <input type="text" name="mobile" class="form-control" id="mobile" value=""> -->
             {!! Form::text('mobile', null, [
            'class'                         => 'form-control',
            'placeholder'                   => '',
			'required',
            'id'                            => 'inputMobile',
            'data-parsley-required-message' => 'Mobile No is required',
            'data-parsley-trigger'          => 'change focusout'
               ]) !!}
           </div>
             <div class="form-group">
                  <label for="inputLastName" class="control-label">Referal ID (Optional)</label>
                     <!--<input type="text" name="referal" class="form-control" id="referal" value=""> -->
					 {!! Form::text('referral_vendor_code', null, [
                          'class'                         => 'form-control',
                          'placeholder'                   => '',
						  
                          'id'                            => 'inputreferal',
                          'data-parsley-required-message' => 'Referral Code is required',
                          'data-parsley-trigger'          => 'change focusout',
                          'data-parsley-minlength'        => '2',
                          'data-parsley-maxlength'        => '32'
                           ]) !!}
			
             </div>
             <div class="form-group">
                 <label for="inputPassword" class="control-label">Password</label>
                   <!-- <input type="password" name="password" class="form-control" id="password" value=""> -->
				   {!! Form::password('password', [
                        'class'                         => 'form-control',
                        'placeholder'                   => '',
                        'required',
                        'id'                            => 'inputpPassword',
                        'data-parsley-required-message' => 'Password is required',
                        'data-parsley-trigger'          => 'change focusout',
                        'data-parsley-minlength'        => '6',
                        'data-parsley-maxlength'        => '20'
                         ]) !!}
		    
             </div>
				  <div class="form-group">
					 <label for="inputPassword" class="control-label">Confirmation Password </label>
					  {!! Form::password('password_confirmation', [
				'class'                         => 'form-control',
				'placeholder'                   => '',
				'id'                            => 'inputPasswordConfirm',
				'data-parsley-required-message' => 'Password confirmation is required',
				'data-parsley-trigger'          => 'change focusout',
				'data-parsley-equalto'          => '#inputpPassword',
				'data-parsley-equalto-message'  => 'Not same as Password',
			]) !!}
				
				 </div>
			
			 
           <div class="form-group">
		   <input type="hidden" name="user_role" value="merchant" />
		    <?php /*<input type="submit" name="user_register" value="Vendor Register" class="form-control btn btn-primary" style="background-color: #CCC !important;" >  */?>
           <button type="submit" class="form-control btn btn-primary " name="user_register" >Vendor Register</button>
            </div>
			{!! Form::close() !!}
                        </div>
					</div>
                  </div>
                </div>
              </div>
            </div>
			
            <?php /*
			<div class="text-center or" >Or Use social login
                <div class="row mt10">
					<div class="col-md-12 col-md-offset-1">
						<div class="col-md-4 ">
						   <div class="socialsign socialfb"><a href="https://en-gb.facebook.com/login/" target="_blank"><i class="fa fa-facebook"></i></a></div>
				        </div>
						<div class="col-md-4  ">
							<div class="socialsign socialtw"><a href="https://twitter.com/login?lang=en" target="_blank"><i class="fa fa-twitter"></i></a></div>
						</div>
						<div class="col-md-4  ">
							<div class="socialsign socialgo"><a href="https://plus.google.com/discover" target="_blank"><i class="fa fa-google-plus"></i></a></div>
						</div>
					</div>
				</div>
            </div>
			*/?>
        </div>

			</div>
			</div>

	 

 </div>

@stop

@section('footer')

<script type="text/javascript">
			$(document).ready(function(){
					$(document).on('click','.userbtn',function(){
							 $('.userform').show();
							 $('.vendorform').hide();
							 $('.signupbtndiv').hide();
					});
					$(document).on('click','.vendorbtn',function(){
							 $('.userform').hide();
							 $('.vendorform').show();
							 $('.signupbtndiv').hide();
					});
					$('a[href="#tab2default"]').click(function(){
							  $('.userform').hide();
							 $('.vendorform').hide();
							 $('.signupbtndiv').show();
					});
			});
			
				
</script>
<script type="text/javascript">
$(document).ready(function(){
					var register_type ="{{ old('user_register') }}"; 
					if(register_type=='Register')
					{												
						
						$("#tab2default").addClass( "active in" );
						$("#tab1default").removeClass( "active" );						
						$("#signup").addClass( "active" );
						$("#signin").removeClass( "active" );
						$(".vendorform").css({ display: "none" });
						$(".userform").css({ display: "block" });
						$(".signupbtndiv").css({ display: "none" });
					
										
					
					}else if(register_type=='Vendor Register'){
						$("#tab2default").addClass( "active in" );
						$("#tab1default").removeClass( "active" );						
						$("#signup").addClass( "active" );
						$("#signin").removeClass( "active" );
						$(".userform").css({ display: "none" });
						$(".vendorform").css({ display: "block" });
						$(".signupbtndiv").css({ display: "none" });
						
						
					}
					
					});
					</script>
<script type="text/javascript">
        window.ParsleyConfig = {
            errorsWrapper: '<div style="margin-top: 10px"></div>',
            errorTemplate: '<span class="error-text"></span>',
            classHandler: function (el) {
                return el.$element.closest('input');
            },
            successClass: 'valid',
            errorClass: 'invalid'
        };
    </script>
    
	 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.5.0/parsley.min.js"></script>

@stop