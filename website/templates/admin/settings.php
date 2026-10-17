<?php $v=$values;$f=fn(string $k)=>str_replace('.','_',$k);$tabs=['site'=>t('admin.tab_site'),'billing'=>t('admin.tab_billing'),'payment'=>t('admin.tab_payment'),'mail'=>t('admin.tab_mail'),'languages'=>t('admin.tab_languages'),'updates'=>t('admin.tab_updates'),'appearance'=>t('theme.appearance')];?>
<div class="tp-tabs" data-tabs data-tabs-active="<?php echo e($tab);?>">
  <div class="tp-tabs__list" role="tablist"><?php foreach($tabs as $k=>$label){?><a class="tp-tabs__tab<?php echo $tab===$k?' is-active':'';?>" role="tab" href="/admin/settings?tab=<?php echo $k;?>"><?php echo e($label);?></a><?php }?></div>
</div>
<?php if($tab==='appearance'){?>
<div class="tp-card tpa-appearance-panel tp-mt-3" data-tpa-appearance-panel></div>
<?php }else{?>
<form class="tp-card tp-form tp-mt-3" method="post" action="/admin/settings"><?php echo csrf_field();?><input type="hidden" name="tab" value="<?php echo e($tab);?>">
<?php if($tab==='site'){?>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label><?php echo e(t('admin.site_name'));?></label><input class="tp-input" name="site_name" value="<?php echo e($v['site.name']?:'ToutPVR');?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.tagline'));?></label><input class="tp-input" name="site_tagline" value="<?php echo e($v['site.tagline']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.docs_url'));?></label><input class="tp-input" name="docs_url" value="<?php echo e($v['docs_url']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.support_email'));?></label><input class="tp-input" type="email" name="support_email" value="<?php echo e($v['support_email']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.default_theme'));?></label><select class="tp-select" name="site_default_theme"><option value="dark" <?php echo $v['site.default_theme']!=='light'?'selected':'';?>><?php echo e(t('common.dark'));?></option><option value="light" <?php echo $v['site.default_theme']==='light'?'selected':'';?>><?php echo e(t('common.light'));?></option></select></div>
    <div class="tp-field"><label>GitHub</label><input class="tp-input" name="site_github_url" value="<?php echo e($v['site.github_url']);?>"></div>
  </div>
<?php }elseif($tab==='billing'){?>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label><?php echo e(t('admin.company_name'));?></label><input class="tp-input" name="billing_company_name" value="<?php echo e($v['billing.company_name']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('field.vat_number'));?></label><input class="tp-input" name="billing_company_vat" value="<?php echo e($v['billing.company_vat']);?>"></div>
    <div class="tp-field"><label>SIRET</label><input class="tp-input" name="billing_company_siret" value="<?php echo e($v['billing.company_siret']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('field.country'));?> (ISO)</label><input class="tp-input" name="billing_company_country" maxlength="2" value="<?php echo e($v['billing.company_country']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('common.currency'));?></label><input class="tp-input" name="billing_currency" maxlength="3" value="<?php echo e($v['billing.currency']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.vat_rate'));?> (%)</label><input class="tp-input" name="billing_vat_rate" value="<?php echo e((string)$v['billing.vat_rate']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.invoice_prefix'));?></label><input class="tp-input" name="billing_invoice_prefix" value="<?php echo e($v['billing.invoice_prefix']);?>"></div>
  </div>
  <div class="tp-field"><label><?php echo e(t('admin.company_address'));?></label><textarea class="tp-input" name="billing_company_address" rows="3"><?php echo e($v['billing.company_address']);?></textarea></div>
<?php }elseif($tab==='payment'){?>
  <div class="tp-field"><label><?php echo e(t('admin.payment_mode'));?></label><select class="tp-select" name="payment_mode"><?php foreach(['sandbox'=>t('admin.mode_sandbox'),'stripe'=>'Stripe Checkout','manual'=>t('admin.mode_manual')]as $k=>$l){?><option value="<?php echo $k;?>" <?php echo $v['payment.mode']===$k?'selected':'';?>><?php echo e($l);?></option><?php }?></select></div>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label>Stripe publishable key</label><input class="tp-input tp-mono" name="payment_stripe_publishable_key" value="<?php echo e($v['payment.stripe_publishable_key']);?>"></div>
    <div class="tp-field"><label>Stripe secret key</label><input class="tp-input tp-mono" type="password" name="payment_stripe_secret_key" placeholder="<?php echo $v['payment.stripe_secret_key']?'••••••••'.e(substr($v['payment.stripe_secret_key'],-4)):'sk_live_…';?>" autocomplete="off"></div>
    <div class="tp-field"><label>Stripe webhook secret</label><input class="tp-input tp-mono" type="password" name="payment_stripe_webhook_secret" placeholder="<?php echo $v['payment.stripe_webhook_secret']?'••••••••':'whsec_…';?>" autocomplete="off"><span class="tp-field__hint">Endpoint : <code><?php echo e(config('app.url'));?>/api/v1/webhooks/stripe</code></span></div>
  </div>
  <div class="tp-field"><label><?php echo e(t('admin.bank_details'));?></label><textarea class="tp-input" name="payment_bank_details" rows="3"><?php echo e($v['payment.bank_details']);?></textarea></div>
<?php }elseif($tab==='mail'){?>
  <div class="tp-grid tp-grid--3 tp-grid--tight">
    <div class="tp-field"><label><?php echo e(t('admin.mail_driver'));?></label><select class="tp-select" name="mail_driver"><option value="log" <?php echo $v['mail.driver']==='log'?'selected':'';?>>log (storage/logs/mail.log)</option><option value="smtp" <?php echo $v['mail.driver']==='smtp'?'selected':'';?>>SMTP</option></select></div>
    <div class="tp-field"><label><?php echo e(t('admin.from_email'));?></label><input class="tp-input" name="mail_from_email" value="<?php echo e($v['mail.from_email']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.from_name'));?></label><input class="tp-input" name="mail_from_name" value="<?php echo e($v['mail.from_name']);?>"></div>
    <div class="tp-field"><label>SMTP host</label><input class="tp-input" name="mail_smtp_host" value="<?php echo e($v['mail.smtp.host']);?>"></div>
    <div class="tp-field"><label>Port</label><input class="tp-input" type="number" name="mail_smtp_port" value="<?php echo e((string)$v['mail.smtp.port']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.encryption'));?></label><select class="tp-select" name="mail_smtp_encryption"><?php foreach(['tls','ssl','none']as $enc){?><option value="<?php echo $enc;?>" <?php echo $v['mail.smtp.encryption']===$enc?'selected':'';?>><?php echo strtoupper($enc);?></option><?php }?></select></div>
    <div class="tp-field"><label><?php echo e(t('field.username'));?></label><input class="tp-input" name="mail_smtp_username" value="<?php echo e($v['mail.smtp.username']);?>" autocomplete="off"></div>
    <div class="tp-field"><label><?php echo e(t('field.password'));?></label><input class="tp-input" type="password" name="mail_smtp_password" placeholder="<?php echo $v['mail.smtp.password']?'••••••••':'';?>" autocomplete="new-password"></div>
  </div>
<?php }elseif($tab==='languages'){?>
  <p class="tp-text-2"><?php echo e(t('admin.languages_lead'));?></p>
  <div class="tp-grid tp-grid--4 tp-grid--tight">
    <?php foreach(\App\Core\I18n::LANGUAGES as $l){?><label class="tp-check"><input type="checkbox" name="languages[]" value="<?php echo $l;?>" <?php echo in_array($l,$languages,true)?'checked':'';?> <?php echo $l===config('app.default_lang','fr')?'disabled checked':'';?>> <span><?php echo strtoupper($l);?> — <?php echo e(\App\Core\I18n::name($l));?></span></label><?php }?>
  </div>
<?php }elseif($tab==='updates'){?>
  <div class="tp-field"><label>X-Editor-Token</label><input class="tp-input tp-mono" name="updates_editor_token" value="" placeholder="<?php echo $v['updates.editor_token']?'••••••••'.e(substr((string)$v['updates.editor_token'],-6)):'';?>" autocomplete="off"><span class="tp-field__hint"><?php echo e(t('admin.editor_token_lead'));?> <code>php cli/console.php editor:token</code></span></div>
<?php }?>
  <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button></div>
</form>
<?php }if($tab==='mail'){?>
<form class="tp-card tp-form tp-mt-3" method="post" action="/admin/settings/test-mail"><?php echo csrf_field();?><h3><?php echo e(t('admin.mail_test'));?></h3><div class="tp-row"><input class="tp-input" type="email" name="to" value="<?php echo e($auth['email']);?>"><button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('mail',16);?> <?php echo e(t('admin.send_test'));?></button></div></form>
<?php }