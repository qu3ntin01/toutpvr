<?php $p=$profile??null;$v=fn(string $k,mixed $d='')=>old($k,$p[$k]??$d);$errs=$errors??[];$oldAll=\App\Core\Session::get('_old',[]);$classes=(array)($oldAll['classes']??$p['classes']??['person','vehicle','animal']);$opts=is_array($p['options']??null)?$p['options']:['temperature'=>0.1,'num_ctx'=>4096];$extra=array_diff_key($opts,['temperature'=>1,'num_ctx'=>1]);$trigger=$oldAll['trigger']??$p['trigger']??'motion';?>
<a class="tp-back" href="/admin/ai?tab=profiles"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.ai.tab_profiles'));?></a>
<form class="tp-form" method="post" action="/admin/ai/profiles/save"><?php echo csrf_field();?><input type="hidden" name="id" value="<?php echo(int)($p['id']??0);?>">
  <div class="tp-grid tp-grid--3">
    <div class="tp-span-2">
      <div class="tp-card">
        <h3><?php echo icon('brain',18);?> <?php echo e($p?t('admin.ai.edit_profile'):t('admin.ai.add_profile'));?></h3>
        <div class="tp-grid tp-grid--2 tp-grid--tight">
          <div class="tp-field"><label><?php echo e(t('admin.ai.name'));?></label><input class="tp-input" name="name" required maxlength="191" value="<?php echo $v('name');?>" placeholder="Intrusion — Qwen2.5-VL 7B"><?php if(isset($errs['name'])){?><span class="tp-field__error"><?php echo e($errs['name']);?></span><?php }?></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.identifier'));?></label><input class="tp-input tp-mono" name="slug" pattern="[a-z0-9._-]*" maxlength="64" value="<?php echo $v('slug');?>" placeholder="intrusion-qwen"><span class="tp-field__hint"><?php echo e(t('admin.ai.identifier_hint'));?></span><?php if(isset($errs['slug'])||isset($errs['id'])){?><span class="tp-field__error"><?php echo e($errs['slug']??$errs['id']);?></span><?php }?></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.model'));?></label><input class="tp-input tp-mono" name="model" required value="<?php echo $v('model');?>" placeholder="qwen2.5vl:7b"><span class="tp-field__hint"><?php echo e(t('admin.ai.model_hint'));?></span><?php if(isset($errs['model'])){?><span class="tp-field__error"><?php echo e($errs['model']);?></span><?php }?></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.trigger'));?></label><select class="tp-select" name="trigger"><?php foreach(\App\Models\AiProfile::TRIGGERS as $tr){?><option value="<?php echo $tr;?>" <?php echo $trigger===$tr?'selected':'';?>><?php echo e(t('admin.ai.trigger_'.$tr));?></option><?php }?></select></div>
        </div>
        <div class="tp-field"><label><?php echo e(t('admin.ai.classes'));?></label><div class="tp-row tp-row--wrap"><?php foreach(\App\Models\AiProfile::CLASSES as $c){?><label class="tp-check"><input type="checkbox" name="classes[]" value="<?php echo $c;?>" <?php echo in_array($c,$classes,true)?'checked':'';?>> <span><?php echo e(t('admin.ai.class_'.$c));?></span></label><?php }?></div><?php if(isset($errs['classes'])){?><span class="tp-field__error"><?php echo e($errs['classes']);?></span><?php }?></div>
        <div class="tp-field"><div class="tp-row tp-row--between"><label><?php echo e(t('admin.ai.prompt'));?></label><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-ai-reset-prompt="<?php echo e($defaultPrompt);?>"><?php echo icon('refresh',14);?> <?php echo e(t('admin.ai.reset_prompt'));?></button></div><textarea class="tp-input tp-mono" name="prompt" rows="7" required><?php echo $v('prompt',$p?'':$defaultPrompt);?></textarea><span class="tp-field__hint"><?php echo e(t('admin.ai.prompt_hint'));?></span><?php if(isset($errs['prompt'])){?><span class="tp-field__error"><?php echo e($errs['prompt']);?></span><?php }?></div>
        <?php partial('multilang_field',['name'=>'description','label'=>t('admin.ai.description_multilang'),'values'=>$oldAll['description']??$p['description']??[],'type'=>'textarea','rows'=>2]);?>
      </div>
    </div>
    <div>
      <div class="tp-card tp-form">
        <h3><?php echo icon('sliders',18);?> <?php echo e(t('admin.ai.thresholds'));?></h3>
        <div class="tp-grid tp-grid--2 tp-grid--tight">
          <div class="tp-field"><label><?php echo e(t('admin.ai.min_confidence'));?></label><input class="tp-input" type="number" step="0.01" min="0" max="1" name="min_confidence" value="<?php echo $v('min_confidence','0.6');?>"><?php if(isset($errs['min_confidence'])){?><span class="tp-field__error"><?php echo e($errs['min_confidence']);?></span><?php }?></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.min_interval'));?></label><input class="tp-input" type="number" min="0" max="86400" name="min_interval_s" value="<?php echo $v('min_interval_s',5);?>"></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.max_concurrency'));?></label><input class="tp-input" type="number" min="1" max="16" name="max_concurrency" value="<?php echo $v('max_concurrency',1);?>"></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.timeout'));?></label><input class="tp-input" type="number" min="1" max="600" name="timeout_s" value="<?php echo $v('timeout_s',30);?>"></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.temperature'));?></label><input class="tp-input" type="number" step="0.01" min="0" max="2" name="temperature" value="<?php echo e((string)($oldAll['temperature']??$opts['temperature']??'0.1'));?>"></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.num_ctx'));?></label><input class="tp-input" type="number" min="256" step="256" name="num_ctx" value="<?php echo e((string)($oldAll['num_ctx']??$opts['num_ctx']??'4096'));?>"></div>
        </div>
        <div class="tp-field"><label><?php echo e(t('admin.ai.extra_options'));?></label><input class="tp-input tp-mono" name="options_json" value="<?php echo e((string)($oldAll['options_json']??($extra?json_encode($extra,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):'')));?>" placeholder='{"top_p":0.9}'><?php if(isset($errs['options'])){?><span class="tp-field__error"><?php echo e($errs['options']);?></span><?php }?></div>
        <div class="tp-grid tp-grid--2 tp-grid--tight">
          <div class="tp-field"><label><?php echo e(t('admin.ai.min_server_version'));?></label><input class="tp-input tp-mono" name="min_server_version" value="<?php echo $v('min_server_version','1.0.0');?>"><?php if(isset($errs['min_server_version'])){?><span class="tp-field__error"><?php echo e($errs['min_server_version']);?></span><?php }?></div>
          <div class="tp-field"><label><?php echo e(t('admin.ai.order'));?></label><input class="tp-input" type="number" min="0" name="sort_order" value="<?php echo $v('sort_order',$p?0:\App\Models\AiProfile::nextOrder());?>"></div>
        </div>
        <label class="tp-check"><input type="checkbox" name="upgrade_to_intrusion" value="1" <?php echo $p===null||(int)$p['upgrade_to_intrusion']?'checked':'';?>> <span><?php echo e(t('admin.ai.upgrade_to_intrusion'));?></span></label>
        <label class="tp-check"><input type="checkbox" name="enabled" value="1" <?php echo $p===null||(int)$p['enabled']?'checked':'';?>> <span><?php echo e(t('admin.ai.enabled'));?></span></label>
        <button class="tp-btn tp-btn--primary tp-btn--block tp-mt-2" type="submit"><?php echo icon('check',16);?> <?php echo e(t('common.save'));?></button>
        <?php if($p){?><p class="tp-text-3 tp-center tp-mt-2"><a href="/admin/ai?tab=catalog#ai-test"><?php echo e(t('admin.ai.test_profile'));?> →</a></p><?php }?>
      </div>
    </div>
  </div>
</form>
<?php include __DIR__.'/_js.php';