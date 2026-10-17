<form class="tp-toolbar" method="get">
  <input class="tp-input" type="search" name="q" value="<?php echo e($filters['q']);?>" placeholder="<?php echo e(t('common.search'));?>">
  <select class="tp-select" name="status"><option value=""><?php echo e(t('common.status'));?></option><?php foreach(['pending','paid','failed','cancelled','refunded','awaiting_transfer']as $s){?><option value="<?php echo $s;?>" <?php echo $filters['status']===$s?'selected':'';?>><?php echo e(t('status_label.'.$s));?></option><?php }?></select>
  <button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('search',16);?></button>
</form>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('admin.customer'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('common.type'));?></th><th><?php echo e(t('invoice.total'));?></th><th><?php echo e(t('checkout.payment'));?></th><th><?php echo e(t('common.status'));?></th></tr></thead><tbody>
  <?php foreach($res['items']as $o){?><tr><td><a href="/admin/orders/<?php echo(int)$o['id'];?>"><?php echo(int)$o['id'];?></a></td><td><?php echo e(date_fmt($o['created_at'],'d/m/Y H:i'));?></td><td><?php echo e($o['user_email']);?></td><td><?php echo e($o['plan_slug']);?> · <?php echo e(t('period.'.$o['period']));?></td><td><?php echo e(t('kind.'.$o['kind']));?></td><td><?php echo money((int)$o['total'],$o['currency']);?></td><td><?php echo e($o['payment_provider']);?></td><td><?php partial('status_badge',['status'=>$o['status']]);?></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="8" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
