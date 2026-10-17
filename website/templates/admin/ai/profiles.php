<?php include __DIR__.'/_tabs.php';$n=count($profiles);?>
<div class="tp-toolbar tp-mt-3"><p class="tp-text-2"><?php echo e(t('admin.ai.profiles_lead'));?></p><span class="tp-toolbar__spacer"></span><a class="tp-btn tp-btn--primary" href="/admin/ai/profiles/create"><?php echo icon('plus',16);?> <?php echo e(t('admin.ai.add_profile'));?></a></div>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('admin.ai.name'));?></th><th><?php echo e(t('admin.ai.model'));?></th><th><?php echo e(t('admin.ai.trigger'));?></th><th><?php echo e(t('admin.ai.classes'));?></th><th><?php echo e(t('admin.ai.min_confidence'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
  <?php foreach($profiles as $i=>$p){?>
    <tr class="<?php echo(int)$p['enabled']?'':'is-muted';?>">
      <td class="tp-nowrap"><span class="tp-text-3"><?php echo(int)$p['sort_order'];?></span>
        <form method="post" action="/admin/ai/profiles/<?php echo(int)$p['id'];?>/move" class="tp-inline"><?php echo csrf_field();?><input type="hidden" name="dir" value="up"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit" <?php echo $i===0?'disabled':'';?> aria-label="↑">&uarr;</button></form>
        <form method="post" action="/admin/ai/profiles/<?php echo(int)$p['id'];?>/move" class="tp-inline"><?php echo csrf_field();?><input type="hidden" name="dir" value="down"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit" <?php echo $i===$n-1?'disabled':'';?> aria-label="↓">&darr;</button></form></td>
      <td><strong><?php echo e($p['name']);?></strong><div class="tp-text-3 tp-mono"><?php echo e($p['slug']);?></div><?php $d=\App\Core\I18n::pick($p['description']??[]);if($d){?><div class="tp-text-3"><?php echo e($d);?></div><?php }?></td>
      <td><code><?php echo e($p['model']);?></code></td>
      <td><span class="tp-badge tp-badge--info"><?php echo e(t('admin.ai.trigger_'.$p['trigger'].'_short'));?></span></td>
      <td class="tp-tags"><?php foreach((array)$p['classes']as $c){?><span class="tp-badge tp-badge--muted"><?php echo e(t('admin.ai.class_'.$c));?></span> <?php }?></td>
      <td><?php echo e(number_format((float)$p['min_confidence'],2));?> · <?php echo(int)$p['min_interval_s'];?> s</td>
      <td><span class="tp-badge tp-badge--<?php echo(int)$p['enabled']?'success':'muted';?>"><?php echo e((int)$p['enabled']?t('admin.ai.enabled'):t('admin.ai.disabled'));?></span></td>
      <td class="tp-row tp-nowrap">
        <a class="tp-btn tp-btn--ghost tp-btn--sm" href="/admin/ai/profiles/<?php echo(int)$p['id'];?>" title="<?php echo e(t('common.edit'));?>"><?php echo icon('edit',14);?></a>
        <form method="post" action="/admin/ai/profiles/<?php echo(int)$p['id'];?>/toggle"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit" title="<?php echo e((int)$p['enabled']?t('admin.ai.disable'):t('admin.ai.enable'));?>"><?php echo icon((int)$p['enabled']?'pause':'play',14);?></button></form>
        <form method="post" action="/admin/ai/profiles/<?php echo(int)$p['id'];?>/delete" data-confirm="<?php echo e(t('admin.ai.confirm_delete_profile'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit" title="<?php echo e(t('common.delete'));?>"><?php echo icon('trash',14);?></button></form>
      </td>
    </tr>
  <?php }?>
  <?php if(!$profiles){?><tr><td colspan="8" class="tp-center tp-text-3"><?php echo e(t('admin.ai.no_profiles'));?></td></tr><?php }?>
  </tbody></table>
</div>
