<?php $s=$status;$map=['active'=>'success','expired'=>'warning','suspended'=>'warning','revoked'=>'danger','paid'=>'success','pending'=>'muted','failed'=>'danger','cancelled'=>'muted','refunded'=>'warning','awaiting_transfer'=>'info'];?>
<span class="tp-badge tp-badge--<?php echo $map[$s]??'muted';?>"><?php echo e(t('status_label.'.$s));?></span>
