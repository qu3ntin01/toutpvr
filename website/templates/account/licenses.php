<div class="tp-page-head tp-page-head--row"><div><h1><?php echo e(t('account.licenses'));?></h1><p class="tp-text-2"><?php echo e(t('account.licenses_lead'));?></p></div><a class="tp-btn tp-btn--primary" href="<?php echo e(url('/pricing'));?>"><?php echo icon('plus',16);?> <?php echo e(t('account.new_license'));?></a></div>
<?php if(!$licenses){?><div class="tp-card tp-center"><p class="tp-text-2"><?php echo e(t('account.no_license_lead'));?></p></div><?php }?>
<div class="tp-grid tp-grid--2">
<?php foreach($licenses as $l){$st=\App\Models\License::effectiveStatus($l);?>
  <a class="tp-card tp-license-card" href="<?php echo e(url('/account/licenses/'.$l['id']));?>">
    <div class="tp-card__head"><strong><?php echo e(\App\Core\I18n::pick($l['plan']['name']));?></strong><?php partial('status_badge',['status'=>$st]);?></div>
    <code class="tp-key tp-key--sm"><?php echo e($l['license_key']);?></code>
    <div class="tp-license-card__meta">
      <span><?php echo icon('calendar',14);?> <?php echo e(t('license.expires'));?> <?php echo e(date_fmt($l['expires_at']));?></span>
      <span><?php echo icon('server',14);?> <?php echo(int)$l['activations'];?>/<?php echo(int)$l['max_activations'];?></span>
      <span><?php echo icon('camera',14);?> <?php echo $l['max_cameras']===null?e(t('pricing.unlimited')):(int)$l['max_cameras'];?></span>
    </div>
  </a>
<?php }?>
</div>
