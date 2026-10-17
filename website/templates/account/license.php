<?php $l=$license;$st=\App\Models\License::effectiveStatus($l);$days=(int)floor((strtotime($l['expires_at'])-time())/86400);?>
<a class="tp-back" href="<?php echo e(url('/account/licenses'));?>"><?php echo icon('chevron-left',16);?> <?php echo e(t('account.licenses'));?></a>
<div class="tp-page-head tp-page-head--row">
  <div><h1><?php echo e(\App\Core\I18n::pick($l['plan_name']));?> <?php partial('status_badge',['status'=>$st]);?></h1><p class="tp-text-2"><?php echo e(t('license.created'));?> <?php echo e(date_fmt($l['created_at']));?> · <?php echo e(t('period.'.$l['period']));?></p></div>
</div>
<div class="tp-card">
  <div class="tp-key" data-copy="<?php echo e($l['license_key']);?>"><code><?php echo e($l['license_key']);?></code><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-copy-btn><?php echo icon('copy',14);?> <?php echo e(t('common.copy'));?></button></div>
  <div class="tp-grid tp-grid--4 tp-kpis tp-mt-2">
    <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('license.expires'));?></span><span class="tp-kpi__value tp-kpi__value--sm"><?php echo e(date_fmt($l['expires_at']));?></span><span class="tp-text-3"><?php echo $days>=0?e(t('license.days_left',['n'=>$days])):e(t('status_label.expired'));?></span></div>
    <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('pricing.max_cameras'));?></span><span class="tp-kpi__value tp-kpi__value--sm"><?php echo $l['max_cameras']===null?e(t('pricing.unlimited')):(int)$l['max_cameras'];?></span></div>
    <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('license.activations'));?></span><span class="tp-kpi__value tp-kpi__value--sm"><?php echo count(array_filter($activations,fn($a)=>!$a['revoked_at']));?>/<?php echo(int)$l['max_activations'];?></span></div>
    <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('license.auto_renew'));?></span><span class="tp-kpi__value tp-kpi__value--sm"><?php echo(int)$l['auto_renew']?e(t('common.yes')):e(t('common.no'));?></span></div>
  </div>
  <div class="tp-tags tp-mt-2"><?php foreach($l['features']as $f){?><span class="tp-badge"><?php echo e(t('feature.'.$f));?></span><?php }?></div>
</div>
<div class="tp-grid tp-grid--2 tp-mt-3">
  <div class="tp-card">
    <h3><?php echo icon('server',18);?> <?php echo e(t('license.machines'));?></h3>
    <?php if(!$activations){?><p class="tp-text-2"><?php echo e(t('license.no_activation'));?></p><pre class="tp-code" data-copy-block><code>toutpvr license activate <?php echo e($l['license_key']);?></code></pre><?php }?>
    <?php foreach($activations as $a){?>
    <div class="tp-activation<?php echo $a['revoked_at']?' is-revoked':'';?>">
      <div><strong><?php echo e($a['hostname']?:'—');?></strong> <span class="tp-text-3">v<?php echo e($a['version']?:'?');?></span><br><span class="tp-text-3 tp-mono"><?php echo e(substr($a['machine_id'],0,16));?>…</span><br><span class="tp-text-3"><?php echo e(t('license.last_seen'));?> <?php echo e(date_fmt($a['last_seen_at'],'d/m/Y H:i'));?></span></div>
      <?php if(!$a['revoked_at']&&$st!=='revoked'){?><form method="post" action="<?php echo e(url('/account/licenses/'.$l['id'].'/revoke-activation'));?>" data-confirm="<?php echo e(t('license.revoke_confirm'));?>"><?php echo csrf_field();?><input type="hidden" name="activation_id" value="<?php echo(int)$a['id'];?>"><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo e(t('license.revoke_activation'));?></button></form><?php }else{?><span class="tp-badge tp-badge--muted"><?php echo e(t('license.revoked_on'));?> <?php echo e(date_fmt($a['revoked_at']));?></span><?php }?>
    </div>
    <?php }?>
  </div>
  <div class="tp-card">
    <h3><?php echo icon('refresh',18);?> <?php echo e(t('license.manage'));?></h3>
    <?php if($st!=='revoked'&&$l['plan_slug']!=='trial'){?>
    <form method="post" action="<?php echo e(url('/account/licenses/'.$l['id'].'/renew'));?>" class="tp-form"><?php echo csrf_field();?>
      <div class="tp-segment"><label class="tp-segment__opt"><input type="radio" name="period" value="month" <?php echo $l['period']==='month'?'checked':'';?>><span><?php echo e(t('pricing.monthly'));?></span></label><label class="tp-segment__opt"><input type="radio" name="period" value="year" <?php echo $l['period']==='year'?'checked':'';?>><span><?php echo e(t('pricing.yearly'));?></span></label></div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo icon('refresh',16);?> <?php echo e(t('license.renew'));?></button>
    </form>
    <?php }?>
    <?php if($st!=='revoked'){?>
    <form method="post" action="<?php echo e(url('/account/licenses/'.$l['id'].'/change-plan'));?>" class="tp-form tp-mt-2"><?php echo csrf_field();?>
      <label for="plan"><?php echo e(t('license.change_plan'));?></label>
      <div class="tp-row"><select class="tp-select" id="plan" name="plan"><?php foreach($plans as $p){if($p['slug']===$l['plan_slug']){continue;}?><option value="<?php echo e($p['slug']);?>"><?php echo e(\App\Core\I18n::pick($p['name']));?> — <?php echo money((int)$p['price_month']);echo e(t('pricing.per_month'));?></option><?php }?></select><button class="tp-btn tp-btn--ghost" type="submit"><?php echo e(t('common.continue'));?></button></div>
    </form>
    <?php }?>
    <?php if((int)$l['auto_renew']&&$st==='active'){?>
    <form method="post" action="<?php echo e(url('/account/licenses/'.$l['id'].'/cancel'));?>" class="tp-mt-2" data-confirm="<?php echo e(t('license.cancel_confirm'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit"><?php echo e(t('license.cancel'));?></button><p class="tp-text-3"><?php echo e(t('license.cancel_hint'));?></p></form>
    <?php }elseif($l['cancelled_at']){?><p class="tp-text-3 tp-mt-2"><?php echo e(t('license.cancelled_on'));?> <?php echo e(date_fmt($l['cancelled_at']));?></p><?php }?>
  </div>
</div>
<div class="tp-card tp-card--table tp-mt-3">
  <h3><?php echo icon('activity',18);?> <?php echo e(t('license.history'));?></h3>
  <table class="tp-table"><thead><tr><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('common.action'));?></th><th><?php echo e(t('common.details'));?></th></tr></thead><tbody>
  <?php foreach($events as $ev){$d=json_decode((string)$ev['details'],true)?:[];?>
  <tr><td><?php echo e(date_fmt($ev['created_at'],'d/m/Y H:i'));?></td><td><span class="tp-badge"><?php echo e($ev['action']);?></span></td><td class="tp-text-3 tp-mono"><?php echo e(implode(' · ',array_map(fn($k,$v)=>$k.'='.(is_scalar($v)?$v:json_encode($v)),array_keys($d),$d)));?></td></tr>
  <?php }?>
  </tbody></table>
</div>
