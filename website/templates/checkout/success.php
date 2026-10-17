<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <?php partial('../checkout/steps',['step'=>5]);?>
    <div class="tp-card tp-center tp-success" data-reveal>
      <?php if($order['status']==='paid'&&$license){?>
        <span class="tp-success__icon"><?php echo icon('check-circle',40);?></span>
        <h1><?php echo e(t('checkout.success_title'));?></h1>
        <p class="tp-text-2"><?php echo e(t('checkout.success_lead'));?></p>
        <div class="tp-key" data-copy="<?php echo e($license['license_key']);?>"><code><?php echo e($license['license_key']);?></code><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-copy-btn><?php echo icon('copy',14);?> <?php echo e(t('common.copy'));?></button></div>
        <pre class="tp-code tp-left" data-copy-block><code>toutpvr license activate <?php echo e($license['license_key']);?></code></pre>
        <p class="tp-text-3"><?php echo e(t('checkout.email_sent',['email'=>$order['user_email']]));?></p>
        <div class="tp-hero__cta tp-center">
          <a class="tp-btn tp-btn--primary" href="<?php echo e(url('/account/licenses/'.$license['id']));?>"><?php echo e(t('account.license'));?></a>
          <?php if($invoice){?><a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/account/invoices/'.$invoice['id'].'/pdf'));?>"><?php echo icon('file-text',16);?> <?php echo e(t('invoice.title'));?> <?php echo e($invoice['number']);?></a><?php }?>
          <a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/download'));?>"><?php echo icon('download',16);?> <?php echo e(t('nav.download'));?></a>
        </div>
      <?php }else{?>
        <span class="tp-success__icon tp-success__icon--pending"><?php echo icon('clock',40);?></span>
        <h1><?php echo e(t('checkout.pending_title'));?></h1>
        <p class="tp-text-2"><?php echo e(t('checkout.pending_lead'));?></p>
        <a class="tp-btn tp-btn--primary" href="<?php echo e(url('/account'));?>"><?php echo e(t('nav.account'));?></a>
      <?php }?>
    </div>
  </div>
</section>
