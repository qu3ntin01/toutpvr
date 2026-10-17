<section class="tp-section tp-section--first">
  <div class="tp-container tp-split">
    <div data-reveal>
      <span class="tp-eyebrow"><?php echo e(t('nav.contact'));?></span>
      <h1><?php echo e(t('contact.title'));?></h1>
      <p class="tp-text-2"><?php echo e(t('contact.lead'));?></p>
      <ul class="tp-checklist tp-mt-3">
        <li><?php echo icon('mail',16);?> <a href="mailto:<?php echo e(setting('support_email'));?>"><?php echo e(setting('support_email'));?></a></li>
        <li><?php echo icon('book',16);?> <a href="<?php echo e(url('/docs'));?>"><?php echo e(t('contact.docs_hint'));?></a></li>
        <li><?php echo icon('clock',16);?> <?php echo e(t('contact.hours'));?></li>
      </ul>
    </div>
    <form class="tp-card tp-form" method="post" action="<?php echo e(url('/contact'));?>" data-reveal>
      <?php echo csrf_field();?>
      <div class="tp-hp"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="tp-field"><label for="c-name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="c-name" name="name" required value="<?php echo old('name');?>"><?php if(!empty($errors['name'])){?><span class="tp-field__error"><?php echo e($errors['name']);?></span><?php }?></div>
      <div class="tp-field"><label for="c-email"><?php echo e(t('field.email'));?></label><input class="tp-input" id="c-email" type="email" name="email" required value="<?php echo old('email');?>"><?php if(!empty($errors['email'])){?><span class="tp-field__error"><?php echo e($errors['email']);?></span><?php }?></div>
      <div class="tp-field"><label for="c-subject"><?php echo e(t('field.subject'));?></label><input class="tp-input" id="c-subject" name="subject" value="<?php echo old('subject');?>"></div>
      <div class="tp-field"><label for="c-message"><?php echo e(t('field.message'));?></label><textarea class="tp-input" id="c-message" name="message" rows="6" required><?php echo old('message');?></textarea><?php if(!empty($errors['message'])){?><span class="tp-field__error"><?php echo e($errors['message']);?></span><?php }?></div>
      <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo icon('mail',18);?> <?php echo e(t('contact.send'));?></button>
    </form>
  </div>
</section>
