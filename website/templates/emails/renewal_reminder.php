<h1><?php echo e(t('email.renewal_reminder.title',['days'=>$days]));?></h1>
<p><?php echo e(t('email.renewal_reminder.body',['date'=>date_fmt($license['expires_at'])]));?></p>
<div class="key"><?php echo e($license['license_key']);?></div>
<p><a class="btn" href="<?php echo e(url('/account/licenses/'.$license['id'],$lang));?>"><?php echo e(t('license.renew'));?></a></p>
<p class="muted"><?php echo e(t('email.renewal_reminder.hint'));?></p>
