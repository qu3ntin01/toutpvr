<form class="tp-card tp-form tp-upload" method="post" action="/admin/media/upload" enctype="multipart/form-data"><?php echo csrf_field();?>
  <label class="tp-upload__zone"><?php echo icon('upload',24);?><span><?php echo e(t('admin.upload_hint'));?></span><input type="file" name="files[]" multiple accept="image/*,.pdf,.svg" data-auto-submit></label>
  <button class="tp-btn tp-btn--primary tp-btn--sm" type="submit"><?php echo e(t('admin.upload'));?></button>
</form>
<div class="tp-media-grid tp-mt-3">
  <?php foreach($res['items']as $m){$url=\App\Models\Media::url($m);?>
  <div class="tp-media">
    <?php if(str_starts_with($m['mime'],'image/')){?><img src="<?php echo e($url);?>" alt="<?php echo e($m['filename']);?>" loading="lazy"><?php }else{?><div class="tp-media__file"><?php echo icon('file',28);?></div><?php }?>
    <div class="tp-media__meta"><span class="tp-truncate" title="<?php echo e($m['filename']);?>"><?php echo e($m['filename']);?></span><span class="tp-text-3"><?php echo round($m['size']/1024);?> Ko<?php echo $m['width']?' · '.(int)$m['width'].'×'.(int)$m['height']:'';?></span></div>
    <div class="tp-media__actions"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-copy-text="<?php echo e($url);?>"><?php echo icon('copy',12);?> URL</button><form method="post" action="/admin/media/<?php echo(int)$m['id'];?>/delete" data-confirm="<?php echo e(t('common.confirm_delete'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('trash',12);?></button></form></div>
  </div>
  <?php }?>
  <?php if(!$res['items']){?><p class="tp-text-3"><?php echo e(t('common.nothing'));?></p><?php }?>
</div>
<?php partial('admin_pagination',['res'=>$res]);