<div class="tp-grid tp-grid--2">
<?php foreach($menus as $m){?>
  <form class="tp-card tp-form" method="post" action="/admin/menus/save" data-menu-editor><?php echo csrf_field();?><input type="hidden" name="slug" value="<?php echo e($m['slug']);?>"><input type="hidden" name="items_json" data-menu-json value="<?php echo e(json_encode($m['items'],JSON_UNESCAPED_UNICODE));?>">
    <div class="tp-card__head"><h3><?php echo icon('list',18);?> <?php echo e($m['name']);?> <code class="tp-text-3"><?php echo e($m['slug']);?></code></h3><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-menu-add><?php echo icon('plus',14);?> <?php echo e(t('admin.add_item'));?></button></div>
    <div class="tp-menu-items" data-menu-items data-labels='<?php echo e(json_encode(['key'=>t('admin.menu_key'),'label'=>t('admin.menu_label'),'url'=>'URL','remove'=>t('common.delete')],JSON_UNESCAPED_UNICODE));?>'></div>
    <p class="tp-text-3"><?php echo e(t('admin.menu_hint'));?></p>
    <button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button>
  </form>
<?php }?>
  <form class="tp-card tp-form" method="post" action="/admin/menus/save"><?php echo csrf_field();?><input type="hidden" name="items_json" value="[]">
    <h3><?php echo e(t('admin.new_menu'));?></h3>
    <div class="tp-field"><label>Slug</label><input class="tp-input" name="slug" required pattern="[a-z0-9-]+"></div>
    <div class="tp-field"><label><?php echo e(t('field.name'));?></label><input class="tp-input" name="name" required></div>
    <button class="tp-btn tp-btn--ghost" type="submit"><?php echo e(t('common.create'));?></button>
  </form>
</div>
