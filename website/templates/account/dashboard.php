<div class="tp-page-head"><h1><?php echo e(t('account.welcome',['name'=>$auth['name']?:$auth['email']]));?></h1><p class="tp-text-2"><?php echo e(t('account.dashboard_lead'));?></p></div>
<?php $active=array_filter($licenses,fn($l)=>\App\Models\License::effectiveStatus($l)==='active');?>
<div class="tp-grid tp-grid--4 tp-kpis">
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('account.active_licenses'));?></span><span class="tp-kpi__value"><?php echo count($active);?></span></div>
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('account.activations'));?></span><span class="tp-kpi__value"><?php echo array_sum(array_column($licenses,'activations'));?></span></div>
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('account.orders'));?></span><span class="tp-kpi__value"><?php echo count($orders);?></span></div>
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('account.latest_version'));?></span><span class="tp-kpi__value"><?php echo $release?'v'.e($release['version']):'—';?></span></div>
</div>
<?php if(!$licenses){?>
<div class="tp-card tp-center tp-mt-3">
  <h2><?php echo e(t('account.no_license_title'));?></h2>
  <p class="tp-text-2"><?php echo e(t('account.no_license_lead'));?></p>
  <div class="tp-hero__cta tp-center">
    <a class="tp-btn tp-btn--primary" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('pricing.choose'));?></a>
    <?php if(!$hasTrial){?><form method="post" action="<?php echo e(url('/account/trial'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('sparkles',16);?> <?php echo e(t('pricing.start_trial'));?></button></form><?php }?>
  </div>
</div>
<?php }else{?>
<div class="tp-card tp-card--table tp-mt-3">
  <div class="tp-card__head"><h2><?php echo e(t('account.licenses'));?></h2><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/account/licenses'));?>"><?php echo e(t('common.see_all'));?></a></div>
  <table class="tp-table"><thead><tr><th><?php echo e(t('license.key'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('common.status'));?></th><th><?php echo e(t('license.expires'));?></th><th><?php echo e(t('license.activations'));?></th><th></th></tr></thead><tbody>
  <?php foreach(array_slice($licenses,0,5)as $l){?>
  <tr><td><code class="tp-mono"><?php echo e($l['license_key']);?></code></td><td><?php echo e(\App\Core\I18n::pick($l['plan']['name']));?></td><td><?php partial('status_badge',['status'=>\App\Models\License::effectiveStatus($l)]);?></td><td><?php echo e(date_fmt($l['expires_at']));?></td><td><?php echo(int)$l['activations'];?>/<?php echo(int)$l['max_activations'];?></td><td><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/account/licenses/'.$l['id']));?>"><?php echo icon('chevron-right',16);?></a></td></tr>
  <?php }?>
  </tbody></table>
</div>
<?php }?>
<div class="tp-grid tp-grid--2 tp-mt-3">
  <div class="tp-card">
    <h3><?php echo icon('download',18);?> <?php echo e(t('account.downloads'));?></h3>
    <?php if($release){?><p class="tp-text-2">ToutPVR v<?php echo e($release['version']);?> · <?php echo e(date_fmt($release['published_at']));?></p><a class="tp-btn tp-btn--primary tp-btn--sm" href="<?php echo e(\App\Models\Release::toApi($release)['download_url']);?>"><?php echo icon('download',14);?> <?php echo e(t('download.get'));?></a><?php }else{?><p class="tp-text-2"><?php echo e(t('download.none'));?></p><?php }?>
  </div>
  <div class="tp-card">
    <h3><?php echo icon('help',18);?> <?php echo e(t('account.help_title'));?></h3>
    <p class="tp-text-2"><?php echo e(t('account.help_lead'));?></p>
    <div class="tp-row tp-row--wrap"><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/docs'));?>"><?php echo icon('book',14);?> <?php echo e(t('nav.docs'));?></a><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/contact'));?>"><?php echo icon('mail',14);?> <?php echo e(t('nav.contact'));?></a></div>
  </div>
</div>
