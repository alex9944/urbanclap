<h4>Tags for {{ $category->c_title }}</h4>
							
<form id="tagsForm" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
	
	@if( isset($message) and $message) <div class="alert alert-success" role="alert">{{ $message }} </div>@endif 
	
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	@php
		$i = 0;
	@endphp
	
	<table id="myTable" class="table order-list">
		<thead>
			<tr>
				<td>Tag Name</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			@foreach ($tags as $tag)
				<tr>
					<td>
						<input class="form-control" type="text" name="tags[]" id="tag_{{ $i }}" value="{{ $tag->name }}"/>
					</td>
					<td>
					@if($i > 0) <input type="button" class="ibtnDel"  value="Delete"> @endif
					</td>
				</tr>
				@php
					$i++;
				@endphp
			@endforeach
			<tr>
				<td>
					<input class="form-control" type="text" name="tags[]" id="tag_{{ $i }}" value=""/>
				</td>
				<td>
				@if($i > 0) <input type="button" class="ibtnDel"  value="Delete"> @endif
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="text-align: left;">
					<input class="" type="button" id="addrow" value="Add Row" />
				</td>
				<td>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="c_id" value="{{ $category->c_id }}">
	<div class="ln_solid"></div>
	<div class="form-group">
		<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
		  <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>

		</div>
	</div>
</form>