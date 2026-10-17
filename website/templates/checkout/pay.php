<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <?php partial('../checkout/steps',['step'=>4]);?>
    <div class="tp-checkout">
      <div class="tp-card tp-form">
        <h2><?php echo e(t('checkout.payment'));?></h2>
        <?php if($mode==='manual'){?>
          <p class="tp-text-2"><?php echo e(t('checkout.manual_lead'));?></p>
          <pre class="tp-code"><?php echo e((string)setting('payment.bank_details',t('checkout.manual_no_details')));?></pre>
          <form method="post" action="<?php echo e(url('/checkout/pay/'.$order['id'].'/sandbox'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('checkout.manual_confirm'));?></button></form>
        <?php }else{?>
          <div class="tp-sandbox">
            <span class="tp-badge tp-badge--warning"><?php echo e(t('checkout.sandbox_badge'));?></span>
            <p class="tp-text-2"><?php echo e(t('checkout.sandbox_lead'));?></p>
            <div class="tp-fake-card">
              <div class="tp-fake-card__chip"></div>
              <div class="tp-fake-card__num">4242 4242 4242 4242</div>
              <div class="tp-fake-card__row"><span>TOUTPVR SANDBOX</span><span>12/34</span></div>
            </div>
            <form method="post" action="<?php echo e(url('/checkout/pay/'.$order['id'].'/sandbox'));?>" class="tp-row tp-row--wrap">
              <?php echo csrf_field();?>
              <button class="tp-btn tp-btn--primary" type="submit" name="outcome" value="success"><?php echo icon('credit-card',16);?> <?php echo e(t('checkout.pay_now',['amount'=>money((int)$order['total'])]));?></button>
              <button class="tp-btn tp-btn--ghost" type="submit" name="outcome" value="fail"><?php echo e(t('checkout.simulate_failure'));?></button>
            </form>
          </div>
        <?php }?>
      </div>
      <?php partial('../checkout/summary',['plan'=>$plan,'period'=>$order['period'],'calc'=>['subtotal'=>(int)$order['subtotal'],'discount'=>(int)$order['discount'],'tax_rate'=>$order['tax_rate'],'tax'=>(int)$order['tax'],'total'=>(int)$order['total'],'reverse_charge'=>(bool)$order['reverse_charge']],'coupon'=>$order['coupon_code']?['code'=>$order['coupon_code']]:null]);?>
    </div>
  </div>
</section>
