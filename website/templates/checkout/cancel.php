<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <div class="tp-card tp-center" data-reveal>
      <span class="tp-success__icon tp-success__icon--pending"><?php echo icon('alert',40);?></span>
      <h1><?php echo e(t('checkout.cancelled_title'));?></h1>
      <p class="tp-text-2"><?php echo e(t('checkout.cancelled_lead'));?></p>
      <div class="tp-hero__cta tp-center"><a class="tp-btn tp-btn--primary" href="<?php echo e(url('/checkout/'.$order['plan_slug'].'/billing'));?>"><?php echo e(t('checkout.retry'));?></a><a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('nav.pricing'));?></a></div>
    </div>
  </div>
</section>
