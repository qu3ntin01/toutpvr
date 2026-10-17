<?php $o=$order;$b=$o['billing']??[];?>
<a class="tp-back" href="/admin/orders"><?php echo icon('chevron-left',16);?> <?php echo e(t('admin.orders'));?></a>
<div class="tp-grid tp-grid--3">
  <div class="tp-card tp-span-2">
    <div class="tp-card__head"><h3><?php echo e(t('admin.order'));?> #<?php echo(int)$o['id'];?></h3><?php partial('status_badge',['status'=>$o['status']]);?></div>
    <dl class="tp-dl tp-dl--grid">
      <dt><?php echo e(t('admin.customer'));?></dt><dd><a href="/admin/customers/<?php echo(int)$o['user_id'];?>"><?php echo e($o['user_email']);?></a></dd>
      <dt><?php echo e(t('license.plan'));?></dt><dd><?php echo e($o['plan_slug']);?> · <?php echo e(t('period.'.$o['period']));?> · <?php echo e(t('kind.'.$o['kind']));?></dd>
      <dt><?php echo e(t('checkout.payment'));?></dt><dd><?php echo e($o['payment_provider']);?> <span class="tp-mono tp-text-3"><?php echo e($o['payment_ref']);?></span></dd>
      <dt><?php echo e(t('common.date'));?></dt><dd><?php echo e(date_fmt($o['created_at'],'d/m/Y H:i'));echo $o['paid_at']?' · '.e(t('status_label.paid')).' '.e(date_fmt($o['paid_at'],'d/m/Y H:i')):'';?></dd>
      <dt><?php echo e(t('license.key'));?></dt><dd><?php echo $license?'<a class="tp-mono" href="/admin/licenses/'.(int)$license['id'].'">'.e($license['license_key']).'</a>':'—';?></dd>
      <dt><?php echo e(t('invoice.title'));?></dt><dd><?php echo $invoice?'<a href="/admin/invoices/'.(int)$invoice['id'].'/pdf" target="_blank">'.e($invoice['number']).'</a>':'—';?></dd>
    </dl>
    <h4><?php echo e(t('checkout.step_billing'));?></h4>
    <p class="tp-text-2"><?php echo e($b['company']??'');?> <?php echo e($b['name']??'');?><br><?php echo e($b['address_line1']??'');?> <?php echo e($b['address_line2']??'');?><br><?php echo e(($b['postal_code']??'').' '.($b['city']??'').' '.($b['country']??''));?><br><?php echo!empty($b['vat_number'])?e(t('field.vat_number')).' : '.e($b['vat_number']):'';?></p>
  </div>
  <div class="tp-card">
    <h3><?php echo e(t('checkout.summary'));?></h3>
    <ul class="tp-summary__lines">
      <li><span><?php echo e(t('checkout.subtotal'));?></span><span><?php echo money((int)$o['subtotal'],$o['currency']);?></span></li>
      <?php if((int)$o['discount']){?><li><span><?php echo e(t('invoice.discount'));?> <?php echo e($o['coupon_code']);?></span><span>− <?php echo money((int)$o['discount'],$o['currency']);?></span></li><?php }?>
      <li><span><?php echo e(t('invoice.vat'));?> <?php echo e((string)$o['tax_rate']);?> %<?php echo(int)$o['reverse_charge']?' (autoliq.)':'';?></span><span><?php echo money((int)$o['tax'],$o['currency']);?></span></li>
      <li class="tp-summary__total"><span><?php echo e(t('invoice.total'));?></span><span><?php echo money((int)$o['total'],$o['currency']);?></span></li>
    </ul>
    <?php $act=fn(string $a,string $label,string $cls='tp-btn--ghost')=>'<form method="post" action="/admin/orders/'.(int)$o['id'].'/action" data-confirm="'.e(t('common.confirm_action')).'">'.csrf_field().'<input type="hidden" name="action" value="'.$a.'"><button class="tp-btn '.$cls.' tp-btn--sm tp-btn--block" type="submit">'.$label.'</button></form>';?>
    <?php if(in_array($o['status'],['pending','awaiting_transfer','failed'],true)){echo $act('mark_paid',icon('check',14).' '.e(t('admin.mark_paid')),'tp-btn--primary');echo $act('cancel',e(t('common.cancel')));}?>
    <?php if($o['status']==='paid'){echo $act('refund',e(t('admin.refund')),'tp-btn--danger');}?>
  </div>
</div>
