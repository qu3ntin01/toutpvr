<a class="tp-back" href="/admin/updates"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.updates'));?></a>
<form class="tp-card tp-form" method="post" action="/admin/updates/create" enctype="multipart/form-data"><?php echo csrf_field();?>
  <h3><?php echo icon('package',18);?> <?php echo e(t('admin.publish_release'));?></h3>
  <p class="tp-text-2"><?php echo e(t('admin.publish_lead'));?></p>
  <div class="tp-grid tp-grid--3 tp-grid--tight">
    <div class="tp-field"><label><?php echo e(t('common.version'));?></label><input class="tp-input" name="version" required placeholder="1.1.0" pattern="\d+\.\d+\.\d+.*" value="<?php echo old('version');?>"></div>
    <div class="tp-field"><label><?php echo e(t('common.channel'));?></label><select class="tp-select" name="channel"><option value="stable">stable</option><option value="beta">beta</option></select></div>
    <div class="tp-field"><label><?php echo e(t('download.min_version'));?></label><input class="tp-input" name="min_version" required value="<?php echo old('min_version','1.0.0');?>"></div>
  </div>
  <div class="tp-field"><label><?php echo e(t('admin.package_file'));?> (toutpvr-x.y.z.tar.gz)</label><input class="tp-input" type="file" name="package" accept=".tar.gz,.tgz,application/gzip"><span class="tp-field__hint"><?php echo e(t('admin.package_hint'));?></span></div>
  <details class="tp-details"><summary><?php echo e(t('admin.external_package'));?></summary>
    <div class="tp-grid tp-grid--3 tp-grid--tight">
      <div class="tp-field"><label>download_url</label><input class="tp-input" name="download_url" placeholder="https://…/toutpvr-1.1.0.tar.gz"></div>
      <div class="tp-field"><label>sha256</label><input class="tp-input tp-mono" name="sha256" pattern="[a-fA-F0-9]{64}"></div>
      <div class="tp-field"><label><?php echo e(t('admin.size'));?> (octets)</label><input class="tp-input" type="number" name="size"></div>
    </div>
  </details>
  <div class="tp-field"><label>notes_url</label><input class="tp-input" name="notes_url" placeholder="https://…"></div>
  <?php partial('multilang_field',['name'=>'changelog','label'=>t('admin.changelog'),'values'=>[],'type'=>'textarea','rows'=>5]);?>
  <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo icon('upload',16);?> <?php echo e(t('admin.publish'));?></button></div>
</form>
