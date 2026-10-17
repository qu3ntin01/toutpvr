<div class="tp-grid tp-grid--3 tp-kpis">
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('admin.invoices_year'));?></span><span class="tp-kpi__value"><?php echo(int)$totals['n'];?></span></div>
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('admin.revenue_year'));?></span><span class="tp-kpi__value"><?php echo money((int)$totals['total']);?></span></div>
  <div class="tp-kpi"><span class="tp-kpi__label"><?php echo e(t('admin.vat_collected'));?></span><span class="tp-kpi__value"><?php echo money((int)$totals['tax']);?></span></div>
</div>
<form class="tp-toolbar tp-mt-3" method="get"><input class="tp-input" type="search" name="q" value="<?php echo e($q);?>" placeholder="<?php echo e(t('common.search'));?>"><button class="tp-btn tp-btn--ghost" type="submit"><?php echo icon('search',16);?></button></form>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('invoice.number'));?></th><th><?php echo e(t('common.date'));?></th><th><?php echo e(t('admin.customer'));?></th><th>HT</th><th><?php echo e(t('invoice.vat'));?></th><th>TTC</th><th></th></tr></thead><tbody>
  <?php foreach($res['items']as $i){?><tr><td><strong><?php echo e($i['number']);?></strong></td><td><?php echo e(date_fmt($i['issued_at']));?></td><td><?php echo e($i['user_email']);?></td><td><?php echo money((int)$i['subtotal']-(int)$i['discount'],$i['currency']);?></td><td><?php echo money((int)$i['tax'],$i['currency']);?></td><td><?php echo money((int)$i['total'],$i['currency']);?></td><td><a class="tp-btn tp-btn--ghost tp-btn--sm" href="/admin/invoices/<?php echo(int)$i['id'];?>/pdf" target="_blank"><?php echo icon('file-text',14);?> PDF</a> <a class="tp-btn tp-btn--ghost tp-btn--sm" href="/admin/orders/<?php echo(int)$i['order_id'];?>"><?php echo e(t('admin.order'));?></a></td></tr><?php }?>
  <?php if(!$res['items']){?><tr><td colspan="7" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
  </tbody></table>
  <?php partial('admin_pagination',['res'=>$res]);?>
</div>
