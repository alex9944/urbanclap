@extends('layouts.'.$merchant_layout)

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div>
    <div class="services_app__block">
        <ul class="nav nav-pills">
            <li>
                <a href="#food" data-toggle="tab" aria-expanded="false">Food</a>
            </li>
            <li><a href="#table" data-toggle="tab" aria-expanded="true">Table</a>
            </li>
            <li class="active"><a href="#appmt" data-toggle="tab" aria-expanded="true">Appointment</a>
            </li>
            <li><a href="#shop" data-toggle="tab" aria-expanded="true">Shop</a>
            </li>
            <li><a href="#service" data-toggle="tab" aria-expanded="true">Service</a>
            </li>
        </ul>
        <div class="tab-content clearfix">
            <div class="tab-pane" id="food">
                <h3 class="appmt_title">Click to Deactivate</h3>
                <div class="appmt_block">
                    <!-- start div box -->
                    <div class="divbox">
                        <a href="#" class="ui-link active">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="  wrapperbox">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Food Booking</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end div box -->
                </div>
                <div class="container">
                    <form class="">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Menu:<span class="red">*</span></label>
                                <select class="form-control" id="menu">
                                    <option>Select</option>
                                    <option>menu1</option>
                                    <option>menu2</option>
                                </select>
                            </div>
                            <div class="form-group mt">
                                <label for="email">Item Name:<span class="red">*</span></label>
                                <select class="form-control" id="itemname">
                                    <option>Select</option>
                                    <option>itemname1</option>
                                    <option>itemname2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="veg_non_veg paymentsel">
                                <ul>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link active">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox veg_box">
                                                    <div class="  veg_content">
                                                        <span>Veg</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox non_veg_box">
                                                    <div class="  non_veg_content">
                                                        <span>Non-Veg</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <!-- orignal, discount, stock -->
                            <div class="orginal_price">
                                <form>
                                    <ul>
                                        <li>
                                            <div class="form-group">
                                                <label for="email">Orginal Price:<span class="red">*</span></label>
                                                <input type="text" name="orginal_price" class="form-control">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="email">Discount Price:</label>
                                                <input type="text" name="orginal_price" class="form-control">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="email">Stock:<span class="red">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
<button type="button" class="btn btn-stock" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                    </span>
                                                    <input type="text" name="quant[2]" class="form-control input-number" value="10" min="1" max="100">
                                                    <span class="input-group-btn">
<button type="button" class="btn btn-stock" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <img src="images/apoyou.png" class="img-responsive" />
                        <div class="clearfix"></div>
                        <!-- add button-->
                        <div class="text-center">
                            <button class="btn btn-primary btn-add">Add</button>
                        </div>
                        <div class="col-sm-12">
                            <p class="text-right delivery_fee">Delivery Fee</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-right">
                                            <span class="amount_info_text">Amount</span> <span class="amount_info_table"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="">
                                                </label>
                                            </div>
                                        </th>
                                        <th>Item</th>
                                        <th>Menu</th>
                                        <th>Orignal Price</th>
                                        <th>Discount Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="">
                                                </label>
                                            </div>
                                        </td>
                                        <td>Sweet corn soup</td>
                                        <td>Soup</td>
                                        <td>150</td>
                                        <td>120</td>
                                        <td>12</td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="#" class="custom_pencil"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a href="#"><i class="fa fa-trash-o custom_trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-add">Save</button>
                        </div>
                </div>
            </div>
            <div class="tab-pane" id="table">
                <h3 class="appmt_title">Click to Deactivate</h3>
                <div class="appmt_block">
                    <!-- start div box -->
                    <div class="divbox">
                        <a href="#" class="ui-link active">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="  wrapperbox">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Table Booking</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end div box -->
                </div>
                <!-- date block start -->
                <div class="date_block  ">
                    <div class="container">
                        <form class="form-inline">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Start Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">End Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="tb_time">Time Interval between 2 Slots:<span class="red">*</span></label>
                                    <input type="email" class="form-control tb_perslot_text" id=""><i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="tb_perslot">No of people per time slot:<span class="red">*</span></label>
                                    <div class="input-group tb_time_text">
                                        <span class="input-group-btn">
<button type="button" class="btn btn-number" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                        </span>
                                        <input type="text" name="quant[2]" class="form-control input-number" value="10" min="1" max="100">
                                        <span class="input-group-btn">
<button type="button" class="btn btn-number" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                        </span>
                                    </div>
                                    <i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- holiday block start -->
                            <div class="holiday_block paymentsel col-sm-12">
                                <label for="email">Holiday<span class="red">*</span></label>
                                <ul>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link active">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Sunday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Moday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Tuesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>wednesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Friday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Saturday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- holiday block end -->
                            <div class="clearfix"></div>
                            <div class="text-center appmt_btn">
                                <button class="appmt_btn_save">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- date block end -->

            </div>
            <div class="tab-pane active" id="appmt">
                <h3 class="appmt_title">Click to Deactivate</h3>
                <div class="appmt_block">
                    <!-- start div box -->
                    <div class="divbox">
                        <a href="#" class="ui-link active">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="  wrapperbox">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Appointment Booking</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end div box -->
                </div>
                <!-- date block start -->
                <div class="date_block">
                    <div class="container">
                        <form class="form-inline">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Start Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">End Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email" class="ampt_time_interval">Time Interval between 2 Slots:<span class="red">*</span></label>
                                    <input type="email" class="form-control ampt_time_interval_input" id="email">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- holiday block start -->
                            <div class="holiday_block paymentsel">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Holidays<span class="red">*</span></label>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link active">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Sunday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Moday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Tuesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>wednesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Friday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Saturday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- holiday block end -->
                            <div class="clearfix"></div>
                            <div class="text-center appmt_btn">
                                <button class="appmt_btn_save">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- date block end -->
            </div>
            <!-- end appointment -->
            <div class="tab-pane" id="shop">
                <h3 class="appmt_title">Click to Activate</h3>
                <div class="shop_block">
                    <div class="divbox">
                        <a href="#" class="ui-link">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="wrapperbox deactive">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Appointment Booking</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="add_new_products_block">
                    <div class="add_new_product">
                        <p claas="">Add New Products</p>
                    </div>
                    <form id="add_new_product">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Category</label>
                                <select class="form-control" id="product_category">
                                    <option>Category 1</option>
                                    <option>Category 2</option>
                                    <option>Category 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Description</label>
                                <textarea class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <img src="images/apoyou.png" class="img-responsive">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Product Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">Product Code</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Original Price</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Discount Price</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Shipping Price</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Stock</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                        </span>
                                        <input type="text" name="quant[2]" class="form-control input-number" value="10" min="1" max="100">
                                        <span class="input-group-btn">
<button type="button" class="btn btn_add_new_product" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- upload new product start -->
                        <div class="clearfix"></div>
                        <div class="upload-btn-wrapper shop_block_upload">
                            <div class="upload-drop-zone grey_color" id="drop-zone">
                                Drop files anyhere to upload
                            </div>
                            <span class="or"> Or  </span>
                            <button class="btn_upload">Select Files</button>
                            <input type="file" name="myfile">
                        </div>
                        <!-- upload new product end -->
                        <div class="new_product_add_block">
                            <ul>
                                <li>
                                    <img src="images/new_pro1.png" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
                                    <img src="images/new_pro2.png" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
                                    <img src="images/new_pro4.png" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                                <li>
                                    <img src="images/add_pro.png" />
                                    <span><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a></span>
                                </li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-center add_product_btn">
                            <button class="btn btn-primary btn-add">Add</button>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="">
                                            </label>
                                        </div>
                                    </th>
                                    <th>Category</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Original Price</th>
                                    <th>Discount Price</th>
                                    <th>Shipping Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="">
                                            </label>
                                        </div>
                                    </td>
                                    <td>Mobile</td>
                                    <td>Samsung</td>
                                    <td>032</td>
                                    <td>18,000</td>
                                    <td>16,500</td>
                                    <td>1000</td>
                                    <td>1269</td>
                                    <td><img src="images/new_pro2.png" class="product_img" /></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a href="#"><i class="fa fa-trash-o custom_trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button class="btn btn-primary btn-add">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="service">
                <h3 class="appmt_title">Click to Deactivate</h3>
                <div class="appmt_block">
                    <!-- start div box -->
                    <div class="divbox">
                        <a href="#" class="ui-link  ">
                            <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <div class="  wrapperbox deactive">
                                <div class="pay-content appmt_span">
                                    <div><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <span>Service Booking</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end div box -->
                </div>
                <!-- date block start -->
                <div class="date_block  ">
                    <div class="container">
                        <form class="form-inline">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Start Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">End Time:<span class="red">*</span></label>
                                    <select class="form-control" id="start_time">
                                        <option>00.00</option>
                                        <option>90.00</option>
                                        <option>08.00</option>
                                    </select>
                                    <select class="form-control" id="ampm">
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="tb_time">Per hour charge:<span class="red">*</span></label>
                                    <input type="email" class="form-control tb_perslot_text" id=""><i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="tb_perslot">No of people per time slot:<span class="red">*</span></label>
                                    <div class="input-group tb_time_text">
                                        <span class="input-group-btn">
<button type="button" class="btn btn-number" data-type="minus" data-field="quant[2]">
<span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                        </span>
                                        <input type="text" name="quant[2]" class="form-control input-number" value="10" min="1" max="100">
                                        <span class="input-group-btn">
<button type="button" class="btn btn-number" data-type="plus" data-field="quant[2]">
  <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                        </span>
                                    </div>
                                    <i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="tb_time">Extra Hour charge:<span class="red">*</span></label>
                                    <input type="email" class="form-control tb_perslot_text" id=""><i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="col-md-7"> <img src="images/apoyou.png" class="img-responsive"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- holiday block start -->
                            <div class="holiday_block paymentsel col-sm-12">
                                <label for="email">Holiday<span class="red">*</span></label>
                                <ul>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link active">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Sunday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Moday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Tuesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>wednesday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Friday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="divbox">
                                            <a href="#" class="ui-link">
                                                <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
                                                <div class="wrapperbox holiday_box">
                                                    <div class="pay-content holiday_box_content">
                                                        <span>Saturday</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- holiday block end -->
                            <div class="clearfix"></div>
                            <div class="text-center appmt_btn">
                                <button class="appmt_btn_save">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- date block end -->

            </div>
        </div>
    </div>
</div>

@stop

@section('footer')
<script>
//Change Status Enable

$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var merchant_service_id = $(this).data('merchant_service_id');
	var host="{{ url('merchant/services/enable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'merchant_service_id': merchant_service_id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:enableStatus
	
	})
	return false;
}
function enableStatus(res){
	location.reload();
}
 	
	
	
//Change Status Disable

$(document).on("click", ".disable", disable);
function disable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var merchant_service_id = $(this).data('merchant_service_id');
	var host="{{ url('merchant/services/disable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'merchant_service_id': merchant_service_id, '_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:disableStatus
	
	})
	return false;
}
function disableStatus(res){ 
	location.reload();
}
</script>

@stop