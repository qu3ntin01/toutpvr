<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow tp-center">
    <div class="tp-card tp-error" data-reveal>
      <span class="tp-error__code"><?php echo(int)$code;?></span>
      <h1><?php echo e($message);?></h1>
      <?php if(!empty($detail)){?><pre class="tp-code tp-left"><?php echo e($detail);?></pre><?php }?>
      <a class="tp-btn tp-btn--primary" href="<?php echo e(url('/'));?>"><?php echo icon('home',16);?> <?php echo e(t('error.back_home'));?></a>
    </div>
  </div>
</section>
