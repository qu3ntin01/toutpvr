<?php $isPost=$kind==='post';$it=$item??['id'=>0,'slug'=>'','title'=>[],'blocks'=>[],'is_published'=>1,'meta_title'=>[],'meta_description'=>[],'excerpt'=>[],'cover'=>'','tags'=>'','published_at'=>null,'is_system'=>0];?>
<a class="tp-back" href="/admin/<?php echo $isPost?'posts':'pages';?>"><?php echo icon('chevron-left',16);?> <?php echo e($isPost?t('admin.posts'):t('admin.pages'));?></a>
<form class="tp-form" method="post" action="/admin/<?php echo $isPost?'posts':'pages';?>/save" data-block-editor-form><?php echo csrf_field();?><input type="hidden" name="id" value="<?php echo(int)$it['id'];?>"><input type="hidden" name="blocks_json" data-blocks-json value="<?php echo e(json_encode($it['blocks']?:[],JSON_UNESCAPED_UNICODE));?>">
  <div class="tp-grid tp-grid--3">
    <div class="tp-span-2">
      <div class="tp-card">
        <?php partial('multilang_field',['name'=>'title','label'=>t('common.title'),'values'=>$it['title'],'type'=>'input']);?>
        <?php if($isPost){partial('multilang_field',['name'=>'excerpt','label'=>t('admin.excerpt'),'values'=>$it['excerpt']??[],'type'=>'textarea','rows'=>2]);}?>
      </div>
      <div class="tp-card tp-mt-3">
        <div class="tp-card__head"><h3><?php echo icon('layers',18);?> <?php echo e(t('admin.blocks'));?></h3>
          <div class="tp-dropdown" data-dropdown><button class="tp-btn tp-btn--primary tp-btn--sm" type="button" data-dropdown-toggle><?php echo icon('plus',14);?> <?php echo e(t('cms.add_block'));?></button>
            <div class="tp-dropdown__menu" role="menu"><?php foreach($blockTypes as $bt){?><button class="tp-dropdown__item" type="button" data-add-block="<?php echo $bt;?>"><?php echo e(t('cms.block_'.$bt));?></button><?php }?></div></div>
        </div>
        <div class="tp-blocks" data-block-editor data-langs="<?php echo e(implode(',',\App\Core\I18n::active()));?>" data-labels='<?php echo e(json_encode(array_combine($blockTypes,array_map(fn($b)=>t('cms.block_'.$b),$blockTypes))+['text'=>t('cms.f_text'),'html'=>t('cms.f_html'),'items'=>t('cms.f_items'),'author'=>t('cms.f_author'),'src'=>t('cms.f_src'),'alt'=>t('cms.f_alt'),'caption'=>t('cms.f_caption'),'label'=>t('cms.f_label'),'href'=>t('cms.f_href'),'left'=>t('cms.f_left'),'right'=>t('cms.f_right'),'question'=>t('cms.f_question'),'answer'=>t('cms.f_answer'),'code'=>t('cms.f_code'),'level'=>t('cms.f_level'),'empty'=>t('cms.empty')],JSON_UNESCAPED_UNICODE));?>'></div>
      </div>
    </div>
    <div>
      <div class="tp-card tp-form">
        <div class="tp-field"><label>Slug</label><input class="tp-input" name="slug" required pattern="[a-z0-9][a-z0-9-]*" value="<?php echo e($it['slug']);?>" <?php echo!empty($it['is_system'])?'readonly':'';?>></div>
        <label class="tp-check"><input type="checkbox" name="is_published" value="1" <?php echo(int)$it['is_published']?'checked':'';?>> <span><?php echo e(t('admin.published'));?></span></label>
        <?php if($isPost){?>
          <div class="tp-field"><label><?php echo e(t('admin.published_at'));?></label><input class="tp-input" type="datetime-local" name="published_at" value="<?php echo $it['published_at']?e(date('Y-m-d\TH:i',strtotime($it['published_at']))):'';?>"></div>
          <div class="tp-field"><label><?php echo e(t('admin.cover_url'));?></label><input class="tp-input" name="cover" value="<?php echo e($it['cover']);?>" placeholder="/media/2026/09/cover.webp"></div>
          <div class="tp-field"><label>Tags</label><input class="tp-input" name="tags" value="<?php echo e($it['tags']);?>" placeholder="release, ai"></div>
        <?php }else{?>
          <?php partial('multilang_field',['name'=>'meta_title','label'=>'SEO — title','values'=>$it['meta_title']??[],'type'=>'input']);?>
          <?php partial('multilang_field',['name'=>'meta_description','label'=>'SEO — description','values'=>$it['meta_description']??[],'type'=>'textarea','rows'=>2]);?>
        <?php }?>
        <button class="tp-btn tp-btn--primary tp-btn--block" type="submit"><?php echo icon('check',16);?> <?php echo e(t('common.save'));?></button>
        <p class="tp-text-3"><?php echo e(t('cms.inline_help'));?></p>
      </div>
    </div>
  </div>
</form>
