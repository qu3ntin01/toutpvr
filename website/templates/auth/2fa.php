<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--xs">
    <form class="tp-card tp-form tp-auth" method="post" action="<?php echo e(url('/login/2fa'));?>" data-reveal>
      <?php echo csrf_field();?>
      <h1><?php echo e(t('auth.two_factor'));?></h1>
      <p class="tp-text-2"><?php echo e(t($method==='email'?'auth.two_factor_email_lead':'auth.two_factor_lead'));?></p>
      <div class="tp-field"><label for="code"><?php echo e(t('field.code'));?></label><input class="tp-input tp-input--code" id="code" name="code" inputmode="numeric" pattern="[0-9 ]*" autocomplete="one-time-code" required autofocus maxlength="7"></div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.verify'));?></button>
    </form>
  </div>
</section>
