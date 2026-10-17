<div class="tp-page-head"><h1><?php echo e(t('account.api_keys'));?></h1><p class="tp-text-2"><?php echo e(t('account.api_keys_lead'));?></p></div>
<?php if($newKey){?><div class="tp-card tp-card--highlight"><p><strong><?php echo e(t('account.api_key_new'));?></strong> <?php echo e(t('account.api_key_once'));?></p><div class="tp-key" data-copy="<?php echo e($newKey);?>"><code><?php echo e($newKey);?></code><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-copy-btn><?php echo icon('copy',14);?> <?php echo e(t('common.copy'));?></button></div></div><?php }?>
<div class="tp-grid tp-grid--2">
  <div class="tp-card tp-card--table">
    <table class="tp-table"><thead><tr><th><?php echo e(t('field.name'));?></th><th><?php echo e(t('account.api_key_prefix'));?></th><th><?php echo e(t('account.api_key_last_used'));?></th><th></th></tr></thead><tbody>
    <?php if(!$keys){?><tr><td colspan="4" class="tp-center tp-text-3"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    <?php foreach($keys as $k){?><tr><td><?php echo e($k['name']);?></td><td><code class="tp-mono"><?php echo e($k['key_prefix']);?>…</code></td><td><?php echo e(date_fmt($k['last_used_at'],'d/m/Y H:i'));?></td><td><form method="post" action="<?php echo e(url('/account/api-keys/'.$k['id'].'/delete'));?>" data-confirm="<?php echo e(t('common.confirm_delete'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('trash',14);?></button></form></td></tr><?php }?>
    </tbody></table>
  </div>
  <form class="tp-card tp-form" method="post" action="<?php echo e(url('/account/api-keys'));?>"><?php echo csrf_field();?>
    <h3><?php echo e(t('account.api_key_create'));?></h3>
    <div class="tp-field"><label for="name"><?php echo e(t('field.name'));?></label><input class="tp-input" id="name" name="name" required placeholder="CI / monitoring"></div>
    <button class="tp-btn tp-btn--primary" type="submit"><?php echo icon('plus',16);?> <?php echo e(t('common.create'));?></button>
    <p class="tp-text-3"><?php echo e(t('account.api_key_usage'));?> <code>X-API-Key</code></p>
  </form>
</div>
