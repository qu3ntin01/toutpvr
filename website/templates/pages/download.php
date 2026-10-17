<section class="tp-section tp-section--first">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.download'));?></span><h1><?php echo e(t('download.title'));?></h1><p class="tp-text-2"><?php echo e(t('download.lead'));?></p></div>
    <div class="tp-grid tp-grid--2">
      <div class="tp-card" data-reveal>
        <h3><?php echo icon('package',20);?> <?php echo e(t('download.stable'));?></h3>
        <?php if($stable){?>
          <p class="tp-download__version">v<?php echo e($stable['version']);?> <span class="tp-text-3">· <?php echo e(date_fmt($stable['published_at']));?> · <?php echo $stable['size']?round($stable['size']/1048576,1).' Mo':'';?></span></p>
          <a class="tp-btn tp-btn--primary" href="<?php echo e(\App\Models\Release::toApi($stable)['download_url']);?>"><?php echo icon('download',18);?> toutpvr-<?php echo e($stable['version']);?>.tar.gz</a>
          <p class="tp-text-3 tp-mt-2 tp-mono">sha256 <?php echo e($stable['sha256']);?></p>
          <?php if($cl=\App\Core\I18n::pick($stable['changelog'])){?><div class="tp-changelog"><?php echo nl2br(e($cl));?></div><?php }?>
        <?php }else{?>
          <p class="tp-text-2"><?php echo e(t('download.none'));?></p>
        <?php }?>
      </div>
      <div class="tp-card" data-reveal>
        <h3><?php echo icon('terminal',20);?> <?php echo e(t('download.install_title'));?></h3>
        <p class="tp-text-2"><?php echo e(t('download.install_lead'));?></p>
        <pre class="tp-code" data-copy-block><code>pip install toutpvr
toutpvr install
toutpvr start --daemon</code></pre>
        <p class="tp-text-2"><?php echo e(t('download.docker'));?></p>
        <pre class="tp-code" data-copy-block><code>docker run -d --name toutpvr -p 8080:8080 -v tp-data:/data toutpvr/server:<?php echo e($stable['version']??'latest');?></code></pre>
        <a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/docs'));?>"><?php echo icon('book',16);?> <?php echo e(t('nav.docs'));?></a>
      </div>
    </div>
    <div class="tp-grid tp-grid--2 tp-mt-3">
      <div class="tp-card" data-reveal>
        <h3><?php echo icon('server',20);?> <?php echo e(t('download.requirements'));?></h3>
        <ul class="tp-checklist">
          <li><?php echo icon('check',16);?> <?php echo e(t('download.req_1'));?></li>
          <li><?php echo icon('check',16);?> <?php echo e(t('download.req_2'));?></li>
          <li><?php echo icon('check',16);?> <?php echo e(t('download.req_3'));?></li>
          <li><?php echo icon('check',16);?> <?php echo e(t('download.req_4'));?></li>
        </ul>
      </div>
      <div class="tp-card" data-reveal>
        <h3><?php echo icon('shield-check',20);?> <?php echo e(t('download.verify'));?></h3>
        <p class="tp-text-2"><?php echo e(t('download.verify_lead'));?></p>
        <?php if($public_key){?><pre class="tp-code" data-copy-block><code>TP_LICENSE_PUBLIC_KEY=<?php echo e($public_key);?></code></pre><?php }?>
        <?php if($beta){?><p class="tp-text-2"><?php echo e(t('download.beta'));?> : <a href="<?php echo e(\App\Models\Release::toApi($beta)['download_url']);?>">v<?php echo e($beta['version']);?></a></p><?php }?>
      </div>
    </div>
    <?php if(count($releases)>1){?>
    <div class="tp-card tp-card--table tp-mt-3" data-reveal>
      <h3><?php echo e(t('download.history'));?></h3>
      <table class="tp-table"><thead><tr><th><?php echo e(t('common.version'));?></th><th><?php echo e(t('common.channel'));?></th><th><?php echo e(t('common.date'));?></th><th></th></tr></thead><tbody>
      <?php foreach($releases as $r){?><tr><td>v<?php echo e($r['version']);?></td><td><span class="tp-badge"><?php echo e($r['channel']);?></span></td><td><?php echo e(date_fmt($r['published_at']));?></td><td><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(\App\Models\Release::toApi($r)['download_url']);?>"><?php echo icon('download',14);?></a></td></tr><?php }?>
      </tbody></table>
    </div>
    <?php }?>
  </div>
</section>
