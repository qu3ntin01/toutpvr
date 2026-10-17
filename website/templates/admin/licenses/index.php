<form class="tp-toolbar" method="get">
  <input class="tp-input" type="search" name="q" value="<?php echo e($filters['q']);?>" placeholder="<?php echo e(t('common.search'));?>">
  <select class="tp-select" name="status"><option value=""><?php echo e(t('common.status'));?></option><?php foreach(\App\Models\License::STATUSES as $s){?><option value="<?php echo $s;?>" <?php echo $filters['status']===$s?'selected':'';?>><?php echo e(t('status_label.'.$s));?></option><?php }?></select>
  <select class="tp-select" name="plan"><option value=""><?php echo e(t('license.plan'));?></option><?php foreach($plans as $p){?><option value="<?php echo e($p['slug']);?>" <?php echo $filters['plan']===$p['slug']?'selected':'';?>><?php echo e($p['slug']);?></option><?php }?></select>
  <button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('search',16);?></button>
  <span class="tp-toolbar__spacer"></span>
  <a class="tp-btn tp-btn--primary" href="/admin/licenses/create"><?php echo icon('plus',16);?> <?php echo e(t('admin.license_create'));?></a>
</form>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('license.key'));?></th><th><?php echo e(t('admin.customer'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('common.status'));?></th><th><?php echo e(t('license.expires'));?></th><th><?php echo e(t('license.activations'));?></th><th><?php echo e(t('common.source'));?></th></tr></thead><tbody>
  <?php foreach($res['items']as $l){?><tr><td><a class="tp-mono" href="/admin/licenses/<?php echo(int)$l['id'];?>"><?php echo e($l['license_key']);?></a></td><td><?php echo e($l['user_email']);?></td><td><?php echo e($l['plan_slug']);?></td><td><?php partial('status_badge',['status'=>\App\Models\License::effectiveStatus($l)]);?></td><td><?php echo e(date_fmt($l['expires_at']));?></td><td><?php echo \App\Models\License::activeActivationCount((int)$l['id']);?>/<?php echo(int)$l['max_activations'];?></td><td><span class="tp-badge tp-badge--muted"><?php echo e($l['source']??'portal');?></span></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="7" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
