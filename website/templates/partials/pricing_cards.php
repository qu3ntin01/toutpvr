<?php $compact=$compact??false;?>
<div class="tp-pricing-toggle" data-pricing-toggle>
  <span class="tp-pricing-toggle__label is-active" data-period-label="month"><?php echo e(t('pricing.monthly'));?></span>
  <label class="tp-switch"><input type="checkbox" data-period-switch aria-label="<?php echo e(t('pricing.yearly'));?>"><span class="tp-switch__track"></span></label>
  <span class="tp-pricing-toggle__label" data-period-label="year"><?php echo e(t('pricing.yearly'));?> <span class="tp-badge tp-badge--success"><?php echo e(t('pricing.save',['pct'=>17]));?></span></span>
</div>
<div class="tp-grid tp-grid--3 tp-pricing">
  <?php foreach($plans as $p){$featured=(int)$p['is_featured'];?>
  <article class="tp-card tp-plan<?php echo $featured?' tp-plan--featured':'';?>" data-reveal>
    <?php if($featured){?><span class="tp-plan__ribbon"><?php echo e(t('pricing.popular'));?></span><?php }?>
    <h3 class="tp-plan__name"><?php echo e(\App\Core\I18n::pick($p['name']));?></h3>
    <p class="tp-text-2 tp-plan__desc"><?php echo e(t('plan.'.$p['slug'].'.desc'));?></p>
    <div class="tp-plan__price">
      <span class="tp-plan__amount" data-price-month="<?php echo money((int)$p['price_month']);?>" data-price-year="<?php echo money((int)round($p['price_year']/12));?>"><?php echo money((int)$p['price_month']);?></span>
      <span class="tp-plan__period"><?php echo e(t('pricing.per_month'));?></span>
    </div>
    <p class="tp-text-3 tp-plan__yearly" data-yearly-note hidden><?php echo e(t('pricing.billed_yearly',['amount'=>money((int)$p['price_year'])]));?></p>
    <ul class="tp-plan__features">
      <li><?php echo icon('camera',16);?> <?php echo $p['max_cameras']===null?e(t('pricing.unlimited_cameras')):e(t('pricing.cameras',['n'=>(int)$p['max_cameras']]));?></li>
      <li><?php echo icon('hard-drive',16);?> <?php echo e(t('pricing.retention',['n'=>(int)$p['retention_days']]));?></li>
      <?php foreach(array_slice($p['features'],0,$compact?4:12)as $f){?><li><?php echo icon('check',16);?> <?php echo e(t('feature.'.$f));?></li><?php }?>
    </ul>
    <a class="tp-btn <?php echo $featured?'tp-btn--primary':'tp-btn--ghost';?> tp-btn--block" href="<?php echo e(url('/checkout/'.$p['slug']));?>" data-checkout-link="<?php echo e(url('/checkout/'.$p['slug']));?>"><?php echo e(t('pricing.choose'));?></a>
  </article>
  <?php }?>
</div>
<p class="tp-text-3 tp-center tp-mt-3"><?php echo e(t('pricing.vat_note'));?> · <?php echo e(t('pricing.trial_note'));?></p>
