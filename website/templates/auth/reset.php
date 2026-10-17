<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--xs">
    <form class="tp-card tp-form tp-auth" method="post" action="<?php echo e(url('/reset-password'));?>" data-reveal>
      <?php echo csrf_field();?>
      <input type="hidden" name="token" value="<?php echo e($token);?>">
      <h1><?php echo e(t('auth.reset_password'));?></h1>
      <div class="tp-field"><label for="password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="password" type="password" name="password" required minlength="10" autocomplete="new-password"><span class="tp-field__hint"><?php echo e(t('auth.password_hint'));?></span></div>
      <div class="tp-field"><label for="password_confirmation"><?php echo e(t('field.password_confirmation'));?></label><input class="tp-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.update_password'));?></button>
    </form>
  </div>
</section>
