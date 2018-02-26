@extends('layouts.main')

@section('content')

@include('partials.status-panel')	
	
	<div class="container blog-details">
		<div class="row">
			<div class="col-md-12">
				@if($menu)
					<?php 
					$title = $menu->title;
					$content = '';
					if(isset($menu->page))
					{
						$title = $menu->page->title;
						$content = $menu->page->description;
					}				
					?>
					<div class="cms-page post-desk">
						<h2 class="title"><?php echo $title;?></h2>
						<?php echo $content;?>
					</div>
				@else
					<div class="cms-page post-desk">
						<p class="title">Invalid page</p>
					</div>
				@endif
			</div>
		</div>
	</div>
	
@stop