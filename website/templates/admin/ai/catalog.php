<?php include __DIR__.'/_tabs.php';$c=$catalog;$ok=$verify['ok'];$pub=rtrim((string)config('app.url'),'/').'/api/v1/ai/catalog';?>
<div class="tp-grid tp-grid--3 tp-mt-3">
  <div class="tp-card tp-span-2">
    <div class="tp-card__head"><h3><?php echo icon('shield-check',18);?> <?php echo e(t('admin.ai.catalog'));?></h3><span class="tp-badge tp-badge--<?php echo $ok?'success':'warning';?>"><?php echo e($ok?t('admin.ai.signature_valid'):t('admin.ai.signature_invalid'));?></span></div>
    <p class="tp-text-2"><?php echo e(t('admin.ai.catalog_lead'));?></p>
    <dl class="tp-dl tp-dl--grid">
      <dt><?php echo e(t('admin.ai.version'));?></dt><dd><strong><?php echo(int)$c['version'];?></strong></dd>
      <dt><?php echo e(t('admin.ai.updated_at'));?></dt><dd><?php echo e($c['updated_at']);?></dd>
      <dt><?php echo e(t('admin.ai.tab_profiles'));?></dt><dd><?php echo count($c['profiles']);?> · <?php echo e(t('admin.ai.tab_models'));?> : <?php echo count($c['recommended_models']);?></dd>
      <dt><?php echo e(t('admin.ai.public_url'));?></dt><dd><a class="tp-mono" href="<?php echo e($pub);?>" target="_blank" rel="noopener"><?php echo e($pub);?></a></dd>
      <dt><?php echo e(t('admin.ai.sha256'));?></dt><dd class="tp-mono tp-text-3" style="word-break:break-all"><?php echo e($c['sha256']?:'—');?></dd>
      <dt><?php echo e(t('admin.ai.signature'));?></dt><dd class="tp-mono tp-text-3" style="word-break:break-all"><?php echo e($c['signature']?:'—');?></dd>
    </dl>
    <div class="tp-row tp-row--wrap tp-mt-3">
      <form method="post" action="/admin/ai/catalog/publish"><?php echo csrf_field();?><button class="tp-btn tp-btn--primary" type="submit" <?php echo $canSign?'':'disabled';?>><?php echo icon('upload',16);?> <?php echo e(t('admin.ai.publish'));?></button></form>
      <a class="tp-btn tp-btn--ghost" href="/admin/ai/catalog/export"><?php echo icon('download',16);?> <?php echo e(t('admin.ai.export'));?></a>
    </div>
    <details class="tp-details tp-mt-3"><summary><?php echo icon('code',14);?> <?php echo e(t('admin.ai.preview'));?></summary><pre class="tp-code tp-ai-json" data-copy-block><code><?php echo e(json_encode($c,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));?></code></pre></details>
  </div>
  <div>
    <form class="tp-card tp-form" method="post" action="/admin/ai/catalog/import" enctype="multipart/form-data"><?php echo csrf_field();?>
      <h3><?php echo icon('upload',18);?> <?php echo e(t('admin.ai.import'));?></h3>
      <p class="tp-text-3"><?php echo e(t('admin.ai.import_lead'));?></p>
      <div class="tp-field"><input class="tp-input" type="file" name="file" accept="application/json,.json" required></div>
      <div class="tp-field"><label><?php echo e(t('common.type'));?></label><select class="tp-select" name="mode"><option value="merge"><?php echo e(t('admin.ai.mode_merge'));?></option><option value="replace"><?php echo e(t('admin.ai.mode_replace'));?></option></select></div>
      <label class="tp-check"><input type="checkbox" name="dry_run" value="1"> <span><?php echo e(t('admin.ai.dry_run'));?></span></label>
      <button class="tp-btn tp-btn--ghost tp-btn--block tp-mt-2" type="submit"><?php echo e(t('admin.ai.import'));?></button>
    </form>
    <div class="tp-card tp-mt-3"><h3><?php echo icon('key',18);?> <?php echo e(t('admin.public_key'));?></h3><pre class="tp-code" data-copy-block><code><?php echo e($public_key);?></code></pre></div>
  </div>
</div>
<div class="tp-card tp-mt-3" id="ai-test">
  <h3><?php echo icon('eye',18);?> <?php echo e(t('admin.ai.test_profile'));?></h3>
  <p class="tp-text-2"><?php echo e(t('admin.ai.test_profile_lead'));?></p>
  <form class="tp-form" data-ai-profile-test data-ai-target="#ai-profile-result">
    <div class="tp-grid tp-grid--4 tp-grid--tight">
      <div class="tp-field"><label><?php echo e(t('admin.ai.profile'));?></label><select class="tp-select" name="profile_id" required><?php foreach($profiles as $p){?><option value="<?php echo(int)$p['id'];?>" data-model="<?php echo e($p['model']);?>"><?php echo e($p['name']);?> (<?php echo e($p['model']);?>)</option><?php }?></select></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.provider'));?></label><select class="tp-select" name="provider_id"><?php foreach($providers as $pr){?><option value="<?php echo(int)$pr['id'];?>" <?php echo(int)$pr['is_default']?'selected':'';?>><?php echo e($pr['name']);?></option><?php }if(!$providers){?><option value=""><?php echo e(t('admin.ai.no_providers'));?></option><?php }?></select></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.model'));?> (<?php echo e(t('admin.ai.override'));?>)</label><input class="tp-input tp-mono" name="model" placeholder="—"></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.image'));?></label><input class="tp-input" type="file" name="image" accept="image/jpeg,image/png,image/webp" required></div>
    </div>
    <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit" <?php echo $profiles&&$providers?'':'disabled';?>><?php echo icon('zap',16);?> <?php echo e(t('admin.ai.analyze'));?></button></div>
  </form>
  <div class="tp-ai-result tp-mt-3" id="ai-profile-result" data-ai-result></div>
</div>
<?php include __DIR__.'/_js.php';