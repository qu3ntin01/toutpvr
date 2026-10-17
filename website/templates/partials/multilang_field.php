<?php $values=$values??[];$type=$type??'input';$langs=\App\Core\I18n::active();$id='ml-'.preg_replace('/\W/','',$name);?>
<div class="tp-field tp-multilang" data-multilang>
  <div class="tp-multilang__head"><label><?php echo e($label);?></label><div class="tp-multilang__tabs" role="tablist"><?php foreach($langs as $i=>$l){?><button type="button" role="tab" class="tp-multilang__tab<?php echo $i===0?' is-active':'';echo!empty($values[$l])?' has-value':'';?>" data-ml-lang="<?php echo $l;?>"><?php echo strtoupper($l);?></button><?php }?></div></div>
  <?php foreach($langs as $i=>$l){?>
    <div class="tp-multilang__panel<?php echo $i===0?' is-active':'';?>" data-ml-panel="<?php echo $l;?>" <?php echo \App\Core\I18n::isRtl($l)?'dir="rtl"':'';?>>
      <?php if($type==='textarea'){?><textarea class="tp-input" name="<?php echo e($name);?>[<?php echo $l;?>]" rows="<?php echo $rows??3;?>" lang="<?php echo $l;?>"><?php echo e($values[$l]??'');?></textarea><?php }else{?><input class="tp-input" name="<?php echo e($name);?>[<?php echo $l;?>]" value="<?php echo e($values[$l]??'');?>" lang="<?php echo $l;?>"><?php }?>
    </div>
  <?php }?>
</div>
