<?php include __DIR__.'/_tabs.php';?>
<p class="tp-text-2 tp-mt-3"><?php echo e(t('admin.ai.models_lead'));?></p>
<div class="tp-grid tp-grid--3">
  <div class="tp-card tp-card--table tp-span-2">
    <table class="tp-table"><thead><tr><th><?php echo e(t('admin.ai.model'));?></th><th><?php echo e(t('admin.ai.vram'));?></th><th><?php echo e(t('admin.ai.speed'));?></th><th><?php echo e(t('admin.ai.quality'));?></th><th><?php echo e(t('admin.ai.notes'));?></th><th></th></tr></thead><tbody>
    <?php foreach($models as $m){$notes=is_array($m['notes'])?$m['notes']:(array)$m['notes'];?>
      <tr><td><code><?php echo e($m['name']);?></code></td><td><?php echo e((string)$m['vram_gb']);?> Go</td><td><span class="tp-badge tp-badge--<?php echo['fast'=>'success','medium'=>'info','slow'=>'warning'][$m['speed']]??'muted';?>"><?php echo e(t('admin.ai.speed_'.$m['speed']));?></span></td><td><span class="tp-badge tp-badge--<?php echo['high'=>'success','medium'=>'info','low'=>'muted'][$m['quality']]??'muted';?>"><?php echo e(t('admin.ai.quality_'.$m['quality']));?></span></td><td class="tp-text-3"><?php echo e(\App\Core\I18n::pick($notes));?></td>
      <td class="tp-row tp-nowrap"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-fill-form='<?php echo e(json_encode(['original_name'=>$m['name'],'name'=>$m['name'],'vram_gb'=>$m['vram_gb'],'speed'=>$m['speed'],'quality'=>$m['quality'],'notes[fr]'=>$notes['fr']??'','notes[en]'=>$notes['en']??''],JSON_UNESCAPED_UNICODE));?>' data-fill-target="#ai-model-form"><?php echo icon('edit',14);?></button><form method="post" action="/admin/ai/models/delete" data-confirm="<?php echo e(t('admin.ai.confirm_delete_model'));?>"><?php echo csrf_field();?><input type="hidden" name="name" value="<?php echo e($m['name']);?>"><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('trash',14);?></button></form></td></tr>
    <?php }?>
    <?php if(!$models){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('admin.ai.no_recommended'));?></td></tr><?php }?>
    </tbody></table>
  </div>
  <form class="tp-card tp-form" id="ai-model-form" method="post" action="/admin/ai/models/save"><?php echo csrf_field();?><input type="hidden" name="original_name" value="">
    <h3><?php echo icon('cpu',18);?> <?php echo e(t('admin.ai.add_model'));?></h3>
    <div class="tp-field"><label><?php echo e(t('admin.ai.model'));?></label><input class="tp-input tp-mono" name="name" required pattern="[A-Za-z0-9._/:-]+" placeholder="llava:7b"><?php if(isset($errors['name'])){?><span class="tp-field__error"><?php echo e($errors['name']);?></span><?php }?></div>
    <div class="tp-grid tp-grid--3 tp-grid--tight">
      <div class="tp-field"><label><?php echo e(t('admin.ai.vram'));?></label><input class="tp-input" type="number" step="0.5" min="0" name="vram_gb" value="6"></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.speed'));?></label><select class="tp-select" name="speed"><?php foreach(\App\Models\AiCatalog::SPEEDS as $s){?><option value="<?php echo $s;?>" <?php echo $s==='medium'?'selected':'';?>><?php echo e(t('admin.ai.speed_'.$s));?></option><?php }?></select></div>
      <div class="tp-field"><label><?php echo e(t('admin.ai.quality'));?></label><select class="tp-select" name="quality"><?php foreach(\App\Models\AiCatalog::QUALITIES as $q){?><option value="<?php echo $q;?>" <?php echo $q==='medium'?'selected':'';?>><?php echo e(t('admin.ai.quality_'.$q));?></option><?php }?></select></div>
    </div>
    <div class="tp-field"><label><?php echo e(t('admin.ai.notes'));?> (FR)</label><textarea class="tp-input" name="notes[fr]" rows="2" lang="fr"></textarea></div>
    <div class="tp-field"><label><?php echo e(t('admin.ai.notes'));?> (EN)</label><textarea class="tp-input" name="notes[en]" rows="2" lang="en"></textarea></div>
    <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button><button class="tp-btn tp-btn--ghost" type="reset"><?php echo e(t('common.new'));?></button></div>
  </form>
</div>
