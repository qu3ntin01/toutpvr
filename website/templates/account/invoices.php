<div class="tp-page-head"><h1><?php echo e(t('account.invoices'));?></h1><p class="tp-text-2"><?php echo e(t('account.invoices_lead'));?></p></div>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('invoice.number'));?></th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('invoice.total'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
  <?php if(!$invoices){?><tr><td colspan="5" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  <?php foreach($invoices as $i){?>
  <tr><td><strong><?php echo e($i['number']);?></strong></td><td><?php echo e(date_fmt($i['issued_at']));?></td><td><?php echo money((int)$i['total'],$i['currency']);?></td><td><?php partial('status_badge',['status'=>$i['status']]);?></td><td class="tp-row"><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/account/invoices/'.$i['id'].'/pdf'));?>" target="_blank"><?php echo icon('file-text',14);?> PDF</a><a class="tp-btn tp-btn--ghost tp-btn--sm" href="<?php echo e(url('/account/invoices/'.$i['id'].'/html'));?>" target="_blank"><?php echo icon('external',14);?> HTML</a></td></tr>
  <?php }?>
  </tbody></table>
</div>
<div class="tp-card tp-card--table tp-mt-3">
  <h3><?php echo e(t('account.orders'));?></h3>
  <table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('invoice.total'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
  <?php if(!$orders){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  <?php foreach($orders as $o){?>
  <tr><td><?php echo(int)$o['id'];?></td><td><?php echo e(date_fmt($o['created_at']));?></td><td><?php echo e(t('kind.'.$o['kind']));?></td><td><?php echo money((int)$o['total'],$o['currency']);?></td><td><?php partial('status_badge',['status'=>$o['status']]);?></td><td><?php if($o['status']==='pending'){?><a class="tp-btn tp-btn--primary tp-btn--sm" href="<?php echo e(url('/checkout/pay/'.$o['id']));?>"><?php echo e(t('checkout.pay'));?></a><?php }?></td></tr>
  <?php }?>
  </tbody></table>
</div>
