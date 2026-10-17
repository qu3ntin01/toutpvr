<?php use App\Core\Auth;$ring=function(?int $pct,string $level):string{$r=40;$c=2*M_PI*$r;$v=$pct===null?0:max(0,min(100,$pct));return'<div class="tpa-ring tpa-lvl-'.e($level).'" role="img" aria-label="'.($pct===null?'—':(int)$pct.'%').'"><svg viewBox="0 0 100 100" aria-hidden="true"><circle class="track" cx="50" cy="50" r="'.$r.'"/><circle class="bar" cx="50" cy="50" r="'.$r.'" stroke-dasharray="'.round($c,2).'" stroke-dashoffset="'.round($c*(1-$v/100),2).'"/></svg><span class="tpa-ring-value">'.($pct===null?'—':(int)$pct.'%').'</span></div>';};$rateLevel=$paidRate===null?'ok':($paidRate>=80?'ok':($paidRate>=50?'warn':'bad'));$maxSale=max(1,max($sales));$total30=array_sum($sales);$days=array_keys($sales);$peak=max($sales);$byPlanTotal=max(1,array_sum(array_column($byPlan,'n')));$tiles=[];if($watch['expiring']>0){$tiles[]=['warning','clock',t('adm.watch.expiring'),'',$watch['expiring'],'/admin/licenses?status=active','licenses'];}if($watch['payments']>0){$tiles[]=['danger','credit-card',t('adm.watch.payments'),'',$watch['payments'],'/admin/orders','orders'];}if($watch['messages']>0){$tiles[]=['info','inbox',t('adm.watch.messages'),'',$watch['messages'],'/admin/messages','contact'];}if($watch['cron_late']){$tiles[]=['danger','timer',t('adm.watch.cron'),t('adm.watch.cron_last',['when'=>$watch['cron_last']?date_fmt($watch['cron_last'],'d/m H:i').' UTC':t('admin.health_never')]),null,'/admin/health','health'];}if($watch['queue']>0||$watch['queue_failed']>0){$tiles[]=[$watch['queue_failed']>0?'danger':'warning','mail',t('adm.watch.queue'),t('adm.watch.queue_sub',['pending'=>$watch['queue'],'failed'=>$watch['queue_failed']]),$watch['queue']+$watch['queue_failed'],'/admin/health','health'];}if($watch['update']>0){$tiles[]=['warning','package',t('adm.watch.update'),t('adm.watch.update_sub'),null,'/admin/updates','updates'];}if($watch['token_missing']){$tiles[]=['warning','key',t('adm.watch.token'),t('adm.watch.token_sub'),null,'/admin/settings?tab=updates','settings'];}$tiles=array_values(array_filter($tiles,fn($x)=>Auth::can($x[6])));?>
<div class="tpa-dash" data-tpa-dash>

  <section class="tp-card tpa-w" data-w="revenue" aria-labelledby="w-revenue">
    <div class="tpa-w-head"><h3 id="w-revenue"><?php echo icon('trending-up',18);?><span><?php echo e(t('adm.w.revenue'));?></span></h3><span class="tpa-w-aside"><?php echo e(t('admin.last_30_days'));?></span></div>
    <div class="tpa-ring-grid">
      <div class="tpa-ring-tile"><span class="tpa-tile-label"><?php echo e(t('adm.paid_rate'));?></span><?php echo $ring($paidRate,$rateLevel);?><span class="tpa-tile-sub"><strong><?php echo(int)$stats['orders30'];?></strong> <?php echo e(t('admin.orders'));?></span></div>
      <div class="tpa-ring-tile"><span class="tpa-tile-label">MRR</span><span class="tpa-tile-value"><?php echo e(money($stats['mrr']));?></span><span class="tpa-tile-sub"><?php echo e(t('adm.mrr_hint'));?></span></div>
      <div class="tpa-ring-tile"><span class="tpa-tile-label">ARR</span><span class="tpa-tile-value"><?php echo e(money($stats['mrr']*12));?></span><span class="tpa-tile-sub"><?php echo e(t('adm.arr_hint'));?></span></div>
      <div class="tpa-ring-tile"><span class="tpa-tile-label"><?php echo e(t('admin.sales_30'));?></span><span class="tpa-tile-value"><?php echo e(money($stats['sales30']));?></span><span class="tpa-tile-sub"><?php echo(int)$stats['orders30'];?> <?php echo e(t('admin.orders'));?></span></div>
    </div>
  </section>

  <section class="tp-card tpa-w" data-w="licenses" aria-labelledby="w-licenses">
    <div class="tpa-w-head"><h3 id="w-licenses"><?php echo icon('key',18);?><span><?php echo e(t('adm.w.licenses'));?></span></h3><?php if(Auth::can('licenses')){?><a class="tpa-w-aside" href="/admin/licenses"><?php echo e(t('common.see_all'));?></a><?php }?></div>
    <div class="tpa-counters">
      <a class="tpa-counter" href="/admin/licenses?status=active"><span class="tpa-counter-head"><?php echo e(t('admin.active_licenses'));echo icon('key',16);?></span><span class="tpa-counter-value"><?php echo(int)$stats['active'];?></span><span class="tpa-counter-sub"><?php echo(int)$stats['customers'];?> <?php echo e(t('admin.customers'));?></span></a>
      <div class="tpa-counter"><span class="tpa-counter-head"><?php echo e(t('adm.trials'));echo icon('timer',16);?></span><span class="tpa-counter-value"><?php echo(int)$stats['trials'];?></span><span class="tpa-counter-sub"><?php echo e(t('adm.trials_hint'));?></span></div>
      <div class="tpa-counter"><span class="tpa-counter-head"><?php echo e(t('admin.activations'));echo icon('zap',16);?></span><span class="tpa-counter-value"><?php echo(int)$stats['activations'];?></span><span class="tpa-counter-sub"><?php echo e(t('adm.activations_hint'));?></span></div>
      <div class="tpa-counter"><span class="tpa-counter-head"><?php echo e(t('adm.expiring7'));echo icon('clock',16);?></span><span class="tpa-counter-value <?php echo $expiring>0?'tpa-lvl-text-warn':'';?>"><?php echo(int)$expiring;?></span><span class="tpa-counter-sub"><?php echo e(t('adm.expiring7_hint'));?></span></div>
    </div>
  </section>

  <section class="tp-card tpa-w" data-w="sales" aria-labelledby="w-sales">
    <div class="tpa-w-head"><h3 id="w-sales"><?php echo icon('bar-chart',18);?><span><?php echo e(t('admin.sales_chart'));?></span></h3><span class="tpa-w-aside"><?php echo e(t('admin.last_30_days'));?></span></div>
    <div class="tpa-chart-sum"><span><strong><?php echo e(money($total30));?></strong><?php echo e(t('adm.total'));?></span><span><strong><?php echo e(money($peak));?></strong><?php echo e(t('adm.peak'));?></span></div>
    <div class="tpa-chart" role="img" aria-label="<?php echo e(t('admin.sales_chart'));?>">
      <?php foreach($sales as $day=>$v){?><div class="tpa-chart-bar<?php echo $v===0?' is-zero':'';?>" style="--h:<?php echo round($v/$maxSale*100);?>%" title="<?php echo e($day);?> · <?php echo e(money($v));?>"><span></span></div><?php }?>
    </div>
    <div class="tpa-chart-axis" aria-hidden="true"><span><?php echo e(date_fmt($days[0],'d/m'));?></span><span><?php echo e(date_fmt($days[(int)(count($days)/2)],'d/m'));?></span><span><?php echo e(date_fmt(end($days),'d/m'));?></span></div>
  </section>

  <section class="tp-card tpa-w" data-w="plans" aria-labelledby="w-plans">
    <div class="tpa-w-head"><h3 id="w-plans"><?php echo icon('layers',18);?><span><?php echo e(t('admin.by_plan'));?></span></h3><span class="tpa-w-aside"><?php echo(int)$stats['active'];?></span></div>
    <?php foreach($byPlan as $row){?><div class="tpa-bar-row"><div class="tpa-bar-label"><span><?php echo e($row['slug']);?></span><span><?php echo(int)$row['n'];?></span></div><div class="tpa-bar-track"><span style="width:<?php echo round($row['n']/$byPlanTotal*100);?>%"></span></div></div><?php }?>
    <?php if(!$byPlan){?><p class="tp-text-3"><?php echo e(t('common.nothing'));?></p><?php }?>
  </section>

  <section class="tp-card tpa-w" data-w="orders" aria-labelledby="w-orders">
    <div class="tpa-w-head"><h3 id="w-orders"><?php echo icon('credit-card',18);?><span><?php echo e(t('admin.recent_orders'));?></span></h3><?php if(Auth::can('orders')){?><a class="tpa-w-aside" href="/admin/orders"><?php echo e(t('common.see_all'));?></a><?php }?></div>
    <div class="tpa-w-table"><table class="tp-table"><thead><tr><th>#</th><th><?php echo e(t('admin.customer'));?></th><th><?php echo e(t('license.plan'));?></th><th><?php echo e(t('invoice.total'));?></th><th><?php echo e(t('common.status'));?></th></tr></thead><tbody>
    <?php foreach($recentOrders as $o){?><tr><td><?php if(Auth::can('orders')){?><a href="/admin/orders/<?php echo(int)$o['id'];?>"><?php echo(int)$o['id'];?></a><?php }else{echo(int)$o['id'];}?></td><td><?php echo e($o['email']);?></td><td><?php echo e($o['plan_slug']);?></td><td><?php echo e(money((int)$o['total']));?></td><td><?php partial('status_badge',['status'=>$o['status']]);?></td></tr><?php }?>
    <?php if(!$recentOrders){?><tr><td colspan="5" class="tp-text-3 tp-center"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    </tbody></table></div>
  </section>

  <section class="tp-card tpa-w" data-w="activations" aria-labelledby="w-activations">
    <div class="tpa-w-head"><h3 id="w-activations"><?php echo icon('zap',18);?><span><?php echo e(t('admin.recent_activations'));?></span></h3><?php if(Auth::can('licenses')){?><a class="tpa-w-aside" href="/admin/licenses"><?php echo e(t('common.see_all'));?></a><?php }?></div>
    <div class="tpa-w-table"><table class="tp-table"><thead><tr><th><?php echo e(t('license.key'));?></th><th><?php echo e(t('license.hostname'));?></th><th><?php echo e(t('common.version'));?></th><th><?php echo e(t('common.date'));?></th></tr></thead><tbody>
    <?php foreach($recentActivations as $a){?><tr><td><?php if(Auth::can('licenses')){?><a class="tp-mono" href="/admin/licenses/<?php echo(int)$a['license_id'];?>"><?php echo e($a['license_key']);?></a><?php }else{?><span class="tp-mono"><?php echo e($a['license_key']);?></span><?php }?></td><td><?php echo e($a['hostname']);?></td><td><?php echo e($a['version']);?></td><td><?php echo e(date_fmt($a['activated_at'],'d/m H:i'));?></td></tr><?php }?>
    <?php if(!$recentActivations){?><tr><td colspan="4" class="tp-text-3 tp-center"><?php echo e(t('common.nothing'));?></td></tr><?php }?>
    </tbody></table></div>
  </section>

  <section class="tp-card tpa-w" data-w="watch" aria-labelledby="w-watch">
    <div class="tpa-w-head"><h3 id="w-watch"><?php echo icon('alert',18);?><span><?php echo e(t('adm.w.watch'));?></span></h3><span class="tpa-w-count<?php echo $tiles?'':' tpa-w-count--zero';?>"><?php echo count($tiles);?></span></div>
    <?php if($tiles){?>
      <div class="tpa-alerts">
        <?php foreach($tiles as[$lvl,$ic,$title,$sub,$count,$href]){?>
          <a class="tpa-alert tpa-alert--<?php echo e($lvl);?>" href="<?php echo e($href);?>"><?php echo icon($ic,18);?><span class="tpa-alert-text"><strong><?php echo e($title);?></strong><?php if($sub!==''){?><span><?php echo e($sub);?></span><?php }?></span><?php if($count!==null){?><span class="tpa-alert-count"><?php echo(int)$count;?></span><?php }echo icon('chevron-right',18,'tpa-chev');?></a>
        <?php }?>
      </div>
    <?php }else{?>
      <div class="tpa-allgood"><?php echo icon('check-circle',20);?><span><?php echo e(t('adm.watch.all_good'));?></span></div>
    <?php }?>
  </section>

  <section class="tp-card tpa-w" data-w="memo" aria-labelledby="w-memo">
    <div class="tpa-w-head"><h3 id="w-memo"><?php echo icon('edit',18);?><span><?php echo e(t('adm.w.memo'));?></span></h3></div>
    <textarea class="tp-input tpa-memo" data-tpa-memo placeholder="<?php echo e(t('adm.memo_placeholder'));?>" aria-label="<?php echo e(t('adm.w.memo'));?>"></textarea>
    <div class="tpa-memo-foot" data-tpa-memo-foot aria-live="polite"><?php echo e(t('adm.memo_local'));?></div>
  </section>

  <section class="tp-card tpa-w" data-w="health" aria-labelledby="w-health">
    <div class="tpa-w-head"><h3 id="w-health"><?php echo icon('heart-pulse',18);?><span><?php echo e(t('adm.w.health'));?></span></h3><?php if(Auth::can('health')){?><a class="tpa-w-aside" href="/admin/health"><?php echo e(t('common.see_all'));?></a><?php }?></div>
    <ul class="tpa-health">
      <li><span class="tpa-dot is-ok" aria-hidden="true"></span><span>PHP</span><span><?php echo e($health['php']);?></span></li>
      <li><span class="tpa-dot is-ok" aria-hidden="true"></span><span><?php echo e(t('admin.health_db'));?></span><span><?php echo e((string)$health['db']);?></span></li>
      <li><span class="tpa-dot <?php echo $health['cron_late']?'is-bad':'is-ok';?>" aria-hidden="true"></span><span>Cron</span><span><?php echo $health['cron_last']?e(date_fmt($health['cron_last'],'d/m H:i')).' UTC':e(t('admin.health_never'));?></span></li>
      <li><span class="tpa-dot <?php echo $health['failed']>0?'is-bad':($health['pending']>20?'is-warn':'is-ok');?>" aria-hidden="true"></span><span><?php echo e(t('admin.health_queue'));?></span><span><?php echo(int)$health['pending'];?> <?php echo e(t('admin.pending'));?> · <?php echo(int)$health['failed'];?> <?php echo e(t('admin.failed'));?></span></li>
    </ul>
  </section>

</div>
