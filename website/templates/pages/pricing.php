<section class="tp-section tp-section--first">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.pricing'));?></span><h1><?php echo e(t('pricing.title'));?></h1><p class="tp-text-2"><?php echo e(t('pricing.lead'));?></p></div>
    <?php partial('pricing_cards',['plans'=>$plans]);?>
    <div class="tp-center tp-mt-4" data-reveal>
      <?php if(!empty($auth)){?>
        <form method="post" action="<?php echo e(url('/account/trial'));?>" class="tp-inline"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--lg" type="submit"><?php echo icon('sparkles',18);?> <?php echo e(t('pricing.start_trial'));?></button></form>
      <?php }else{?>
        <a class="tp-btn tp-btn--ghost tp-btn--lg" href="<?php echo e(url('/register'));?>"><?php echo icon('sparkles',18);?> <?php echo e(t('pricing.start_trial'));?></a>
      <?php }?>
    </div>
  </div>
</section>
<section class="tp-section">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><h2><?php echo e(t('pricing.compare_title'));?></h2></div>
    <div class="tp-card tp-card--table" data-reveal>
      <table class="tp-table tp-table--compare">
        <thead><tr><th><?php echo e(t('pricing.feature'));?></th><?php foreach($plans as $p){?><th><?php echo e(\App\Core\I18n::pick($p['name']));?></th><?php }?></tr></thead>
        <tbody>
          <tr><th><?php echo e(t('pricing.price_month'));?></th><?php foreach($plans as $p){?><td><strong><?php echo money((int)$p['price_month']);?></strong></td><?php }?></tr>
          <tr><th><?php echo e(t('pricing.price_year'));?></th><?php foreach($plans as $p){?><td><strong><?php echo money((int)$p['price_year']);?></strong></td><?php }?></tr>
          <tr><th><?php echo e(t('pricing.max_cameras'));?></th><?php foreach($plans as $p){?><td><?php echo $p['max_cameras']===null?e(t('pricing.unlimited')):(int)$p['max_cameras'];?></td><?php }?></tr>
          <tr><th><?php echo e(t('pricing.retention_days'));?></th><?php foreach($plans as $p){?><td><?php echo(int)$p['retention_days'];?></td><?php }?></tr>
          <?php foreach(\App\Models\Plan::ALL_FEATURES as $f){?>
          <tr><th><?php echo e(t('feature.'.$f));?></th><?php foreach($plans as $p){?><td><?php echo in_array($f,$p['features'],true)?'<span class="tp-yes">'.icon('check',16).'</span>':'<span class="tp-no">'.icon('minus',16).'</span>';?></td><?php }?></tr>
          <?php }?>
          <tr><th><?php echo e(t('pricing.activations'));?></th><?php foreach($plans as $p){?><td>1 <?php echo e(t('pricing.server'));?></td><?php }?></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
<section class="tp-section">
  <div class="tp-container tp-container--narrow">
    <div class="tp-section__head" data-reveal><h2><?php echo e(t('home.faq_title'));?></h2></div>
    <?php foreach([7,8,9,10]as $i){?>
    <details class="tp-faq" data-reveal><summary><?php echo e(t('faq.q'.$i));echo icon('chevron-down',18);?></summary><div><?php echo e(t('faq.a'.$i));?></div></details>
    <?php }?>
  </div>
</section>
