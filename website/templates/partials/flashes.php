<div class="tp-toast-region" aria-live="polite" data-flashes>
<?php foreach($flashes??[]as $f){?>
  <div class="tp-toast tp-toast--<?php echo e($f['type']);?>" role="status"><?php echo icon($f['type']==='success'?'check-circle':($f['type']==='error'?'alert':'info'),18);?><span><?php echo e($f['message']);?></span><button class="tp-toast__close" type="button" aria-label="<?php echo e(t('common.close'));?>" data-toast-close><?php echo icon('x',14);?></button></div>
<?php }?>
</div>
