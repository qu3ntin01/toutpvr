<?php $p=$provider??null;$v=fn(string $k,mixed $d='')=>old($k,$p[$k]??$d);$errs=$errors??[];?>
<a class="tp-back" href="/admin/ai?tab=providers"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.ai.tab_providers'));?></a>
<form class="tp-form" method="post" action="/admin/ai/providers/save" id="ai-provider-form" data-ai-provider-form><?php echo csrf_field();?><input type="hidden" name="id" value="<?php echo(int)($p['id']??0);?>">
  <div class="tp-grid tp-grid--3">
    <div class="tp-card tp-span-2">
      <h3><?php echo icon('server',18);?> <?php echo e($p?t('admin.ai.edit_provider'):t('admin.ai.add_provider'));?></h3>
      <div class="tp-grid tp-grid--2 tp-grid--tight">
        <div class="tp-field"><label><?php echo e(t('admin.ai.name'));?></label><input class="tp-input" name="name" required maxlength="191" value="<?php echo $v('name');?>" placeholder="Ollama GPU bureau"><?php if(isset($errs['name'])){?><span class="tp-field__error"><?php echo e($errs['name']);?></span><?php }?></div>
        <div class="tp-field"><label><?php echo e(t('admin.ai.identifier'));?></label><input class="tp-input tp-mono" name="slug" pattern="[a-z0-9._-]*" maxlength="64" value="<?php echo $v('slug');?>" placeholder="ollama-main"><span class="tp-field__hint"><?php echo e(t('admin.ai.identifier_hint'));?></span><?php if(isset($errs['slug'])||isset($errs['id'])){?><span class="tp-field__error"><?php echo e($errs['slug']??$errs['id']);?></span><?php }?></div>
      </div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.base_url'));?></label><input class="tp-input tp-mono" name="base_url" required value="<?php echo $v('base_url');?>" placeholder="http://192.168.1.50:11434"><span class="tp-field__hint"><?php echo e(t('admin.ai.base_url_hint'));?></span><?php if(isset($errs['base_url'])){?><span class="tp-field__error"><?php echo e($errs['base_url']);?></span><?php }?></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.api_key'));?></label><input class="tp-input tp-mono" type="password" name="api_key" value="" autocomplete="new-password" placeholder="<?php echo!empty($p['api_key_enc'])?'••••••••':'';?>"><span class="tp-field__hint"><?php echo e(t('admin.ai.api_key_hint'));?></span></div>
      <?php if(!empty($p['api_key_enc'])){?><label class="tp-check"><input type="checkbox" name="clear_api_key" value="1"> <span><?php echo e(t('admin.ai.clear_api_key'));?></span></label><?php }?>
      <div class="tp-grid tp-grid--3 tp-grid--tight tp-mt-2">
        <div class="tp-field"><label><?php echo e(t('admin.ai.default_model'));?></label><input class="tp-input tp-mono" name="model" list="ai-model-list" value="<?php echo $v('model');?>" placeholder="qwen2.5vl:7b"><datalist id="ai-model-list" data-ai-model-list></datalist><span class="tp-field__hint"><?php echo e(t('admin.ai.model_hint'));?></span></div>
        <div class="tp-field"><label><?php echo e(t('admin.ai.timeout'));?></label><input class="tp-input" type="number" name="timeout_s" min="1" max="600" value="<?php echo $v('timeout_s',30);?>"><?php if(isset($errs['timeout_s'])){?><span class="tp-field__error"><?php echo e($errs['timeout_s']);?></span><?php }?></div>
        <div class="tp-field"><label><?php echo e(t('admin.ai.keep_alive'));?></label><input class="tp-input" name="keep_alive" value="<?php echo $v('keep_alive','10m');?>" placeholder="10m"><?php if(isset($errs['keep_alive'])){?><span class="tp-field__error"><?php echo e($errs['keep_alive']);?></span><?php }?></div>
      </div>
      <div class="tp-row tp-row--wrap tp-mt-2">
        <label class="tp-check"><input type="checkbox" name="enabled" value="1" <?php echo $p===null||(int)$p['enabled']?'checked':'';?>> <span><?php echo e(t('admin.ai.enabled'));?></span></label>
        <label class="tp-check"><input type="checkbox" name="is_default" value="1" <?php echo!empty($p['is_default'])?'checked':'';?>> <span><?php echo e(t('admin.ai.is_default'));?></span></label>
        <label class="tp-check"><input type="checkbox" name="verify_tls" value="1" <?php echo $p===null||(int)$p['verify_tls']?'checked':'';?>> <span><?php echo e(t('admin.ai.verify_tls'));?></span></label>
      </div>
      <div class="tp-row tp-mt-3"><button class="tp-btn tp-btn--primary" type="submit"><?php echo icon('check',16);?> <?php echo e(t('common.save'));?></button><button class="tp-btn tp-btn--ghost" type="button" data-ai-test-provider="/admin/ai/providers/test" data-ai-from-form="#ai-provider-form" data-ai-target="#ai-provider-test"><?php echo icon('zap',16);?> <?php echo e(t('admin.ai.test'));?></button></div>
    </div>
    <div class="tp-card" id="ai-provider-test">
      <h3><?php echo icon('activity',18);?> <?php echo e(t('admin.ai.test'));?></h3>
      <div class="tp-ai-result" data-ai-result><p class="tp-text-3"><?php echo e(t('admin.ai.models_detected'));?> : —</p></div>
    </div>
  </div>
</form>
<?php include __DIR__.'/_js.php';