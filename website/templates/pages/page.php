<section class="tp-section tp-section--first">
  <div class="tp-container tp-container--narrow">
    <h1 data-reveal><?php echo e(\App\Core\I18n::pick($page['title']));?></h1>
    <div class="tp-card tp-prose" data-reveal><?php echo \App\Services\CmsService::renderBlocks($page['blocks']);?></div>
    <p class="tp-text-3 tp-mt-2"><?php echo e(t('common.updated'));?> : <?php echo e(date_fmt($page['updated_at']?:$page['created_at']));?></p>
  </div>
</section>
