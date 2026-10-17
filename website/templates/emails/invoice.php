<h1><?php echo e(t('email.invoice.title',['number'=>$invoice['number']]));?></h1>
<p><?php echo e(t('email.invoice.body'));?></p>
<table>
  <?php foreach($invoice['lines']as $line){?><tr><td><?php echo e($line['label']);?></td><td class="r"><?php echo money((int)$line['total'],$invoice['currency']);?></td></tr><?php }?>
  <tr><td><?php echo e(t('invoice.vat'));?> <?php echo e((string)$invoice['tax_rate']);?> %</td><td class="r"><?php echo money((int)$invoice['tax'],$invoice['currency']);?></td></tr>
  <tr><td><strong><?php echo e(t('invoice.total'));?></strong></td><td class="r"><strong><?php echo money((int)$invoice['total'],$invoice['currency']);?></strong></td></tr>
</table>
<p><a class="btn" href="<?php echo e(url('/account/invoices/'.$invoice['id'].'/pdf',$lang));?>"><?php echo e(t('email.invoice.download'));?></a></p>
