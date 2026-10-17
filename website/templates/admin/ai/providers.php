<?php include __DIR__.'/_tabs.php';?>
<div class="tp-toolbar tp-mt-3"><p class="tp-text-2"><?php echo e(t('admin.ai.providers_lead'));?></p><span class="tp-toolbar__spacer"></span><a class="tp-btn tp-btn--primary" href="/admin/ai/providers/create"><?php echo icon('plus',16);?> <?php echo e(t('admin.ai.add_provider'));?></a></div>
<div class="tp-card tp-card--table">
  <table class="tp-table"><thead><tr><th><?php echo e(t('admin.ai.name'));?></th><th><?php echo e(t('admin.ai.base_url'));?></th><th><?php echo e(t('admin.ai.model'));?></th><th><?php echo e(t('admin.ai.timeout'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
  <?php foreach($providers as $p){?>
    <tr class="<?php echo(int)$p['enabled']?'':'is-muted';?>" data-ai-provider-row="<?php echo(int)$p['id'];?>">
      <td><strong><?php echo e($p['name']);?></strong> <?php if((int)$p['is_default']){?><span class="tp-badge tp-badge--aurora"><?php echo e(t('admin.ai.default'));?></span><?php }?><div class="tp-text-3 tp-mono"><?php echo e($p['slug']);?></div></td>
      <td class="tp-mono tp-text-2"><?php echo e($p['base_url']);echo $p['api_key_enc']?' '.icon('key',12):'';echo!(int)$p['verify_tls']?' <span class="tp-badge tp-badge--warning">TLS ✗</span>':'';?></td>
      <td><code><?php echo e($p['model']?:'—');?></code></td>
      <td><?php echo(int)$p['timeout_s'];?> s · <?php echo e($p['keep_alive']?:'—');?></td>
      <td><span class="tp-badge tp-badge--<?php echo(int)$p['enabled']?'success':'muted';?>"><?php echo e((int)$p['enabled']?t('admin.ai.enabled'):t('admin.ai.disabled'));?></span> <span class="tp-ai-status" data-ai-status="<?php echo(int)$p['id'];?>"></span></td>
      <td class="tp-row tp-nowrap">
        <button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-ai-test-provider="/admin/ai/providers/<?php echo(int)$p['id'];?>/test" data-ai-target="#ai-test-<?php echo(int)$p['id'];?>" title="<?php echo e(t('admin.ai.test'));?>"><?php echo icon('zap',14);?> <?php echo e(t('admin.ai.test'));?></button>
        <a class="tp-btn tp-btn--ghost tp-btn--sm" href="/admin/ai/providers/<?php echo(int)$p['id'];?>" title="<?php echo e(t('common.edit'));?>"><?php echo icon('edit',14);?></a>
        <?php if(!(int)$p['is_default']){?><form method="post" action="/admin/ai/providers/<?php echo(int)$p['id'];?>/default"><?php echo csrf_field();?><button class="tp-btn tp-btn--ghost tp-btn--sm" type="submit" title="<?php echo e(t('admin.ai.set_default'));?>"><?php echo icon('star',14);?></button></form><?php }?>
        <form method="post" action="/admin/ai/providers/<?php echo(int)$p['id'];?>/delete" data-confirm="<?php echo e(t('admin.ai.confirm_delete_provider'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit" title="<?php echo e(t('common.delete'));?>"><?php echo icon('trash',14);?></button></form>
      </td>
    </tr>
    <tr class="tp-ai-test-row" id="ai-test-<?php echo(int)$p['id'];?>" hidden><td colspan="6"><div class="tp-ai-result" data-ai-result></div></td></tr>
  <?php }?>
  <?php if(!$providers){?><tr><td colspan="6" class="tp-center tp-text-3"><?php echo e(t('admin.ai.no_providers'));?></td></tr><?php }?>
  </tbody></table>
</div>
<p class="tp-text-3 tp-mt-3"><?php echo icon('terminal',14);?> <?php echo e(t('admin.ai.api_hint'));?></p>
<?php include __DIR__.'/_js.php';