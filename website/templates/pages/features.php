<section class="tp-section tp-section--first">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.features'));?></span><h1><?php echo e(t('features.title'));?></h1><p class="tp-text-2"><?php echo e(t('features.lead'));?></p></div>
    <?php $sections=[['camera','f1',['ai_motion','onvif','ptz']],['brain','f2',['ai_person','ai_vehicle','ai_animal','zones','lines']],['hard-drive','f3',[]],['bell','f4',['notifications']],['users','f5',['multi_site','api']],['shield-check','f6',[]]];?>
    <?php foreach($sections as $i=>[$ic,$k,$feats]){?>
    <div class="tp-split tp-feature-row<?php echo $i%2?' tp-split--reverse':'';?>" data-reveal>
      <div>
        <span class="tp-card__icon"><?php echo icon($ic,22);?></span>
        <h2><?php echo e(t('features.'.$k.'.title'));?></h2>
        <p class="tp-text-2"><?php echo e(t('features.'.$k.'.desc'));?></p>
        <p class="tp-text-2"><?php echo e(t('features.'.$k.'.more'));?></p>
        <?php if($feats){?><div class="tp-tags"><?php foreach($feats as $f){?><span class="tp-badge"><?php echo e(t('feature.'.$f));?></span><?php }?></div><?php }?>
      </div>
      <div class="tp-window tp-window--static tp-window--mini">
        <div class="tp-window__header"><div class="tp-window__dots"><span class="tp-window__dot tp-window__dot--close"></span><span class="tp-window__dot tp-window__dot--min"></span><span class="tp-window__dot tp-window__dot--max"></span></div><div class="tp-window__title"><?php echo e(t('features.'.$k.'.title'));?></div></div>
        <div class="tp-window__body tp-feature-illus tp-feature-illus--<?php echo $k;?>"><span></span><span></span><span></span><span></span></div>
      </div>
    </div>
    <?php }?>
  </div>
</section>
<section class="tp-section tp-section--cta"><div class="tp-container"><div class="tp-cta-box" data-reveal><h2><?php echo e(t('home.cta_title'));?></h2><p class="tp-text-2"><?php echo e(t('home.cta_lead'));?></p><a class="tp-btn tp-btn--primary tp-btn--lg" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('home.cta_trial'));?></a></div></div></section>
