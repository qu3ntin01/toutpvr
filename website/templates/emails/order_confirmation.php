<h1><?php echo e(t('email.order_confirmation.title'));?></h1>
<p><?php echo e(t('email.order_confirmation.body',['plan'=>\App\Core\I18n::pick($plan['name'],$lang)]));?></p>
<div class="key"><?php echo e($license['license_key']);?></div>
<p><?php echo e(t('email.order_confirmation.activate'));?></p>
<p style="font-family:monospace;background:rgba(255,255,255,.06);padding:10px;border-radius:8px">toutpvr license activate <?php echo e($license['license_key']);?></p>
<table>
  <tr><td><?php echo e(t('license.plan'));?></td><td class="r"><?php echo e(\App\Core\I18n::pick($plan['name'],$lang));?></td></tr>
  <tr><td><?php echo e(t('license.expires'));?></td><td class="r"><?php echo e(date_fmt($license['expires_at']));?></td></tr>
  <tr><td><?php echo e(t('pricing.max_cameras'));?></td><td class="r"><?php echo $license['max_cameras']===null?e(t('pricing.unlimited')):(int)$license['max_cameras'];?></td></tr>
  <?php if(!empty($order)){?><tr><td><?php echo e(t('invoice.total'));?></td><td class="r"><?php echo money((int)$order['total'],$order['currency']);?></td></tr><?php }?>
</table>
<p><a class="btn" href="<?php echo e(url('/account/licenses/'.$license['id'],$lang));?>"><?php echo e(t('account.license'));?></a></p>
