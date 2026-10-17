<h1><?php echo e(t('email.password_reset.title'));?></h1>
<p><?php echo e(t('email.password_reset.body'));?></p>
<p><a class="btn" href="<?php echo e($reset_url);?>"><?php echo e(t('auth.reset_password'));?></a></p>
<p class="muted"><?php echo e(t('email.password_reset.expires'));?><br><?php echo e($reset_url);?></p>
