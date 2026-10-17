<?php $p=$pagination;if(($p['pages']??1)<=1){return;}$base=$base??preg_replace('/([?&])page=\d+/','$1',$_SERVER['REQUEST_URI']??'');$sep=str_contains($base,'?')?str_ends_with($base,'?')||str_ends_with($base,'&')?'':'&':'?';?>
<nav class="tp-pagination" aria-label="Pagination">
  <?php if($p['page']>1){?><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e($base.$sep.'page='.($p['page']-1));?>"><?php echo icon('chevron-left',16);?></a><?php }?>
  <span class="tp-text-2"><?php echo(int)$p['page'];?> / <?php echo(int)$p['pages'];?></span>
  <?php if($p['page']<$p['pages']){?><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e($base.$sep.'page='.($p['page']+1));?>"><?php echo icon('chevron-right',16);?></a><?php }?>
</nav>
