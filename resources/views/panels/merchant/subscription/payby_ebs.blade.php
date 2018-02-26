@extends('layouts.merchantmain')
@section('content')
@include('partials.status-panel')

<!-- Main Section -->
<div class="main-section">
	<div class="container">
		<div class="row">
			<!-- title -->
			<div class="title-box" style=" margin:50px 0 20px 0;">
				<h1 class="text-center title">Processing Payment</h1>
			</div>
							
			<div class="inner" style="min-height:300px;">				
				<?php 
				if(isset($ebs_html)) {
					
					echo $ebs_html;
					?>
					<script>
						$(document).ready(function() {
							
							//document.forms['ebs_auto_form'].submit();
							
						});
					</script>
					<?php
					
				}
				?>
					
					
			</div>
		</div>
	</div>
</div>
@stop