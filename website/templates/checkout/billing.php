<section class="tp-section tp-section--first">
  <div class="tp-container">
    <?php partial('../checkout/steps',['step'=>3]);?>
    <div class="tp-checkout">
      <form class="tp-card tp-form" method="post" action="<?php echo e(url('/checkout/'.$plan['slug'].'/billing'));?>" data-billing-form>
        <?php echo csrf_field();?>
        <h2><?php echo e(t('checkout.step_billing'));?></h2>
        <div class="tp-field"><label><?php echo e(t('checkout.period'));?></label>
          <div class="tp-segment">
            <label class="tp-segment__opt"><input type="radio" name="period" value="month" <?php echo $period==='month'?'checked':'';?>><span><?php echo e(t('pricing.monthly'));?> · <?php echo money((int)$plan['price_month']);?></span></label>
            <label class="tp-segment__opt"><input type="radio" name="period" value="year" <?php echo $period==='year'?'checked':'';?>><span><?php echo e(t('pricing.yearly'));?> · <?php echo money((int)$plan['price_year']);?></span></label>
          </div>
        </div>
        <div class="tp-grid tp-grid--2 tp-grid--tight">
          <div class="tp-field"><label for="name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="name" name="name" required value="<?php echo old('name',$user['name']);?>"></div>
          <div class="tp-field"><label for="company"><?php echo e(t('field.company'));?></label><input class="tp-input" id="company" name="company" value="<?php echo old('company',$user['company']);?>"></div>
        </div>
        <div class="tp-field"><label for="address_line1"><?php echo e(t('field.address_line1'));?></label><input class="tp-input" id="address_line1" name="address_line1" required value="<?php echo old('address_line1',$user['address_line1']);?>"></div>
        <div class="tp-field"><label for="address_line2"><?php echo e(t('field.address_line2'));?></label><input class="tp-input" id="address_line2" name="address_line2" value="<?php echo old('address_line2',$user['address_line2']);?>"></div>
        <div class="tp-grid tp-grid--3 tp-grid--tight">
          <div class="tp-field"><label for="postal_code"><?php echo e(t('field.postal_code'));?></label><input class="tp-input" id="postal_code" name="postal_code" required value="<?php echo old('postal_code',$user['postal_code']);?>"></div>
          <div class="tp-field"><label for="city"><?php echo e(t('field.city'));?></label><input class="tp-input" id="city" name="city" required value="<?php echo old('city',$user['city']);?>"></div>
          <div class="tp-field"><label for="country"><?php echo e(t('field.country'));?></label><select class="tp-select" id="country" name="country" required data-vat-country><?php foreach($countries as $code=>$label){?><option value="<?php echo $code;?>" <?php echo($user['country']?:'FR')===$code?'selected':'';?>><?php echo e($label);?></option><?php }?></select></div>
        </div>
        <div class="tp-grid tp-grid--2 tp-grid--tight">
          <div class="tp-field"><label for="vat_number"><?php echo e(t('field.vat_number'));?></label><input class="tp-input" id="vat_number" name="vat_number" placeholder="FR12345678901" value="<?php echo old('vat_number',$user['vat_number']);?>"><span class="tp-field__hint"><?php echo e(t('checkout.vat_hint'));?></span></div>
          <div class="tp-field"><label for="phone"><?php echo e(t('field.phone'));?></label><input class="tp-input" id="phone" name="phone" value="<?php echo old('phone',$user['phone']);?>"></div>
        </div>
        <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo e(t('checkout.continue_payment'));?> <?php echo icon('arrow-right',16);?></button>
      </form>
      <div>
        <?php partial('../checkout/summary',['plan'=>$plan,'calc'=>$calc,'period'=>$period,'coupon'=>$coupon]);?>
        <form class="tp-card tp-form tp-mt-2" method="post" action="<?php echo e(url('/checkout/coupon'));?>">
          <?php echo csrf_field();?><input type="hidden" name="plan" value="<?php echo e($plan['slug']);?>">
          <label for="coupon"><?php echo e(t('checkout.coupon'));?></label>
          <div class="tp-row"><input class="tp-input" id="coupon" name="coupon" value="<?php echo e($coupon['code']??'');?>" placeholder="PROMO"><button class="tp-btn tp-btn--ghost" type="submit"><?php echo e(t('common.apply'));?></button></div>
        </form>
      </div>
    </div>
  </div>
</section>
