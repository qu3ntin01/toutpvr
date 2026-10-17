<?php $p=$res;if(($p['pages']??1)<=1){return;}$q=$_GET;?>
<nav class="tp-pagination" aria-label="Pagination">
  <?php if($p['page']>1){$q['page']=$p['page']-1;?><a class="tp-btn tp-btn--ghost tp-btn--sm" href="?<?php echo e(http_build_query($q));?>"><?php echo icon('chevron-left',16);?></a><?php }?>
  <span class="tp-text-2"><?php echo(int)$p['page'];?> / <?php echo(int)$p['pages'];?> · <?php echo(int)$p['total'];?></span>
  <?php if($p['page']<$p['pages']){$q['page']=$p['page']+1;?><a class="tp-btn tp-btn--ghost tp-btn--sm" href="?<?php echo e(http_build_query($q));?>"><?php echo icon('chevron-right',16);?></a><?php }?>
</nav>
