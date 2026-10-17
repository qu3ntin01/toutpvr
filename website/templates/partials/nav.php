<?php $menu=\App\Services\CmsService::menu('main');?>
<header class="tp-topbar tp-topbar--site" data-topbar>
  <div class="tp-container tp-topbar__inner">
    <a class="tp-brand" href="<?php echo e(url('/'));?>" aria-label="<?php echo e(setting('site.name','ToutPVR'));?>">
      <span class="tp-brand__logo"><?php echo icon('camera',20);?></span><span class="tp-brand__name"><?php echo e(setting('site.name','ToutPVR'));?></span>
    </a>
    <nav class="tp-nav" aria-label="<?php echo e(t('nav.main'));?>" data-nav>
      <?php foreach($menu as $item){?>
        <a class="tp-nav__link" href="<?php echo e($item['href']);?>"<?php echo $item['external']?' target="_blank" rel="noopener"':'';?>><?php echo e($item['label']);?></a>
      <?php }?>
    </nav>
    <div class="tp-topbar__actions">
      <?php partial('lang_switcher');?>
      <?php partial('theme_toggle');?>
      <?php if(!empty($auth)){?>
        <a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/account'));?>"><?php echo icon('user',16);?><span><?php echo e(t('nav.account'));?></span></a>
      <?php }else{?>
        <a class="tp-btn tp-btn--ghost tp-hide-sm" href="<?php echo e(url('/login'));?>"><?php echo e(t('nav.login'));?></a>
      <?php }?>
      <a class="tp-btn tp-btn--primary tp-hide-sm" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('nav.try_free'));?></a>
      <button class="tp-btn tp-btn--ghost tp-btn--icon tp-nav-toggle" type="button" aria-label="<?php echo e(t('nav.menu'));?>" aria-expanded="false" data-nav-toggle><?php echo icon('menu',20);?></button>
    </div>
  </div>
</header>
