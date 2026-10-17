<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <?php partial('../checkout/steps',['step'=>2]);?>
    <div class="tp-grid tp-grid--2">
      <form class="tp-card tp-form" method="post" action="<?php echo e(url('/login'));?>">
        <?php echo csrf_field();?>
        <h2><?php echo e(t('auth.login'));?></h2>
        <div class="tp-field"><label for="l-email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="l-email" type="email" name="email" required autocomplete="email"></div>
        <div class="tp-field"><label for="l-password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="l-password" type="password" name="password" required autocomplete="current-password"></div>
        <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.login'));?></button>
      </form>
      <form class="tp-card tp-form" method="post" action="<?php echo e(url('/register'));?>">
        <?php echo csrf_field();?>
        <h2><?php echo e(t('auth.register'));?></h2>
        <div class="tp-field"><label for="r-name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="r-name" name="name" required autocomplete="name"></div>
        <div class="tp-field"><label for="r-email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="r-email" type="email" name="email" required autocomplete="email"></div>
        <div class="tp-field"><label for="r-password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="r-password" type="password" name="password" required minlength="10" autocomplete="new-password"></div>
        <div class="tp-field"><label for="r-password2"><?php echo e(t('field.password_confirmation'));?></label><input class="tp-input" id="r-password2" type="password" name="password_confirmation" required autocomplete="new-password"></div>
        <label class="tp-check"><input type="checkbox" name="terms" value="1" required> <span><?php echo e(t('auth.accept_terms'));?> <a href="<?php echo e(url('/terms'));?>" target="_blank"><?php echo e(t('nav.terms'));?></a></span></label>
        <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.create_account'));?></button>
      </form>
    </div>
  </div>
</section>
