<?php $c=$customer;?>
<a class="tp-back" href="/admin/customers"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.customers'));?></a>
<div class="tp-grid tp-grid--3">
  <form class="tp-card tp-form tp-span-2" method="post" action="/admin/customers/<?php echo(int)$c['id'];?>"><?php echo csrf_field();?>
    <h3><?php echo icon('user',18);?> <?php echo e($c['email']);?> <span class="tp-badge tp-badge--muted"><?php echo e($c['role']);?></span></h3>
    <div class="tp-grid tp-grid--2 tp-grid--tight">
      <div class="tp-field"><label><?php echo e(t('field.name'));?></label><input class="tp-input" name="name" value="<?php echo e($c['name']);?>" required></div>
      <div class="tp-field"><label><?php echo e(t('field.email'));?></label><input class="tp-input" type="email" name="email" value="<?php echo e($c['email']);?>" required></div>
      <div class="tp-field"><label><?php echo e(t('field.company'));?></label><input class="tp-input" name="company" value="<?php echo e($c['company']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.vat_number'));?></label><input class="tp-input" name="vat_number" value="<?php echo e($c['vat_number']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.address_line1'));?></label><input class="tp-input" name="address_line1" value="<?php echo e($c['address_line1']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.address_line2'));?></label><input class="tp-input" name="address_line2" value="<?php echo e($c['address_line2']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.postal_code'));?></label><input class="tp-input" name="postal_code" value="<?php echo e($c['postal_code']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.city'));?></label><input class="tp-input" name="city" value="<?php echo e($c['city']);?>"></div>
      <div class="tp-field"><label><?php echo e(t('field.country'));?></label><select class="tp-select" name="country"><?php foreach($countries as $code=>$label){?><option value="<?php echo $code;?>" <?php echo $c['country']===$code?'selected':'';?>><?php echo e($label);?></option><?php }?></select></div>
      <div class="tp-field"><label><?php echo e(t('field.phone'));?></label><input class="tp-input" name="phone" value="<?php echo e($c['phone']);?>"></div>
    </div>
    <div class="tp-row tp-row--wrap">
      <label class="tp-check"><input type="checkbox" name="is_active" value="1" <?php echo(int)$c['is_active']?'checked':'';?>> <span><?php echo e(t('common.active'));?></span></label>
      <?php if($c['two_factor']!=='none'){?><label class="tp-check"><input type="checkbox" name="reset_2fa" value="1"> <span><?php echo e(t('admin.reset_2fa'));?> (<?php echo e($c['two_factor']);?>)</span></label><?php }?>
    </div>
    <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button></div>
  </form>
  <div class="tp-card">
    <h3><?php echo e(t('common.details'));?></h3>
    <dl class="tp-dl">
      <dt><?php echo e(t('common.created'));?></dt><dd><?php echo e(date_fmt($c['created_at'],'d/m/Y H:i'));?></dd>
      <dt><?php echo e(t('account.last_login'));?></dt><dd><?php echo e(date_fmt($c['last_login_at'],'d/m/Y H:i'));?></dd>
      <dt><?php echo e(t('common.language'));?></dt><dd><?php echo e(\App\Core\I18n::name($c['lang']));?></dd>
      <dt>E-mail vérifié</dt><dd><?php echo $c['email_verified_at']?icon('check',14):'—';?></dd>
    </dl>
    <form method="post" action="/admin/customers/<?php echo(int)$c['id'];?>/impersonate-reset" class="tp-mt-2"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo icon('mail',14);?> <?php echo e(t('admin.send_reset'));?></button></form>
    <a class="tp-btn tp-btn--ghost tp-btn--sm tp-mt-2" href="/admin/licenses/create?email=<?php echo e(rawurlencode($c['email']));?>"><?php echo icon('plus',14);?> <?php echo e(t('admin.license_create'));?></a>
  </div>
</div>
<div class="tp-grid tp-grid--2 tp-mt-3">
  <div class="tp-card tp-card--table"><h3><?php echo e(t('admin.licenses'));?></h3>
    <table class="tp-table"><thead><tr><th><?php echo e(t('license.key'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('common.status'));?></th><th><?php echo e(t('license.expires'));?></th></tr></thead><tbody>
    <?php foreach($licenses as $l){?><tr><td><a class="tp-mono" href="/admin/licenses/<?php echo(int)$l['id'];?>"><?php echo e($l['license_key']);?></a></td><td><?php echo e($l['plan']['slug']);?></td><td><?php partial('status_badge',['status'=>\App\Models\License::effectiveStatus($l)]);?></td><td><?php echo e(date_fmt($l['expires_at']));?></td></tr><?php }?>
    <?php if(!$licenses){?><tr><td colspan="4" class="tp-text-3 tp-center"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    </tbody></table></div>
  <div class="tp-card tp-card--table"><h3><?php echo e(t('admin.orders'));?></h3>
    <table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('invoice.total'));?></th><th><?php echo e(t('common.status'));?></th></tr></thead><tbody>
    <?php foreach($orders as $o){?><tr><td><a href="/admin/orders/<?php echo(int)$o['id'];?>"><?php echo(int)$o['id'];?></a></td><td><?php echo e(date_fmt($o['created_at']));?></td><td><?php echo money((int)$o['total']);?></td><td><?php partial('status_badge',['status'=>$o['status']]);?></td></tr><?php }?>
    <?php if(!$orders){?><tr><td colspan="4" class="tp-text-3 tp-center"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    </tbody></table></div>
</div>
<div class="tp-card tp-card--table tp-mt-3"><h3><?php echo e(t('admin.audit'));?></h3>
  <table class="tp-table"><thead><tr><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('common.action'));?></th><th><?php echo e(t('common.target'));?></th><th>IP</th></tr></thead><tbody>
  <?php foreach($audit as $a){?><tr><td><?php echo e(date_fmt($a['created_at'],'d/m/Y H:i'));?></td><td><span class="tp-badge"><?php echo e($a['action']);?></span></td><td><?php echo e($a['target']);?></td><td class="tp-mono"><?php echo e($a['ip']);?></td></tr><?php }?>
  </tbody></table></div>
