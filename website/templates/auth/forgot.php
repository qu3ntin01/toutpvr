<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--xs">
    <form class="tp-card tp-form tp-auth" method="post" action="<?php echo e(url('/forgot-password'));?>" data-reveal>
      <?php echo csrf_field();?>
      <h1><?php echo e(t('auth.forgot_password'));?></h1>
      <p class="tp-text-2"><?php echo e(t('auth.forgot_lead'));?></p>
      <div class="tp-field"><label for="email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="email" type="email" name="email" required autocomplete="email"></div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.send_link'));?></button>
      <p class="tp-center tp-text-2"><a href="<?php echo e(url('/login'));?>"><?php echo e(t('auth.back_login'));?></a></p>
    </form>
  </div>
</section>
