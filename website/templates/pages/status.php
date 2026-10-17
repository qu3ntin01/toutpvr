<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.status'));?></span><h1><?php echo e(t('status.title'));?></h1></div>
    <div class="tp-card tp-status-banner tp-status-banner--<?php echo e($status['status']);?>" data-reveal>
      <?php echo icon($status['status']==='operational'?'check-circle':'alert',28);?>
      <div><strong><?php echo e(t('status.'.$status['status']));?></strong><span class="tp-text-3"><?php echo e(t('status.checked_at'));?> <?php echo e(date_fmt($status['time'],'d/m/Y H:i'));?> UTC</span></div>
    </div>
    <div class="tp-card tp-mt-3" data-reveal>
      <ul class="tp-status-list">
        <?php foreach($status['components']as $name=>$state){?>
        <li><span><?php echo e(t('status.component_'.$name));?></span><span class="tp-badge tp-badge--<?php echo $state==='operational'?'success':($state==='outage'?'danger':'warning');?>"><?php echo e(t('status.'.$state));?></span></li>
        <?php }?>
      </ul>
      <p class="tp-text-3 tp-mt-2">JSON : <a href="<?php echo e(url('/status.json'));?>"><?php echo e(url('/status.json'));?></a> · API : <code>/api/v1/status</code></p>
    </div>
  </div>
</section>
