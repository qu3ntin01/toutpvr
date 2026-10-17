<div class="tp-page-head"><h1><?php echo e(t('account.profile'));?></h1><p class="tp-text-2"><?php echo e(t('account.profile_lead'));?></p></div>
<form class="tp-card tp-form" method="post" action="<?php echo e(url('/account/profile'));?>">
  <?php echo csrf_field();?>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label for="name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="name" name="name" required value="<?php echo old('name',$user['name']);?>"></div>
    <div class="tp-field"><label for="email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="email" value="<?php echo e($user['email']);?>" disabled></div>
    <div class="tp-field"><label for="company"><?php echo e(t('field.company'));?></label><input class="tp-input" id="company" name="company" value="<?php echo old('company',$user['company']);?>"></div>
    <div class="tp-field"><label for="vat_number"><?php echo e(t('field.vat_number'));?></label><input class="tp-input" id="vat_number" name="vat_number" value="<?php echo old('vat_number',$user['vat_number']);?>"></div>
  </div>
  <div class="tp-field"><label for="address_line1"><?php echo e(t('field.address_line1'));?></label><input class="tp-input" id="address_line1" name="address_line1" value="<?php echo old('address_line1',$user['address_line1']);?>"></div>
  <div class="tp-field"><label for="address_line2"><?php echo e(t('field.address_line2'));?></label><input class="tp-input" id="address_line2" name="address_line2" value="<?php echo old('address_line2',$user['address_line2']);?>"></div>
  <div class="tp-grid tp-grid--3 tp-grid--tight">
    <div class="tp-field"><label for="postal_code"><?php echo e(t('field.postal_code'));?></label><input class="tp-input" id="postal_code" name="postal_code" value="<?php echo old('postal_code',$user['postal_code']);?>"></div>
    <div class="tp-field"><label for="city"><?php echo e(t('field.city'));?></label><input class="tp-input" id="city" name="city" value="<?php echo old('city',$user['city']);?>"></div>
    <div class="tp-field"><label for="country"><?php echo e(t('field.country'));?></label><select class="tp-select" id="country" name="country"><?php foreach($countries as $code=>$label){?><option value="<?php echo $code;?>" <?php echo($user['country']?:'FR')===$code?'selected':'';?>><?php echo e($label);?></option><?php }?></select></div>
  </div>
  <div class="tp-grid tp-grid--2 tp-grid--tight">
    <div class="tp-field"><label for="phone"><?php echo e(t('field.phone'));?></label><input class="tp-input" id="phone" name="phone" value="<?php echo old('phone',$user['phone']);?>"></div>
    <div class="tp-field"><label for="lang"><?php echo e(t('common.language'));?></label><select class="tp-select" id="lang" name="lang"><?php foreach(\App\Core\I18n::active()as $l){?><option value="<?php echo $l;?>" <?php echo($user['lang']?:'fr')===$l?'selected':'';?>><?php echo e(\App\Core\I18n::name($l));?></option><?php }?></select></div>
  </div>
  <button class="tp-btn tp-btn--primary" type="submit"><?php echo icon('check',16);?> <?php echo e(t('common.save'));?></button>
</form>
