<div class="tp-grid tp-grid--3">
  <div class="tp-card tp-card--table tp-span-2">
    <table class="tp-table"><thead><tr><th>Code</th><th><?php echo e(t('common.type'));?></th><th><?php echo e(t('admin.value'));?></th><th><?php echo e(t('admin.uses'));?></th><th><?php echo e(t('admin.validity'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
    <?php foreach($coupons as $c){?><tr><td><code><?php echo e($c['code']);?></code></td><td><?php echo e($c['kind']);?></td><td><?php echo $c['kind']==='percent'?(int)$c['value'].' %':money((int)$c['value']);?></td><td><?php echo(int)$c['used'];echo $c['max_uses']!==null?'/'.(int)$c['max_uses']:'';?></td><td class="tp-text-3"><?php echo e(date_fmt($c['valid_from']));?> → <?php echo e(date_fmt($c['valid_until']));?></td><td><span class="tp-badge tp-badge--<?php echo(int)$c['is_active']?'success':'muted';?>"><?php echo(int)$c['is_active']?e(t('common.active')):e(t('common.disabled'));?></span></td><td class="tp-row"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-fill-form='<?php echo e(json_encode(['id'=>$c['id'],'code'=>$c['code'],'kind'=>$c['kind'],'value'=>$c['kind']==='percent'?$c['value']:$c['value']/100,'max_uses'=>$c['max_uses'],'valid_from'=>substr((string)$c['valid_from'],0,10),'valid_until'=>substr((string)$c['valid_until'],0,10),'is_active'=>(bool)$c['is_active'],'plan_slugs'=>$c['plan_slugs']??[]]));?>' data-fill-target="#coupon-form"><?php echo icon('edit',14);?></button><form method="post" action="/admin/coupons/<?php echo(int)$c['id'];?>/delete" data-confirm="<?php echo e(t('common.confirm_delete'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('trash',14);?></button></form></td></tr><?php }?>
    <?php if(!$coupons){?><tr><td colspan="7" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    </tbody></table>
  </div>
  <form class="tp-card tp-form" id="coupon-form" method="post" action="/admin/coupons/save"><?php echo csrf_field();?><input type="hidden" name="id" value="0">
    <h3><?php echo e(t('admin.coupon_edit'));?></h3>
    <div class="tp-field"><label>Code</label><input class="tp-input" name="code" required pattern="[A-Za-z0-9_-]+"></div>
    <div class="tp-grid tp-grid--2 tp-grid--tight">
      <div class="tp-field"><label><?php echo e(t('common.type'));?></label><select class="tp-select" name="kind"><option value="percent">%</option><option value="fixed"><?php echo e(t('admin.fixed_amount'));?></option></select></div>
      <div class="tp-field"><label><?php echo e(t('admin.value'));?></label><input class="tp-input" name="value" required value="10"></div>
      <div class="tp-field"><label><?php echo e(t('admin.max_uses'));?></label><input class="tp-input" type="number" name="max_uses" min="1"></div>
      <div class="tp-field"><label><?php echo e(t('license.plan'));?></label><select class="tp-select" name="plan_slugs[]" multiple size="3"><?php foreach($plans as $p){?><option value="<?php echo e($p['slug']);?>"><?php echo e($p['slug']);?></option><?php }?></select></div>
      <div class="tp-field"><label><?php echo e(t('admin.valid_from'));?></label><input class="tp-input" type="date" name="valid_from"></div>
      <div class="tp-field"><label><?php echo e(t('admin.valid_until'));?></label><input class="tp-input" type="date" name="valid_until"></div>
    </div>
    <label class="tp-check"><input type="checkbox" name="is_active" value="1" checked> <span><?php echo e(t('common.active'));?></span></label>
    <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button><button class="tp-btn tp-btn--ghost" type="reset"><?php echo e(t('common.new'));?></button></div>
  </form>
</div>
