<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>apo you</title>
	
	<link rel='stylesheet' type='text/css' href="{{ asset('print_local_vendor_marketing/css/style.css') }}" />
	<link rel='stylesheet' type='text/css' href="{{ asset('print_local_vendor_marketing/css/print.css') }}" media="print" />
	<script type='text/javascript' src="{{ asset('print_local_vendor_marketing/js/jquery-1.3.2.min.js') }}"></script>
	<script type='text/javascript' src="{{ asset('print_local_vendor_marketing/js/example.js') }}"></script>

<style type="text/css">

#page-wrap {
    width: 800px;
    margin: 0 auto;
}
	.logo img{
  width: 380px;
    margin-top: 4%;
    /* margin: 0 auto; */
    margin-left: 28%;
	}
.divbox{
    position:relative;
    padding-top:20px;
  
}

.wrapperbox {
      height: 195px;
    background: white;
    border-radius: 10px;
    -webkit-box-shadow: 0px 0px -1px rgba(0,0,0,0.3);
    -moz-box-shadow: 0px 0px 8px rgba(0,0,0,0.3);
    border: 2px solid #03A9F4;
    width: 208px;
    margin-left: 37%; 
    

}

.text-input {
        border-bottom: solid 1px #9E9E9E;
    border-top: none;
    border-left: none;
    border-right: none;
    width: 41%;
    height: 26px;
    padding: 2px 4px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    margin-left: 2%;
}
input[type="text"]:focus{
   
    outline: none;

}
h5{
  font-size: 24px;
    font-family: sans-serif;
    font-weight: 600;
}

.center{
      margin-left: 214px;
    margin-top: 2%;
}
.div4 {
    width: 33.33333333333333%;
}
.div3{
    width: 25%;
}
.div5{

    width: 41.66666666666667%;
 
}
.div3,.div4,.div5{
	    float: left;
}
.cenrew{
	    margin-left: 306px;
    margin-top: 2%;
}
</style>
</head>

<body>

	<div id="page-wrap">

	 
		<div id="identity" style="">
	 

            <div id="" class="logo">
 
              <img id="image" src="{{ asset('print_local_vendor_marketing/images/logo.png') }}" alt="logo" />
            </div>
		

		 <div class=" col-md-4 col-md-offset-3">
      <div class="divbox">
                  <a   class=" ">
           
          
               <div class="  wrapperbox">
				<h3 style="
    text-align: -webkit-center;
    margin-bottom: 1%;
    font-size: 17px;
    font-weight: 600;
    color: #1f5ca9;
    margin-right: -2%;
    margin-top: 2%;
">ID : {{$marketing_user->unique_code}} </h3>
                  <div class="pay-content" style="text-align:center;">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->margin(0)->size(160)->generate($marketing_user->unique_code)) !!} ">
                  </div> 
                </div>
                <div style="margin-left:38%;
    font-size: 19px;
    color: #f05736;
    font-weight: 700;">(Scan for Rewards)</div>
          </a>
            </div>
    </div>

<div class="center">
 

<form >
<label for="inputType" class="col-md-2 control-label"><img src="{{ asset('print_local_vendor_marketing/images/icon_2.png') }}" style="    width: 50px; "></label>
  <input type="text" name=" " value=" " class="text-input">
  <br>
<label for="inputType" class="col-md-2 control-label"><img src="{{ asset('print_local_vendor_marketing/images/icon_1.png') }}" style="    width: 50px; "></label>
  <input type="text" name=" " value=" " class="text-input">
   
</form> 
 
</div>

<div style="    margin-top: 2%;">
 <div class="div4"><h5>Install <span style="color: #1f5ca9">apo<span style="color: #f05736;">you</span></span> app</h5>
<img src="{{ asset('print_local_vendor_marketing/images/mobile_1.png') }}" style="width: 72%;
    margin-left: 16%;
    height: 266px;">

  </div>
  <div class="div3">
    <img src="{{ asset('print_local_vendor_marketing/images/arrow.png') }}" style=" width: 84%;
    height: 57px;
    margin-top: 73%;">

  </div>
  <div class="div5"><h5>Scan <span style="color: #f05736;">Qr</span>&ensp;code/<span style="color: #f05736;">&ensp;Go</span>&ensp; Live </h5>
 
<img src="{{ asset('print_local_vendor_marketing/images/stall.png') }}" style="width: 166px;
    height: 266px;
    margin-bottom: 29px;">
<img src="{{ asset('print_local_vendor_marketing/images/mobile_2.png') }}" style="width: 163px;
    height: 265px;
    margin-bottom: 32px;" class="pull-right">


  </div>
  

</div>

<div>
	
	<div class="cenrew">
 <div class="col-md-4">

  </div>
  <div class="col-md-4">

    <h5> <span style="color: #1f5ca9">EARN<span style="color: #f05736;     margin-left: 3%;">REWARDS</span></span></h5>
    <img src="{{ asset('print_local_vendor_marketing/images/giftbox.png') }}" style="width: 36%;">

  </div>
  <div class="col-md-4">
  


  </div>
	</div>
</div>

		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">
  
		
		</div>
		
	 
	 
	
	</div>
	
</body>

</html>