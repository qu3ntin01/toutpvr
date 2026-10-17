<form class="tp-toolbar" method="get"><input class="tp-input" type="search" name="q" value="<?php echo e($filters['q']);?>" placeholder="<?php echo e(t('common.search'));?>"><input class="tp-input" name="action" value="<?php echo e($filters['action']);?>" placeholder="action (auth., admin., api.)"><button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('search',16);?></button></form>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('admin.user'));?></th><th><?php echo e(t('common.action'));?></th><th><?php echo e(t('common.target'));?></th><th>IP</th><th><?php echo e(t('common.details'));?></th></tr></thead><tbody>
  <?php foreach($res['items']as $a){?><tr><td class="tp-nowrap"><?php echo e(date_fmt($a['created_at'],'d/m/Y H:i:s'));?></td><td><?php echo e($a['user_email']?:$a['details']['user']??'—');?></td><td><span class="tp-badge"><?php echo e($a['action']);?></span></td><td class="tp-mono"><?php echo e($a['target']);?></td><td class="tp-mono"><?php echo e($a['ip']);?></td><td class="tp-text-3 tp-mono tp-truncate" title="<?php echo e(json_encode($a['details'],JSON_UNESCAPED_UNICODE));?>"><?php echo e(json_encode($a['details'],JSON_UNESCAPED_UNICODE));?></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
