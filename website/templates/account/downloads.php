<div class="tp-page-head"><h1><?php echo e(t('account.downloads'));?></h1><p class="tp-text-2"><?php echo e(t('download.lead'));?></p></div>
<div class="tp-grid tp-grid--2">
  <?php foreach(['stable'=>$stable,'beta'=>$beta]as $ch=>$r){?>
  <div class="tp-card">
    <div class="tp-card__head"><h3><?php echo icon('package',18);?> <?php echo e(t('download.'.$ch));?></h3><span class="tp-badge tp-badge--<?php echo $ch==='stable'?'success':'warning';?>"><?php echo e($ch);?></span></div>
    <?php if($r){?>
      <p class="tp-download__version">v<?php echo e($r['version']);?> <span class="tp-text-3">· <?php echo e(date_fmt($r['published_at']));?></span></p>
      <a class="tp-btn tp-btn--primary tp-btn--sm" href="<?php echo e(\App\Models\Release::toApi($r)['download_url']);?>"><?php echo icon('download',14);?> toutpvr-<?php echo e($r['version']);?>.tar.gz</a>
      <p class="tp-text-3 tp-mono tp-mt-2">sha256 <?php echo e($r['sha256']);?></p>
      <?php if($cl=\App\Core\I18n::pick($r['changelog'])){?><div class="tp-changelog"><?php echo nl2br(e($cl));?></div><?php }?>
    <?php }else{?><p class="tp-text-2"><?php echo e(t('download.none'));?></p><?php }?>
  </div>
  <?php }?>
</div>
<div class="tp-card tp-mt-3">
  <h3><?php echo icon('shield-check',18);?> <?php echo e(t('download.verify'));?></h3>
  <p class="tp-text-2"><?php echo e(t('download.verify_lead'));?></p>
  <pre class="tp-code" data-copy-block><code>TP_LICENSE_PUBLIC_KEY=<?php echo e($public_key);?></code></pre>
  <pre class="tp-code" data-copy-block><code>toutpvr update check
toutpvr update apply</code></pre>
</div>
<?php if($releases){?>
<div class="tp-card tp-card--table tp-mt-3"><h3><?php echo e(t('download.history'));?></h3>
<table class="tp-table"><thead><tr><th><?php echo e(t('common.version'));?></th><th><?php echo e(t('common.channel'));?></th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('download.min_version'));?></th><th></th></tr></thead><tbody>
<?php foreach($releases as $r){?><tr><td>v<?php echo e($r['version']);?></td><td><span class="tp-badge"><?php echo e($r['channel']);?></span></td><td><?php echo e(date_fmt($r['published_at']));?></td><td><?php echo e($r['min_version']);?></td><td><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(\App\Models\Release::toApi($r)['download_url']);?>"><?php echo icon('download',14);?></a></td></tr><?php }?>
</tbody></table></div>
<?php }