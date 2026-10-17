<section class="tp-hero">
  <div class="tp-container tp-hero__inner">
    <div class="tp-hero__text" data-reveal>
      <span class="tp-badge tp-badge--aurora"><?php echo icon('sparkles',14);?> <?php echo e(t('home.badge'));?></span>
      <h1 class="tp-hero__title"><?php echo e(t('home.title_1'));?> <span class="tp-gradient-text"><?php echo e(t('home.title_2'));?></span></h1>
      <p class="tp-hero__lead"><?php echo e(t('home.lead'));?></p>
      <div class="tp-hero__cta">
        <a class="tp-btn tp-btn--primary tp-btn--lg" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('home.cta_trial'));?> <?php echo icon('arrow-right',18);?></a>
        <a class="tp-btn tp-btn--ghost tp-btn--lg" href="<?php echo e(url('/download'));?>"><?php echo icon('download',18);?> <?php echo e(t('home.cta_download'));?></a>
      </div>
      <ul class="tp-hero__points">
        <li><?php echo icon('check',16);?> <?php echo e(t('home.point_1'));?></li>
        <li><?php echo icon('check',16);?> <?php echo e(t('home.point_2'));?></li>
        <li><?php echo icon('check',16);?> <?php echo e(t('home.point_3'));?></li>
      </ul>
    </div>
    <div class="tp-hero__demo" data-reveal>
      <?php partial('nvr_demo');?>
    </div>
  </div>
</section>

<section class="tp-section tp-section--stats">
  <div class="tp-container tp-stats">
    <div class="tp-stat" data-reveal><span class="tp-stat__value" data-counter="20">0</span><span class="tp-stat__label"><?php echo e(t('home.stat_langs'));?></span></div>
    <div class="tp-stat" data-reveal><span class="tp-stat__value"><span data-counter="99.9" data-decimals="1">0</span>%</span><span class="tp-stat__label"><?php echo e(t('home.stat_uptime'));?></span></div>
    <div class="tp-stat" data-reveal><span class="tp-stat__value">&lt;<span data-counter="120">0</span> ms</span><span class="tp-stat__label"><?php echo e(t('home.stat_latency'));?></span></div>
    <div class="tp-stat" data-reveal><span class="tp-stat__value"><span data-counter="5">0</span> s</span><span class="tp-stat__label"><?php echo e(t('home.stat_prerecord'));?></span></div>
  </div>
</section>

<section class="tp-section" id="features">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal>
      <span class="tp-eyebrow"><?php echo e(t('home.features_eyebrow'));?></span>
      <h2><?php echo e(t('home.features_title'));?></h2>
      <p class="tp-text-2"><?php echo e(t('home.features_lead'));?></p>
    </div>
    <div class="tp-grid tp-grid--3">
      <?php foreach([['camera','f1'],['brain','f2'],['hard-drive','f3'],['bell','f4'],['users','f5'],['shield-check','f6']]as[$ic,$k]){?>
      <article class="tp-card tp-card--feature" data-reveal>
        <span class="tp-card__icon"><?php echo icon($ic,22);?></span>
        <h3><?php echo e(t('features.'.$k.'.title'));?></h3>
        <p class="tp-text-2"><?php echo e(t('features.'.$k.'.desc'));?></p>
      </article>
      <?php }?>
    </div>
  </div>
</section>

<section class="tp-section tp-section--ai">
  <div class="tp-container tp-split">
    <div data-reveal>
      <span class="tp-eyebrow"><?php echo e(t('home.ai_eyebrow'));?></span>
      <h2><?php echo e(t('home.ai_title'));?></h2>
      <p class="tp-text-2"><?php echo e(t('home.ai_lead'));?></p>
      <ul class="tp-checklist">
        <li><?php echo icon('user',16);?> <?php echo e(t('home.ai_1'));?></li>
        <li><?php echo icon('car',16);?> <?php echo e(t('home.ai_2'));?></li>
        <li><?php echo icon('dog',16);?> <?php echo e(t('home.ai_3'));?></li>
        <li><?php echo icon('move',16);?> <?php echo e(t('home.ai_4'));?></li>
        <li><?php echo icon('timer',16);?> <?php echo e(t('home.ai_5'));?></li>
      </ul>
    </div>
    <div class="tp-window tp-window--static" data-reveal>
      <div class="tp-window__header"><div class="tp-window__dots"><span class="tp-window__dot tp-window__dot--close"></span><span class="tp-window__dot tp-window__dot--min"></span><span class="tp-window__dot tp-window__dot--max"></span></div><div class="tp-window__title"><?php echo icon('brain',14);?> <?php echo e(t('home.ai_window'));?></div></div>
      <div class="tp-window__body tp-ai-pipeline">
        <?php foreach([['camera','p1'],['activity','p2'],['brain','p3'],['move','p4'],['bell','p5']]as $i=>[$ic,$k]){?>
        <div class="tp-ai-pipeline__step" style="--i:<?php echo $i;?>"><span class="tp-ai-pipeline__icon"><?php echo icon($ic,18);?></span><span><?php echo e(t('home.pipeline_'.$k));?></span></div>
        <?php }?>
      </div>
    </div>
  </div>
</section>

<section class="tp-section" id="compare">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('home.compare_eyebrow'));?></span><h2><?php echo e(t('home.compare_title'));?></h2></div>
    <div class="tp-card tp-card--table" data-reveal>
      <table class="tp-table tp-table--compare">
        <thead><tr><th></th><th><?php echo e(t('home.compare_us'));?></th><th><?php echo e(t('home.compare_cloud'));?></th><th><?php echo e(t('home.compare_diy'));?></th></tr></thead>
        <tbody>
          <?php foreach(['c1'=>[1,0,1],'c2'=>[1,0,0],'c3'=>[1,1,0],'c4'=>[1,0,0],'c5'=>[1,1,0],'c6'=>[1,0,0]]as $k=>$row){?>
          <tr><th><?php echo e(t('home.compare_'.$k));?></th><?php foreach($row as $v){?><td><?php echo $v?'<span class="tp-yes">'.icon('check',16).'</span>':'<span class="tp-no">'.icon('x',16).'</span>';?></td><?php }?></tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="tp-section" id="pricing">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('nav.pricing'));?></span><h2><?php echo e(t('pricing.title'));?></h2><p class="tp-text-2"><?php echo e(t('pricing.lead'));?></p></div>
    <?php partial('pricing_cards',['plans'=>$plans,'compact'=>true]);?>
  </div>
</section>

<section class="tp-section">
  <div class="tp-container">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow"><?php echo e(t('home.testimonials_eyebrow'));?></span><h2><?php echo e(t('home.testimonials_title'));?></h2></div>
    <div class="tp-grid tp-grid--3">
      <?php foreach(['t1','t2','t3']as $k){?>
      <blockquote class="tp-card tp-quote" data-reveal>
        <div class="tp-quote__stars"><?php echo str_repeat(icon('star',14,'tp-star'),5);?></div>
        <p>« <?php echo e(t('home.'.$k.'_text'));?> »</p>
        <footer><strong><?php echo e(t('home.'.$k.'_name'));?></strong><span class="tp-text-3"><?php echo e(t('home.'.$k.'_role'));?></span></footer>
      </blockquote>
      <?php }?>
    </div>
  </div>
</section>

<section class="tp-section" id="faq">
  <div class="tp-container tp-container--narrow">
    <div class="tp-section__head" data-reveal><span class="tp-eyebrow">FAQ</span><h2><?php echo e(t('home.faq_title'));?></h2></div>
    <?php for($i=1;$i<=6;$i++){?>
    <details class="tp-faq" data-reveal><summary><?php echo e(t('faq.q'.$i));echo icon('chevron-down',18);?></summary><div><?php echo e(t('faq.a'.$i));?></div></details>
    <?php }?>
  </div>
</section>

<?php if($posts){?>
<section class="tp-section">
  <div class="tp-container">
    <div class="tp-section__head tp-section__head--row" data-reveal><h2><?php echo e(t('nav.blog'));?></h2><a class="tp-btn tp-btn--ghost" href="<?php echo e(url('/blog'));?>"><?php echo e(t('common.see_all'));?> <?php echo icon('arrow-right',16);?></a></div>
    <div class="tp-grid tp-grid--3">
      <?php foreach($posts as $p){?>
      <a class="tp-card tp-card--post" href="<?php echo e(url('/blog/'.$p['slug']));?>" data-reveal>
        <span class="tp-text-3"><?php echo e(date_fmt($p['published_at']));?></span>
        <h3><?php echo e(\App\Core\I18n::pick($p['title']));?></h3>
        <p class="tp-text-2"><?php echo e(\App\Core\I18n::pick($p['excerpt']??[])?:\App\Services\CmsService::excerpt($p['blocks']));?></p>
      </a>
      <?php }?>
    </div>
  </div>
</section>
<?php }?>

<section class="tp-section tp-section--cta">
  <div class="tp-container">
    <div class="tp-cta-box" data-reveal>
      <h2><?php echo e(t('home.cta_title'));?></h2>
      <p class="tp-text-2"><?php echo e(t('home.cta_lead'));?></p>
      <div class="tp-hero__cta"><a class="tp-btn tp-btn--primary tp-btn--lg" href="<?php echo e(url('/pricing'));?>"><?php echo e(t('home.cta_trial'));?></a><a class="tp-btn tp-btn--ghost tp-btn--lg" href="<?php echo e(url('/contact'));?>"><?php echo e(t('nav.contact'));?></a></div>
    </div>
  </div>
</section>
