<?php $current=lang();$path=preg_replace('#^/[a-z]{2}(/|$)#','/',parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/');$isAdmin=str_starts_with($path,'/admin');$qs=$_SERVER['QUERY_STRING']??'';?>
<div class="tp-dropdown" data-dropdown>
  <button class="tp-btn tp-btn--ghost tp-btn--icon" type="button" aria-haspopup="menu" aria-expanded="false" data-dropdown-toggle aria-label="<?php echo e(t('common.language'));?>"><?php echo icon('globe',18);?><span class="tp-lang-code"><?php echo strtoupper($current);?></span></button>
  <div class="tp-dropdown__menu tp-dropdown__menu--langs" role="menu">
    <?php foreach(\App\Core\I18n::active()as $l){?>
      <?php if($isAdmin){?>
        <form method="post" action="/admin/language"><?php echo csrf_field();?><input type="hidden" name="lang" value="<?php echo $l;?>"><button type="submit" role="menuitem" class="tp-dropdown__item<?php echo $l===$current?' is-active':'';?>" lang="<?php echo $l;?>"><span class="tp-lang-code"><?php echo strtoupper($l);?></span> <?php echo e(\App\Core\I18n::name($l));?></button></form>
      <?php }else{?>
        <a role="menuitem" class="tp-dropdown__item<?php echo $l===$current?' is-active':'';?>" href="<?php echo e(url($path,$l)).($qs?'?'.e($qs):'');?>" hreflang="<?php echo $l;?>" lang="<?php echo $l;?>"><span class="tp-lang-code"><?php echo strtoupper($l);?></span> <?php echo e(\App\Core\I18n::name($l));?></a>
      <?php }?>
    <?php }?>
  </div>
</div>
