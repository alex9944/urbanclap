<?php $__env->startSection('head'); ?>
    <?php echo HTML::style('/assets/css/reset.css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
		<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		<div class="panel panel-default resetForm">
		<h2 class="form-signin-heading text-center">Password Reset</h2>
        <?php echo Form::open(['url' => url('/password/email'), 'class' => 'form-signin' ] ); ?>


        <?php echo $__env->make('includes.status', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php echo $__env->make('includes.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!--<h2 class="form-signin-heading">Password Reset</h2>-->
        <label for="inputEmail" class="sr-only">Email address</label>
        <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email address', 'required', 'autofocus', 'id' => 'inputEmail' ]); ?>


        <br />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Send me a reset link</button>

        <?php echo Form::close(); ?>

		</div>
		</div>
		<div class="col-md-4"></div>
		</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>