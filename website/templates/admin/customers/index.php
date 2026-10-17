<form class="tp-toolbar" method="get"><input class="tp-input" type="search" name="q" value="<?php echo e($q);?>" placeholder="<?php echo e(t('common.search'));?>"><button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('search',16);?></button></form>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('field.email'));?></th><th><?php echo e(t('field.name'));?></th><th><?php echo e(t('field.company'));?></th><th><?php echo e(t('field.country'));?></th><th><?php echo e(t('common.created'));?></th><th><?php echo e(t('common.status'));?></th></tr></thead><tbody>
  <?php foreach($res['items']as $u){?><tr><td><?php echo(int)$u['id'];?></td><td><a href="/admin/customers/<?php echo(int)$u['id'];?>"><?php echo e($u['email']);?></a></td><td><?php echo e($u['name']);?></td><td><?php echo e($u['company']);?></td><td><?php echo e($u['country']);?></td><td><?php echo e(date_fmt($u['created_at']));?></td><td><span class="tp-badge tp-badge--<?php echo(int)$u['is_active']?'success':'danger';?>"><?php echo(int)$u['is_active']?e(t('common.active')):e(t('common.disabled'));?></span></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="7" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
