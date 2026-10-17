<?php $l=$license;$st=\App\Models\License::effectiveStatus($l);?>
<a class="tp-back" href="/admin/licenses"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.licenses'));?></a>
<div class="tp-grid tp-grid--3">
  <div class="tp-card tp-span-2">
    <div class="tp-card__head"><h3 class="tp-mono"><?php echo e($l['license_key']);?></h3><?php partial('status_badge',['status'=>$st]);?></div>
    <dl class="tp-dl tp-dl--grid">
      <dt><?php echo e(t('admin.customer'));?></dt><dd><a href="/admin/customers/<?php echo(int)$l['user_id'];?>"><?php echo e($l['user_email']);?></a> <?php echo e($l['user_name']);?></dd>
      <dt><?php echo e(t('license.plan'));?></dt><dd><?php echo e($l['plan_slug']);?> · <?php echo e(t('period.'.$l['period']));?></dd>
      <dt><?php echo e(t('license.expires'));?></dt><dd><?php echo e(date_fmt($l['expires_at'],'d/m/Y H:i'));?></dd>
      <dt><?php echo e(t('pricing.max_cameras'));?></dt><dd><?php echo $l['max_cameras']===null?e(t('pricing.unlimited')):(int)$l['max_cameras'];?></dd>
      <dt><?php echo e(t('license.max_activations'));?></dt><dd><?php echo(int)$l['max_activations'];?></dd>
      <dt><?php echo e(t('common.source'));?></dt><dd><?php echo e($l['source']??'portal');echo $l['order_id']?' · <a href="/admin/orders/'.(int)$l['order_id'].'">'.e(t('admin.order')).' #'.(int)$l['order_id'].'</a>':'';?></dd>
      <dt><?php echo e(t('license.auto_renew'));?></dt><dd><?php echo(int)$l['auto_renew']?e(t('common.yes')):e(t('common.no'));?></dd>
      <dt><?php echo e(t('common.created'));?></dt><dd><?php echo e(date_fmt($l['created_at'],'d/m/Y H:i'));?></dd>
    </dl>
    <div class="tp-tags"><?php foreach($l['features']as $f){?><span class="tp-badge"><?php echo e(t('feature.'.$f));?></span><?php }?></div>
    <form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action" class="tp-mt-2"><?php echo csrf_field();?><input type="hidden" name="action" value="notes"><div class="tp-field"><label><?php echo e(t('admin.notes'));?></label><textarea class="tp-input" name="notes" rows="2"><?php echo e($l['notes']);?></textarea></div><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo e(t('common.save'));?></button></form>
  </div>
  <div class="tp-card tp-form">
    <h3><?php echo e(t('common.actions'));?></h3>
    <?php $act=fn(string $a,string $label,string $cls='tp-btn--ghost',bool $confirm=false)=>'<form method="post" action="/admin/licenses/'.(int)$l['id'].'/action"'.($confirm?' data-confirm="'.e(t('common.confirm_action')).'"':'').'>'.csrf_field().'<input type="hidden" name="action" value="'.$a.'"><button class="tp-btn '.$cls.' tp-btn--sm tp-btn--block" type="submit">'.$label.'</button></form>';?>
    <?php if($st!=='revoked'){?>
      <?php if($st==='active'){echo $act('suspend',icon('pause',14).' '.e(t('admin.suspend')),'tp-btn--ghost',true);}else{echo $act('activate',icon('play',14).' '.e(t('admin.reactivate')),'tp-btn--primary');}?>
      <?php echo $act('revoke',icon('x',14).' '.e(t('admin.revoke')),'tp-btn--danger',true);?>
    <?php }?>
    <form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action"><?php echo csrf_field();?><input type="hidden" name="action" value="extend"><label><?php echo e(t('admin.extend'));?></label><div class="tp-row"><input class="tp-input" type="number" name="months" min="1" max="120" value="1"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo e(t('admin.months'));?></button></div></form>
    <form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action"><?php echo csrf_field();?><input type="hidden" name="action" value="set_expiry"><label><?php echo e(t('license.expires'));?></label><div class="tp-row"><input class="tp-input" type="date" name="expires_at" value="<?php echo e(substr((string)$l['expires_at'],0,10));?>"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo icon('check',14);?></button></div></form>
    <form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action"><?php echo csrf_field();?><input type="hidden" name="action" value="max_activations"><label><?php echo e(t('license.max_activations'));?></label><div class="tp-row"><input class="tp-input" type="number" name="max_activations" min="1" value="<?php echo(int)$l['max_activations'];?>"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo icon('check',14);?></button></div></form>
    <form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action"><?php echo csrf_field();?><input type="hidden" name="action" value="change_plan"><label><?php echo e(t('license.change_plan'));?></label><div class="tp-row"><select class="tp-select" name="plan"><?php foreach($plans as $p){?><option value="<?php echo e($p['slug']);?>" <?php echo $p['slug']===$l['plan_slug']?'selected':'';?>><?php echo e($p['slug']);?></option><?php }?></select><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo icon('check',14);?></button></div></form>
  </div>
</div>
<div class="tp-grid tp-grid--2 tp-mt-3">
  <div class="tp-card tp-card--table"><h3><?php echo e(t('license.machines'));?></h3>
    <table class="tp-table"><thead><tr><th><?php echo e(t('license.hostname'));?></th><th>machine_id</th><th><?php echo e(t('common.version'));?></th><th>IP</th><th><?php echo e(t('license.last_seen'));?></th><th></th></tr></thead><tbody>
    <?php foreach($activations as $a){?><tr class="<?php echo $a['revoked_at']?'is-muted':'';?>"><td><?php echo e($a['hostname']);?></td><td class="tp-mono" title="<?php echo e($a['machine_id']);?>"><?php echo e(substr($a['machine_id'],0,12));?>…</td><td><?php echo e($a['version']);?></td><td class="tp-mono"><?php echo e($a['ip']);?></td><td><?php echo e(date_fmt($a['last_seen_at'],'d/m H:i'));?></td><td><?php if(!$a['revoked_at']){?><form method="post" action="/admin/licenses/<?php echo(int)$l['id'];?>/action"><?php echo csrf_field();?><input type="hidden" name="action" value="revoke_activation"><input type="hidden" name="activation_id" value="<?php echo(int)$a['id'];?>"><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('x',12);?></button></form><?php }else{?><span class="tp-text-3"><?php echo e(date_fmt($a['revoked_at']));?></span><?php }?></td></tr><?php }?>
    <?php if(!$activations){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('license.no_activation'));?></td></tr><?php }?>
    </tbody></table></div>
  <div class="tp-card tp-card--table"><h3><?php echo e(t('license.history'));?></h3>
    <table class="tp-table"><thead><tr><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('common.action'));?></th><th><?php echo e(t('common.details'));?></th></tr></thead><tbody>
    <?php foreach($events as $ev){?><tr><td><?php echo e(date_fmt($ev['created_at'],'d/m H:i'));?></td><td><span class="tp-badge"><?php echo e($ev['action']);?></span></td><td class="tp-text-3 tp-mono tp-truncate"><?php echo e((string)$ev['details']);?></td></tr><?php }?>
    </tbody></table></div>
</div>
