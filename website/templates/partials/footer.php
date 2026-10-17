<?php $footer=\App\Services\CmsService::menu('footer');?>
<footer class="tp-footer">
  <div class="tp-container">
    <div class="tp-footer__grid">
      <div>
        <a class="tp-brand" href="<?php echo e(url('/'));?>"><span class="tp-brand__logo"><?php echo icon('camera',20);?></span><span class="tp-brand__name"><?php echo e(setting('site.name','ToutPVR'));?></span></a>
        <p class="tp-text-2 tp-mt-2"><?php echo e(t('footer.about'));?></p>
      </div>
      <div>
        <h4 class="tp-footer__title"><?php echo e(t('footer.product'));?></h4>
        <a href="<?php echo e(url('/features'));?>"><?php echo e(t('nav.features'));?></a>
        <a href="<?php echo e(url('/pricing'));?>"><?php echo e(t('nav.pricing'));?></a>
        <a href="<?php echo e(url('/download'));?>"><?php echo e(t('nav.download'));?></a>
        <a href="<?php echo e(url('/status'));?>"><?php echo e(t('nav.status'));?></a>
      </div>
      <div>
        <h4 class="tp-footer__title"><?php echo e(t('footer.resources'));?></h4>
        <a href="<?php echo e(url('/docs'));?>"><?php echo e(t('nav.docs'));?></a>
        <a href="<?php echo e(url('/blog'));?>"><?php echo e(t('nav.blog'));?></a>
        <a href="<?php echo e(url('/contact'));?>"><?php echo e(t('nav.contact'));?></a>
        <a href="<?php echo e(url('/account'));?>"><?php echo e(t('nav.account'));?></a>
      </div>
      <div>
        <h4 class="tp-footer__title"><?php echo e(t('footer.legal'));?></h4>
        <?php foreach($footer as $item){?><a href="<?php echo e($item['href']);?>"><?php echo e($item['label']);?></a><?php }?>
      </div>
    </div>
    <div class="tp-footer__bottom">
      <span>© <?php echo date('Y');?> <?php echo e(setting('billing.company_name','ToutPVR Éditions'));?> · <?php echo e(t('footer.rights'));?></span>
      <span class="tp-text-3">v<?php echo TP_VERSION;?> · <?php echo e(t('footer.made_with'));?></span>
    </div>
  </div>
</footer>
