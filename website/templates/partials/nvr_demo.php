<div class="tp-window tp-window--demo" data-window data-window-id="demo" role="region" aria-label="<?php echo e(t('home.demo_title'));?>">
  <div class="tp-window__header" data-drag-handle>
    <div class="tp-window__dots"><span class="tp-window__dot tp-window__dot--close" data-window-close></span><span class="tp-window__dot tp-window__dot--min" data-window-min></span><span class="tp-window__dot tp-window__dot--max" data-window-max></span></div>
    <div class="tp-window__title"><?php echo icon('camera',14);?> ToutPVR — NVR · nvr-01</div>
    <div class="tp-window__controls"><button type="button" data-window-min aria-label="<?php echo e(t('window.minimize'));?>"><?php echo icon('minus',14);?></button><button type="button" data-window-max aria-label="<?php echo e(t('window.maximize'));?>"><?php echo icon('square',12);?></button><button type="button" data-window-close aria-label="<?php echo e(t('window.close'));?>"><?php echo icon('x',14);?></button></div>
  </div>
  <div class="tp-window__body tp-demo">
    <aside class="tp-demo__sidebar">
      <div class="tp-demo__nav is-active"><?php echo icon('layout',15);?><span><?php echo e(t('demo.dashboard'));?></span></div>
      <div class="tp-demo__nav"><?php echo icon('camera',15);?><span><?php echo e(t('demo.cameras'));?></span></div>
      <div class="tp-demo__nav"><?php echo icon('play',15);?><span><?php echo e(t('demo.recordings'));?></span></div>
      <div class="tp-demo__nav"><?php echo icon('bell',15);?><span><?php echo e(t('demo.events'));?><span class="tp-badge tp-badge--danger tp-badge--dot" data-demo-badge>3</span></span></div>
      <div class="tp-demo__nav"><?php echo icon('brain',15);?><span><?php echo e(t('demo.ai'));?></span></div>
      <div class="tp-demo__nav"><?php echo icon('settings',15);?><span><?php echo e(t('demo.settings'));?></span></div>
    </aside>
    <div class="tp-demo__main">
      <div class="tp-demo__kpis">
        <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('demo.cameras'));?></span><span class="tp-kpi__value">12<small>/12</small></span></div>
        <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('demo.events_today'));?></span><span class="tp-kpi__value" data-demo-events>47</span></div>
        <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label"><?php echo e(t('demo.storage'));?></span><span class="tp-kpi__value">1.8<small>To</small></span></div>
        <div class="tp-kpi tp-kpi--sm"><span class="tp-kpi__label">CPU</span><span class="tp-kpi__value" data-demo-cpu>23<small>%</small></span></div>
      </div>
      <div class="tp-demo__grid">
        <?php $cams=[['Entrée','person'],['Parking','vehicle'],['Jardin','animal'],['Atelier','none'],['Couloir','none'],['Quai','person']];?>
        <?php foreach($cams as $i=>[$name,$kind]){?>
        <div class="tp-demo__cam tp-demo__cam--<?php echo $i+1;?>" data-demo-cam="<?php echo $kind;?>">
          <div class="tp-demo__cam-scene"><span class="tp-demo__blob"></span><span class="tp-demo__blob tp-demo__blob--2"></span></div>
          <div class="tp-demo__bbox"><span><?php echo e(t('demo.'.($kind==='none'?'person':$kind)));?> 0.9<?php echo 1+$i;?></span></div>
          <div class="tp-demo__cam-label"><span class="tp-demo__rec"></span><?php echo $name;?> · CAM-0<?php echo $i+1;?></div>
          <div class="tp-demo__cam-time" data-demo-clock></div>
        </div>
        <?php }?>
      </div>
      <div class="tp-demo__events">
        <div class="tp-demo__events-title"><?php echo icon('activity',14);?> <?php echo e(t('demo.live_events'));?></div>
        <ul data-demo-feed>
          <li><span class="tp-badge tp-badge--success"><?php echo e(t('demo.person'));?></span> CAM-01 · 0.94</li>
          <li><span class="tp-badge tp-badge--info"><?php echo e(t('demo.vehicle'));?></span> CAM-02 · 0.88</li>
          <li><span class="tp-badge tp-badge--warning"><?php echo e(t('demo.animal'));?></span> CAM-03 · 0.81</li>
        </ul>
      </div>
    </div>
  </div>
</div>
