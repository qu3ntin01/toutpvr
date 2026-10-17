<aside class="tp-card tp-summary">
  <h3><?php echo e(t('checkout.summary'));?></h3>
  <div class="tp-summary__plan"><strong><?php echo e(\App\Core\I18n::pick($plan['name']));?></strong><span class="tp-badge"><?php echo e(t('period.'.$period));?></span></div>
  <ul class="tp-summary__lines">
    <li><span><?php echo e(t('checkout.subtotal'));?></span><span><?php echo money($calc['subtotal']);?></span></li>
    <?php if($calc['discount']>0){?><li><span><?php echo e(t('invoice.discount'));echo!empty($coupon)?' ('.e($coupon['code']).')':'';?></span><span>− <?php echo money($calc['discount']);?></span></li><?php }?>
    <li><span><?php echo e(t('invoice.vat'));?> <?php echo rtrim(rtrim(number_format((float)$calc['tax_rate'],2,'.',''),'0'),'.');?> %</span><span><?php echo money($calc['tax']);?></span></li>
    <li class="tp-summary__total"><span><?php echo e(t('invoice.total'));?></span><span><?php echo money($calc['total']);?></span></li>
  </ul>
  <?php if($calc['reverse_charge']){?><p class="tp-text-3"><?php echo e(t('invoice.reverse_charge_note'));?></p><?php }?>
  <p class="tp-text-3"><?php echo icon('lock',12);?> <?php echo e(t('checkout.secure'));?></p>
</aside>
