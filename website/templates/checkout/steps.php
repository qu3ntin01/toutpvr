<?php $step=$step??1;?>
<ol class="tp-steps" aria-label="<?php echo e(t('checkout.title'));?>">
  <?php foreach([1=>t('checkout.step_plan'),2=>t('checkout.step_account'),3=>t('checkout.step_billing'),4=>t('checkout.step_payment'),5=>t('checkout.step_done')]as $n=>$label){?>
  <li class="tp-steps__item<?php echo $n<$step?' is-done':($n===$step?' is-active':'');?>"><span class="tp-steps__num"><?php echo $n<$step?icon('check',12):$n;?></span><span class="tp-steps__label"><?php echo e($label);?></span></li>
  <?php }?>
</ol>
