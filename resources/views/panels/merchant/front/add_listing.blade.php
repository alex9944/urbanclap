@extends('layouts.main')

<style>
.tab-pane{    padding-top: 30px;}
</style>

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

    @include('partials.status-panel')

	
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row mt-xl">
				<div class="col-md-12">
				
					<h2>Add Free Listing</h2>
					
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">General Info</a></li>
						<li><a data-toggle="tab" href="#menu1">Address & Location</a></li>
						<li><a data-toggle="tab" href="#menu2">SEO & Description</a></li>
						<li><a data-toggle="tab" href="#menu3">Images</a></li>
					</ul>

					<form method="post" action="{{ url('merchant/add-listing') }}" class="form-horizontal">
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
							<div class="form-group required">
								<label class="control-label col-sm-3" for="title">Listing Title</label>
								<div class="col-sm-7">
								<input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3" for="category">Main Category</label>
								<div class="col-sm-7">
								<select name="category" id="category" class="form-control category">	<option value="">---Choose---</option>				  
									 @foreach ($categories as $category)
									 <option value="{{ $category->c_id }}" @if( old('category') == $category->c_id ) selected @endif>
									 {{ $category->c_title }}
									 </option>
									 @endforeach
								</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3" for="scategory">Sub Category</label>
								<div class="col-sm-7">
								<select name="scategory" id="scategory"  class="form-control"> 
									<option value="">---Choose---</option>
									@foreach ($subcategory as $category)
									 <option value="{{ $category->c_id }}" @if( old('scategory') == $category->c_id ) selected @endif>
									 {{ $category->c_title }}
									 </option>
									@endforeach
								</select>
								</div>
							</div>							
							<div class="form-group tag_section" @if(empty($tags)) style="display:none;"@endif>
								<label class="control-label col-sm-3" for="tags">Tags</label>
								<div class="tag_div col-sm-7" style="text-align: left;">
									@foreach ($tags as $tag)
										@php
											$checked ='';
											if(in_array($tag->id, (array) old('tags')))
												$checked = ' checked';
										@endphp
										<input class="" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"{{ $checked }}/> {{ $tag->name }}<br />
									@endforeach
								</div>
							</div>
							<button type="button" data-next="menu1" class="cls_next btn btn-primary">Next</button>
						</div>
						<div id="menu1" class="tab-pane fade">
							<div class="form-group required">
								<label class="control-label col-sm-3">Country</label>
								<div class="col-sm-7">
								<select name="country" id="country"  class="form-control country">						  
									<option value="">---Choose---</option>
									 @foreach ($country as $country)
									 <option value="{{ $country->id }}" @if( old('country') == $country->id ) selected @endif>
									 {{ $country->name }}
									 </option>
									 @endforeach
								 </select>
								</div>	
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3">State</label>
								<div class="col-sm-7">
								<select name="states" id="states"  class="form-control states">
									<option value="">---Choose---</option>
									@foreach ($states as $state)
									 <option value="{{ $state->id }}" @if( old('states') == $state->id ) selected @endif>
									 {{ $state->name }}
									 </option>
									@endforeach
								 </select>	
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3">City</label>
								<div class="col-sm-7">
								<select name="cities" id="cities"  class="form-control">		
									<option value="">---Choose---</option>
									@foreach ($cities as $city)
									 <option value="{{ $city->id }}" @if( old('cities') == $city->id ) selected @endif>
									 {{ $city->name }}
									 </option>
									@endforeach
								 </select>	
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3">Address 1</label>
								<div class="col-sm-7">
								<input class="form-control" type="text" name="address1" id="address1" value="{{ old('address1') }}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Address 2</label>
								<div class="col-sm-7">
								<input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2') }}"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3">Post Code</label>
								<div class="col-sm-7">
								<input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="control-label col-sm-3">Phone No</label>
								<div class="col-sm-7">
								<input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno') }}"/>
								</div>
							</div>
							<button type="button" data-next="menu2" class="cls_next btn btn-primary">Next</button>
						</div>
						<div id="menu2" class="tab-pane fade">
							<div class="form-group">
								<label class="col-sm-3 control-label">Meta Tag</label>
								<div class="col-sm-7">
									<textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag') }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Meta Description</label>
								<div class="col-sm-7">
									<textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label">Listing Description</label>
								<div class="col-sm-7">
									<textarea  class="tinymce" id="description" name="description">{{ old('description') }}</textarea>
								</div>
							</div>
							
							<button type="button" data-next="menu3" class="cls_next btn btn-primary">Next</button>
						</div>
						<div id="menu3" class="tab-pane fade">
							<div class="form-group required">
								<label class="col-sm-3 control-label">Images</label>
								<div class="col-sm-7">
									<div id="dropzonePreview" class="dropzone"></div><br />
									<div class="view_banner_section">
										<div class="dropzone">
											<div id="fil"></div>
										</div>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					</form>				
					
				</div>
			</div>

		</div>
		<div class="container hidden">
			<div class="row">
				<div class="col-sm-4">
					<div class="item">
						<div class="thumb-icon">
							<a href="#">
								<i class="icon icon-Truck"></i>
							</a>
						</div>
						<h6 class="title">FREE SHIPPING</h6>
						<p class="sub-title">Free shipping on all USA order</p>
					</div> <!-- /.end item -->
				</div>
				<div class="col-sm-4">
					<div class="item">
						<div class="thumb-icon">
							<a href="#">
								<i class="icon icon-Headset"></i>
							</a>
						</div>
						<h6 class="title">ONLINE SUPPORT</h6>
						<p class="sub-title">We support online 24/24 on day</p>
					</div> <!-- /.end item -->
				</div>
				<div class="col-sm-4">
					<div class="item no-border">
						<div class="thumb-icon">
							<a href="#">
								<i class="icon icon-Restart"></i>
							</a>
						</div>
						<h6 class="title">MONEY GUARENTEE</h6>
						<p class="sub-title">30 days money back guarentee</p>
					</div> <!-- /.end item -->
				</div>
			</div>
		</div>
	</div>
	<!-- end support -->

@stop