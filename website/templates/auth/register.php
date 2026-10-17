<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--xs">
    <form class="tp-card tp-form tp-auth" method="post" action="<?php echo e(url('/register'));?>" data-reveal>
      <?php echo csrf_field();?>
      <h1><?php echo e(t('auth.register'));?></h1>
      <p class="tp-text-2"><?php echo e(t('auth.register_lead'));?></p>
      <div class="tp-field"><label for="name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="name" name="name" required autocomplete="name" value="<?php echo old('name');?>"><?php if(!empty($errors['name'])){?><span class="tp-field__error"><?php echo e($errors['name']);?></span><?php }?></div>
      <div class="tp-field"><label for="company"><?php echo e(t('field.company'));?> <span class="tp-text-3">(<?php echo e(t('common.optional'));?>)</span></label><input class="tp-input" id="company" name="company" autocomplete="organization" value="<?php echo old('company');?>"></div>
      <div class="tp-field"><label for="email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="email" type="email" name="email" required autocomplete="email" value="<?php echo old('email');?>"><?php if(!empty($errors['email'])){?><span class="tp-field__error"><?php echo e($errors['email']);?></span><?php }?></div>
      <div class="tp-field"><label for="password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="password" type="password" name="password" required minlength="10" autocomplete="new-password"><span class="tp-field__hint"><?php echo e(t('auth.password_hint'));?></span><?php if(!empty($errors['password'])){?><span class="tp-field__error"><?php echo e($errors['password']);?></span><?php }?></div>
      <div class="tp-field"><label for="password_confirmation"><?php echo e(t('field.password_confirmation'));?></label><input class="tp-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"><?php if(!empty($errors['password_confirmation'])){?><span class="tp-field__error"><?php echo e($errors['password_confirmation']);?></span><?php }?></div>
      <label class="tp-check"><input type="checkbox" name="terms" value="1" required> <span><?php echo e(t('auth.accept_terms'));?> <a href="<?php echo e(url('/terms'));?>" target="_blank"><?php echo e(t('nav.terms'));?></a></span></label>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('auth.create_account'));?></button>
      <p class="tp-center tp-text-2"><?php echo e(t('auth.have_account'));?> <a href="<?php echo e(url('/login'));?>"><?php echo e(t('auth.login'));?></a></p>
    </form>
  </div>
</section>
