<div class="tp-page-head"><h1><?php echo e(t('account.security'));?></h1><p class="tp-text-2"><?php echo e(t('account.security_lead'));?></p></div>
<div class="tp-grid tp-grid--2">
  <form class="tp-card tp-form" method="post" action="<?php echo e(url('/account/security/password'));?>">
    <?php echo csrf_field();?>
    <h3><?php echo icon('lock',18);?> <?php echo e(t('account.change_password'));?></h3>
    <div class="tp-field"><label for="current_password"><?php echo e(t('field.current_password'));?></label><input class="tp-input" id="current_password" type="password" name="current_password" required autocomplete="current-password"></div>
    <div class="tp-field"><label for="password"><?php echo e(t('field.new_password'));?></label><input class="tp-input" id="password" type="password" name="password" required minlength="10" autocomplete="new-password"><span class="tp-field__hint"><?php echo e(t('auth.password_hint'));?></span></div>
    <div class="tp-field"><label for="password_confirmation"><?php echo e(t('field.password_confirmation'));?></label><input class="tp-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
    <button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('auth.update_password'));?></button>
  </form>
  <div class="tp-card tp-form">
    <h3><?php echo icon('shield-check',18);?> <?php echo e(t('account.two_factor'));?></h3>
    <?php if($user['two_factor']!=='none'){?>
      <p><span class="tp-badge tp-badge--success"><?php echo e(t('common.enabled'));?></span> <?php echo e(t('account.2fa_method_'.$user['two_factor']));?></p>
      <form method="post" action="<?php echo e(url('/account/security/2fa/disable'));?>"><?php echo csrf_field();?>
        <div class="tp-field"><label for="cp2"><?php echo e(t('field.current_password'));?></label><input class="tp-input" id="cp2" type="password" name="current_password" required></div>
        <button class="tp-btn tp-btn--danger" type="submit"><?php echo e(t('account.2fa_disable'));?></button>
      </form>
    <?php }else{?>
      <p class="tp-text-2"><?php echo e(t('account.2fa_lead'));?></p>
      <div class="tp-tabs" data-tabs>
        <div class="tp-tabs__list" role="tablist"><button class="tp-tabs__tab is-active" role="tab" data-tab="totp"><?php echo e(t('account.2fa_method_totp'));?></button><button class="tp-tabs__tab" role="tab" data-tab="email"><?php echo e(t('account.2fa_method_email'));?></button></div>
        <div class="tp-tabs__panel is-active" data-tab-panel="totp">
          <div class="tp-qr" data-qr="<?php echo e($totp_uri);?>"></div>
          <p class="tp-text-3"><?php echo e(t('account.2fa_secret'));?> : <code class="tp-mono"><?php echo e(chunk_split($totp_secret,4,' '));?></code></p>
          <p class="tp-text-3"><a href="<?php echo e($totp_uri);?>"><?php echo e(t('account.2fa_open_app'));?></a></p>
          <form method="post" action="<?php echo e(url('/account/security/2fa/enable'));?>"><?php echo csrf_field();?><input type="hidden" name="method" value="totp">
            <div class="tp-field"><label for="code"><?php echo e(t('field.code'));?></label><input class="tp-input tp-input--code" id="code" name="code" inputmode="numeric" required autocomplete="one-time-code"></div>
            <button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('account.2fa_enable'));?></button>
          </form>
        </div>
        <div class="tp-tabs__panel" data-tab-panel="email">
          <p class="tp-text-2"><?php echo e(t('account.2fa_email_lead'));?></p>
          <form method="post" action="<?php echo e(url('/account/security/2fa/enable'));?>"><?php echo csrf_field();?><input type="hidden" name="method" value="email"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('account.2fa_enable'));?></button></form>
        </div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>
    <?php }?>
  </div>
</div>
<div class="tp-card tp-mt-3"><h3><?php echo icon('activity',18);?> <?php echo e(t('account.last_login'));?></h3><p class="tp-text-2"><?php echo e(date_fmt($user['last_login_at'],'d/m/Y H:i'));?> UTC</p></div>
