<a class="tp-back" href="/admin/licenses"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.licenses'));?></a>
<form class="tp-card tp-form tp-container--narrow" method="post" action="/admin/licenses/create"><?php echo csrf_field();?>
  <h3><?php echo icon('key',18);?> <?php echo e(t('admin.license_create'));?></h3>
  <p class="tp-text-2"><?php echo e(t('admin.license_create_lead'));?></p>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label><?php echo e(t('field.email'));?></label><input class="tp-input" type="email" name="email" required value="<?php echo old('email',$_GET['email']??'');?>"></div>
    <div class="tp-field"><label><?php echo e(t('field.name'));?> <span class="tp-text-3">(<?php echo e(t('admin.if_new'));?>)</span></label><input class="tp-input" name="name" value="<?php echo old('name');?>"></div>
    <div class="tp-field"><label><?php echo e(t('license.plan'));?></label><select class="tp-select" name="plan"><?php foreach($plans as $p){?><option value="<?php echo e($p['slug']);?>" <?php echo $p['slug']==='pro'?'selected':'';?>><?php echo e($p['slug']);?> — <?php echo money((int)$p['price_month']);?></option><?php }?></select></div>
    <div class="tp-field"><label><?php echo e(t('admin.months'));?></label><input class="tp-input" type="number" name="months" min="1" max="120" value="12"></div>
    <div class="tp-field"><label><?php echo e(t('license.max_activations'));?></label><input class="tp-input" type="number" name="max_activations" min="1" max="100" value="1"></div>
    <div class="tp-field"><label><?php echo e(t('pricing.max_cameras'));?> <span class="tp-text-3">(<?php echo e(t('admin.plan_default'));?>)</span></label><input class="tp-input" type="number" name="max_cameras" min="1" placeholder="—"></div>
  </div>
  <div class="tp-field"><label><?php echo e(t('admin.notes'));?></label><textarea class="tp-input" name="notes" rows="2"></textarea></div>
  <label class="tp-check"><input type="checkbox" name="send_mail" value="1" checked> <span><?php echo e(t('admin.send_key_mail'));?></span></label>
  <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo icon('key',16);?> <?php echo e(t('common.create'));?></button></div>
</form>
