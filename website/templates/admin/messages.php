<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('field.name'));?></th><th><?php echo e(t('field.email'));?></th><th><?php echo e(t('field.subject'));?></th><th><?php echo e(t('field.message'));?></th><th></th></tr></thead><tbody>
  <?php foreach($res['items']as $m){?><tr class="<?php echo $m['read_at']?'is-muted':'';?>"><td class="tp-nowrap"><?php echo e(date_fmt($m['created_at'],'d/m/Y H:i'));?></td><td><?php echo e($m['name']);?></td><td><a href="mailto:<?php echo e($m['email']);?>"><?php echo e($m['email']);?></a></td><td><?php echo e($m['subject']);?></td><td class="tp-prewrap"><?php echo e($m['message']);?></td><td><?php if(!$m['read_at']){?><form method="post" action="/admin/messages/<?php echo(int)$m['id'];?>/read"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo icon('check',14);?> <?php echo e(t('admin.mark_read'));?></button></form><?php }?></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
