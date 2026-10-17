<?php $p=$plan??['slug'=>'','name'=>[],'description'=>[],'price_month'=>0,'price_year'=>0,'currency'=>'EUR','max_cameras'=>8,'retention_days'=>7,'features'=>[],'highlights'=>[],'trial_days'=>0,'stripe_price_month'=>'','stripe_price_year'=>'','sort'=>0,'is_public'=>1,'is_active'=>1,'is_featured'=>0];?>
<a class="tp-back" href="/admin/plans"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.plans'));?></a>
<form class="tp-card tp-form" method="post" action="/admin/plans/save"><?php echo csrf_field();?><input type="hidden" name="id" value="<?php echo(int)($p['id']??0);?>">
  <div class="tp-grid tp-grid--3 tp-grid--tight">
    <div class="tp-field"><label>Slug</label><input class="tp-input" name="slug" required pattern="[a-z0-9-]+" value="<?php echo e($p['slug']);?>"></div>
    <div class="tp-field"><label><?php echo e(t('pricing.price_month'));?> (<?php echo e($p['currency']);?>)</label><input class="tp-input" name="price_month" required value="<?php echo number_format($p['price_month']/100,2,'.','');?>"></div>
    <div class="tp-field"><label><?php echo e(t('pricing.price_year'));?></label><input class="tp-input" name="price_year" required value="<?php echo number_format($p['price_year']/100,2,'.','');?>"></div>
    <div class="tp-field"><label><?php echo e(t('pricing.max_cameras'));?> <span class="tp-text-3">(<?php echo e(t('admin.empty_unlimited'));?>)</span></label><input class="tp-input" type="number" name="max_cameras" value="<?php echo $p['max_cameras']===null?'':(int)$p['max_cameras'];?>"></div>
    <div class="tp-field"><label><?php echo e(t('pricing.retention_days'));?></label><input class="tp-input" type="number" name="retention_days" required value="<?php echo(int)$p['retention_days'];?>"></div>
    <div class="tp-field"><label><?php echo e(t('admin.trial_days'));?></label><input class="tp-input" type="number" name="trial_days" value="<?php echo(int)$p['trial_days'];?>"></div>
    <div class="tp-field"><label><?php echo e(t('common.currency'));?></label><input class="tp-input" name="currency" maxlength="3" value="<?php echo e($p['currency']);?>"></div>
    <div class="tp-field"><label>Stripe price (month)</label><input class="tp-input" name="stripe_price_month" value="<?php echo e($p['stripe_price_month']);?>"></div>
    <div class="tp-field"><label>Stripe price (year)</label><input class="tp-input" name="stripe_price_year" value="<?php echo e($p['stripe_price_year']);?>"></div>
  </div>
  <?php partial('multilang_field',['name'=>'name','label'=>t('field.name'),'values'=>$p['name'],'type'=>'input']);?>
  <?php partial('multilang_field',['name'=>'description','label'=>t('common.description'),'values'=>$p['description']??[],'type'=>'textarea']);?>
  <div class="tp-field"><label><?php echo e(t('admin.features'));?></label><div class="tp-row tp-row--wrap"><?php foreach($allFeatures as $f){?><label class="tp-check"><input type="checkbox" name="features[]" value="<?php echo $f;?>" <?php echo in_array($f,$p['features'],true)?'checked':'';?>> <span><?php echo e(t('feature.'.$f));?></span></label><?php }?></div></div>
  <div class="tp-row tp-row--wrap">
    <label class="tp-check"><input type="checkbox" name="is_active" value="1" <?php echo(int)$p['is_active']?'checked':'';?>> <span><?php echo e(t('common.active'));?></span></label>
    <label class="tp-check"><input type="checkbox" name="is_public" value="1" <?php echo(int)$p['is_public']?'checked':'';?>> <span><?php echo e(t('admin.public'));?></span></label>
    <label class="tp-check"><input type="checkbox" name="is_featured" value="1" <?php echo(int)$p['is_featured']?'checked':'';?>> <span><?php echo e(t('pricing.popular'));?></span></label>
    <div class="tp-field tp-field--inline"><label><?php echo e(t('common.sort'));?></label><input class="tp-input tp-input--xs" type="number" name="sort" value="<?php echo(int)$p['sort'];?>"></div>
  </div>
  <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button></div>
</form>
