<div class="tp-grid tp-grid--3">
  <div class="tp-card tp-card--table tp-span-2">
    <table class="tp-table"><thead><tr><th><?php echo e(t('field.name'));?></th><th><?php echo e(t('field.email'));?></th><th><?php echo e(t('admin.role'));?></th><th>2FA</th><th><?php echo e(t('account.last_login'));?></th><th><?php echo e(t('common.status'));?></th><th></th></tr></thead><tbody>
    <?php foreach($admins as $a){?><tr><td><?php echo e($a['name']);?></td><td><?php echo e($a['email']);?></td><td><span class="tp-badge tp-badge--<?php echo $a['role']==='superadmin'?'danger':($a['role']==='admin'?'warning':'info');?>"><?php echo e($a['role']);?></span></td><td><?php echo $a['two_factor']!=='none'?icon('shield-check',16,'tp-yes'):'<span class="tp-text-3">—</span>';?></td><td><?php echo e(date_fmt($a['last_login_at'],'d/m/Y H:i'));?></td><td><span class="tp-badge tp-badge--<?php echo(int)$a['is_active']?'success':'muted';?>"><?php echo(int)$a['is_active']?e(t('common.active')):e(t('common.disabled'));?></span></td><td class="tp-row"><button class="tp-btn tp-btn--ghost tp-btn--sm" type="button" data-fill-form='<?php echo e(json_encode(['id'=>$a['id'],'name'=>$a['name'],'email'=>$a['email'],'role'=>$a['role'],'is_active'=>(bool)$a['is_active']]));?>' data-fill-target="#admin-form"><?php echo icon('edit',14);?></button><?php if((int)$a['id']!==(int)$auth['id']){?><form method="post" action="/admin/admins/<?php echo(int)$a['id'];?>/delete" data-confirm="<?php echo e(t('common.confirm_delete'));?>"><?php echo csrf_field();?><button class="tp-btn tp-btn--danger tp-btn--sm" type="submit"><?php echo icon('trash',14);?></button></form><?php }?></td></tr><?php }?>
    </tbody></table>
    <div class="tp-roles">
      <h4><?php echo e(t('admin.roles_matrix'));?></h4>
      <table class="tp-table tp-table--compact"><thead><tr><th></th><th>superadmin</th><th>admin</th><th>support</th><th>editor</th></tr></thead><tbody>
      <?php foreach(['dashboard'=>[1,1,1,1],'customers'=>[1,1,1,0],'licenses'=>[1,1,1,0],'orders'=>[1,1,1,0],'plans'=>[1,1,0,0],'updates'=>[1,1,0,0],'pages'=>[1,1,0,1],'media'=>[1,1,0,1],'settings'=>[1,1,0,0],'admins'=>[1,0,0,0],'audit'=>[1,1,1,0]]as $perm=>$row){?><tr><th><?php echo e(t('admin.'.$perm));?></th><?php foreach($row as $ok){?><td><?php echo $ok?'<span class="tp-yes">'.icon('check',14).'</span>':'<span class="tp-no">'.icon('minus',14).'</span>';?></td><?php }?></tr><?php }?>
      </tbody></table>
    </div>
  </div>
  <form class="tp-card tp-form" id="admin-form" method="post" action="/admin/admins/save"><?php echo csrf_field();?><input type="hidden" name="id" value="0">
    <h3><?php echo e(t('admin.admin_edit'));?></h3>
    <div class="tp-field"><label><?php echo e(t('field.name'));?></label><input class="tp-input" name="name" required></div>
    <div class="tp-field"><label><?php echo e(t('field.email'));?></label><input class="tp-input" type="email" name="email" required></div>
    <div class="tp-field"><label><?php echo e(t('admin.role'));?></label><select class="tp-select" name="role"><?php foreach($roles as $r){if($r==='superadmin'&&$auth['role']!=='superadmin'){continue;}?><option value="<?php echo $r;?>"><?php echo $r;?></option><?php }?></select></div>
    <div class="tp-field"><label><?php echo e(t('field.password'));?> <span class="tp-text-3">(<?php echo e(t('admin.blank_keep'));?>)</span></label><input class="tp-input" type="password" name="password" minlength="12" autocomplete="new-password"></div>
    <label class="tp-check"><input type="checkbox" name="is_active" value="1" checked> <span><?php echo e(t('common.active'));?></span></label>
    <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit"><?php echo e(t('common.save'));?></button><button class="tp-btn tp-btn--ghost" type="reset"><?php echo e(t('common.new'));?></button></div>
    <p class="tp-text-3"><?php echo e(t('admin.admin_2fa_hint'));?></p>
  </form>
</div>
