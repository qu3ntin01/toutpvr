<article class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <a class="tp-back" href="<?php echo e(url('/blog'));?>"><?php echo icon('chevron-left',16);?> <?php echo e(t('nav.blog'));?></a>
    <h1 data-reveal><?php echo e(\App\Core\I18n::pick($post['title']));?></h1>
    <p class="tp-text-3"><?php echo e(date_fmt($post['published_at']));if($post['tags']){?> · <?php foreach(explode(',',$post['tags'])as $tag){?><span class="tp-badge"><?php echo e(trim($tag));?></span> <?php }}?></p>
    <?php if($post['cover']){?><img class="tp-post__cover" src="<?php echo e($post['cover']);?>" alt=""><?php }?>
    <div class="tp-card tp-prose" data-reveal><?php echo \App\Services\CmsService::renderBlocks($post['blocks']);?></div>
  </div>
</article>
