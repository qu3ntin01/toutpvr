<h1><?php echo e(t('email.expiry.title'));?></h1>
<p><?php echo e(t('email.expiry.body',['date'=>date_fmt($license['expires_at'])]));?></p>
<div class="key"><?php echo e($license['license_key']);?></div>
<p><?php echo e(t('email.expiry.restricted'));?></p>
<p><a class="btn" href="<?php echo e(url('/account/licenses/'.$license['id'],$lang));?>"><?php echo e(t('license.renew'));?></a></p>
