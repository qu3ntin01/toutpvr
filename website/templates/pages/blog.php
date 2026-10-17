<section class="tp-section tp-section--first">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.blog'));?></span><h1><?php echo e(t('blog.title'));?></h1><p class="tp-text-2"><?php echo e(t('blog.lead'));?></p></div>
    <?php if(!$posts){?><p class="tp-text-2 tp-center"><?php echo e(t('blog.empty'));?></p><?php }?>
    <div class="tp-grid tp-grid--3">
      <?php foreach($posts as $p){?>
      <a class="tp-card tp-card--post" href="<?php echo e(url('/blog/'.$p['slug']));?>" data-reveal>
        <?php if($p['cover']){?><img class="tp-card__cover" src="<?php echo e($p['cover']);?>" alt="" loading="lazy"><?php }?>
        <span class="tp-text-3"><?php echo e(date_fmt($p['published_at']));?></span>
        <h3><?php echo e(\App\Core\I18n::pick($p['title']));?></h3>
        <p class="tp-text-2"><?php echo e(\App\Core\I18n::pick($p['excerpt']??[])?:\App\Services\CmsService::excerpt($p['blocks']));?></p>
      </a>
      <?php }?>
    </div>
    <?php partial('pagination',['pagination'=>$pagination]);?>
  </div>
</section>
