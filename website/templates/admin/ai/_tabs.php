<?php $aiTabs=['providers'=>t('admin.ai.tab_providers'),'profiles'=>t('admin.ai.tab_profiles'),'catalog'=>t('admin.ai.tab_catalog'),'models'=>t('admin.ai.tab_models')];$tab=$tab??'providers';?>
<div class="tp-page-head tp-page-head--row">
  <div><p class="tp-text-2"><?php echo e(t('admin.ai.lead'));?></p></div>
  <?php if(empty($canSign)){?><span class="tp-badge tp-badge--warning"><?php echo icon('alert',14);?> <?php echo e(t('admin.ai.no_private_key'));?></span><?php }?>
</div>
<div class="tp-tabs" data-tabs data-tabs-active="<?php echo e($tab);?>">
  <div class="tp-tabs__list" role="tablist"><?php foreach($aiTabs as $k=>$label){?><a class="tp-tabs__tab<?php echo $tab===$k?' is-active':'';?>" role="tab" href="/admin/ai?tab=<?php echo $k;?>"<?php echo $tab===$k?' aria-current="page"':'';?>><?php echo e($label);?></a><?php }?></div>
</div>
