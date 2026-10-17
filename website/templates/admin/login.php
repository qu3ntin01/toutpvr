<form class="tp-card tp-form tp-auth" method="post" action="/admin/login">
  <?php echo csrf_field();?>
  <div class="tp-center"><span class="tp-brand__logo tp-brand__logo--lg"><?php echo icon('camera',26);?></span></div>
  <h1 class="tp-center"><?php echo e(t('admin.login_title'));?></h1>
  <div class="tp-field"><label for="email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="email" type="email" name="email" required autocomplete="username" value="<?php echo old('email');?>"></div>
  <div class="tp-field"><label for="password"><?php echo e(t('field.password'));?></label><input class="tp-input" id="password" type="password" name="password" required autocomplete="current-password"></div>
  <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo icon('lock',16);?> <?php echo e(t('auth.login'));?></button>
  <p class="tp-center tp-text-3"><a href="<?php echo e(url('/'));?>"><?php echo e(t('admin.back_site'));?></a></p>
</form>
