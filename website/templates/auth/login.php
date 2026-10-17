<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--xs">
    <form class="tp-card tp-form tp-auth" method="post" action="<?php echo e(url('/login'));?>" data-reveal>
      <?php echo csrf_field();?>
      <h1><?php echo e(t('auth.login'));?></h1>
      <p class="tp-text-2"><?php echo e(t('auth.login_lead'));?></p>
      <div class="tp-field"><label for="email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="email" type="email" name="email" required autocomplete="email" value="<?php echo old('email');?>"></div>
      <div class="tp-field"><label for="password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="password" type="password" name="password" required autocomplete="current-password"></div>
      <div class="tp-row tp-row--between">
        <label class="tp-check"><input type="checkbox" name="remember" value="1"> <span><?php echo e(t('auth.remember'));?></span></label>
        <a href="<?php echo e(url('/forgot-password'));?>"><?php echo e(t('auth.forgot_password'));?></a>
      </div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.login'));?></button>
      <p class="tp-center tp-text-2"><?php echo e(t('auth.no_account'));?> <a href="<?php echo e(url('/register'));?>"><?php echo e(t('auth.register'));?></a></p>
    </form>
  </div>
</section>
