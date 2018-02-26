<?php if(session()->has('status')): ?>
    <?php if(session()->get('status') == 'wrong'): ?>
    <div class="card card-danger text-xs-center z-depth-2">
        <div class="card-block">
            <p class="white-text"><?php echo e(session()->get('message')); ?></p>
        </div>
    </div>
    <?php else: ?>
    <div class="card card-success text-xs-center z-depth-2">
        <div class="card-block">
            <p class="white-text"><?php echo e(session()->get('message')); ?></p>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>