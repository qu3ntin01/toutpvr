<?php use App\Core\Auth;use App\Services\AdminUi;$path=parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/';$groups=AdminUi::visibleNav();$unread=AdminUi::unreadMessages();$badges=['unread'=>$unread];$siteName=(string)setting('site.name',config('app.name','ToutPVR'));$pageTitle=(string)($meta['title']??'');$docTitle=($pageTitle!==''?$pageTitle.' — ':'').$siteName.' Admin';$activeGroup='';$palette=[];foreach($groups as $label=>$items){foreach($items as[$href,$ic,$lbl,$perm,$exact]){$isActive=$exact?$path===$href:str_starts_with($path,$href);if($isActive){$activeGroup=$label;}$palette[]=['href'=>$href,'label'=>$lbl,'group'=>$label,'icon'=>$ic];}}$pills=$meta['pills']??AdminUi::pills();$name=(string)($auth['name']??''?:$auth['email']??'');$tpa=['canApplyAll'=>AdminUi::canApplyToAll(),'canSettings'=>Auth::can('settings'),'settingsUrl'=>'/admin/settings?tab=appearance','palette'=>$palette,'hasDefaults'=>AdminUi::defaults()!==null,'icons'=>[]];foreach(['circle','check','sun','moon','monitor','grip','arrow-up','arrow-down','chevron-right','x','search','palette','sliders','rotate','users','layout','credit-card','key','bar-chart','trending-up','layers','zap','alert','edit','heart-pulse','file-text','file','list','image','package','brain','tag','inbox','settings','shield','activity']as $ic){$tpa['icons'][$ic]=icon($ic,16);}?>
<!DOCTYPE html>
<html lang="<?php echo e(lang());?>" dir="<?php echo \App\Core\I18n::isRtl()?'rtl':'ltr';?>"<?php echo AdminUi::htmlAttributes();?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="color-scheme" content="light dark">
<meta name="theme-color" content="#e9edfb">
<meta name="robots" content="noindex,nofollow">
<title><?php echo e($docTitle);?></title>
<link rel="icon" type="image/svg+xml" href="<?php echo e(rtrim((string)config('app.url'),'/'));?>/assets/img/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap">
<link rel="stylesheet" href="<?php echo e(asset('css/aurora.css'));?>">
<link rel="stylesheet" href="<?php echo e(asset('css/admin-themes.css'));?>">
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css'));?>">
<script src="<?php echo e(asset('js/admin-theme-core.js'));?>"></script>
<script nonce="<?php echo \App\Core\App::nonce();?>">/* avant le premier rendu : thème, mode, accent, arrondi, densité, fond, barre latérale, disposition du tableau de bord */
(function(){try{if(window.TPTheme)window.TPTheme.boot();}catch(e){}
try{var d=JSON.parse(localStorage.getItem('tp.admin.dashboard')||'null');if(d&&typeof d==='object'){var css='';var sp={'1/3':4,'1/2':6,'2/3':8,'full':12};var hid=Array.isArray(d.hidden)?d.hidden:[];var ord=Array.isArray(d.order)?d.order:[];var w=d.widths||{};
ord.forEach(function(id,i){if(/^[a-z]+$/.test(id))css+='.tpa-w[data-w="'+id+'"]{order:'+i+'}';});
hid.forEach(function(id){if(/^[a-z]+$/.test(id))css+='.tpa-w[data-w="'+id+'"]{display:none}';});
Object.keys(w).forEach(function(id){if(/^[a-z]+$/.test(id)&&sp[w[id]])css+='.tpa-w[data-w="'+id+'"]{--span:'+sp[w[id]]+'}';});
if(css){var s=document.createElement('style');s.id='tpa-dash-pre';s.textContent=css;document.head.appendChild(s);}}}catch(e){}})();</script>
</head>
<body class="tp-admin" data-admin>
<div class="tp-aurora-bg tpa-bg" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<a class="tp-skip" href="#main"><?php echo e(t('common.skip'));?></a>
<div class="tpa-shell">
  <div class="tpa-scrim" data-tpa-scrim></div>
  <aside class="tpa-sidebar" id="tpa-sidebar" data-tpa-sidebar aria-label="<?php echo e(t('admin.menu'));?>">
    <a class="tpa-brand" href="/admin">
      <span class="tpa-brand-logo"><?php echo icon('camera',20);?></span>
      <span class="tpa-brand-text"><span class="tpa-brand-name"><?php echo e($siteName);?></span><span class="tpa-brand-sub">Admin</span></span>
    </a>
    <nav class="tpa-nav" aria-label="<?php echo e(t('admin.menu'));?>">
      <?php $gi=0;foreach($groups as $label=>$items){$gi++;$open=true;?>
        <button class="tpa-nav-group" type="button" aria-expanded="true" aria-controls="tpa-g<?php echo $gi;?>" data-tpa-group="<?php echo $gi;?>"><span><?php echo e($label);?></span><?php echo icon('chevron-down',14);?></button>
        <div class="tpa-nav-list" id="tpa-g<?php echo $gi;?>">
          <?php foreach($items as[$href,$ic,$lbl,$perm,$exact,$badge]){$active=$exact?$path===$href:str_starts_with($path,$href);?>
            <a class="tpa-nav-item<?php echo $active?' is-active':'';?>" href="<?php echo e($href);?>" title="<?php echo e($lbl);?>"<?php echo $active?' aria-current="page"':'';?>><?php echo icon($ic,18);?><span><?php echo e($lbl);?></span><?php if($badge&&!empty($badges[$badge])){?><span class="tpa-nav-count"><?php echo(int)$badges[$badge];?></span><?php }?></a>
          <?php }?>
        </div>
      <?php }?>
    </nav>
    <div class="tpa-sidebar-foot">
      <div class="tpa-user">
        <span class="tpa-avatar"><?php echo e(mb_strtoupper(mb_substr($name,0,1)));?></span>
        <span class="tpa-user-meta"><strong><?php echo e($name);?></strong><span><?php echo e($auth['role']??'');?></span></span>
      </div>
      <a class="tpa-sb-btn tpa-sb-btn--ghost" href="<?php echo e(url('/'));?>" target="_blank" rel="noopener" title="<?php echo e(t('admin.view_site'));?>"><?php echo icon('external',16);?><span class="tpa-sb-label"><?php echo e(t('admin.view_site'));?></span></a>
      <div class="tpa-foot-actions">
        <button class="tpa-sb-btn tpa-sb-btn--grow" type="button" data-tpa-appearance aria-haspopup="dialog" aria-expanded="false" title="<?php echo e(t('theme.appearance'));?>"><?php echo icon('palette',16);?><span class="tpa-sb-label"><?php echo e(t('theme.appearance'));?></span></button>
        <button class="tpa-sb-btn tpa-sb-btn--icon tpa-sb-collapse" type="button" data-tpa-collapse aria-pressed="false" title="<?php echo e(t('nav.collapse'));?>" aria-label="<?php echo e(t('nav.collapse'));?>"><?php echo icon('chevrons-left',16);?></button>
      </div>
    </div>
  </aside>
  <div class="tpa-main">
    <header class="tpa-topbar">
      <button class="tpa-iconbtn tpa-burger" type="button" data-tpa-burger aria-controls="tpa-sidebar" aria-expanded="false" aria-label="<?php echo e(t('nav.menu'));?>"><?php echo icon('menu',20);?></button>
      <div class="tpa-host" title="<?php echo e((string)config('app.url'));?>">
        <span class="tpa-dot<?php echo AdminUi::cronLate()?' is-warn':'';?>" aria-hidden="true"></span>
        <span class="tpa-host-name"><?php echo e(AdminUi::host());?></span>
        <span class="tpa-pill tpa-pill--env"><?php echo e(AdminUi::environment());?></span>
      </div>
      <div class="tpa-topbar-actions">
        <button class="tpa-search" type="button" data-tpa-palette aria-haspopup="dialog" title="<?php echo e(t('adm.search'));?> (Ctrl K)"><?php echo icon('search',16);?><span><?php echo e(t('adm.search'));?></span><kbd>Ctrl K</kbd></button>
        <div class="tp-dropdown tpa-lang" data-dropdown>
          <button class="tpa-langbtn" type="button" aria-haspopup="menu" aria-expanded="false" data-dropdown-toggle aria-label="<?php echo e(t('common.language'));?>" title="<?php echo e(t('common.language'));?>"><?php echo e(strtoupper(lang()));?></button>
          <div class="tp-dropdown__menu tp-dropdown__menu--langs" role="menu">
            <?php foreach(\App\Core\I18n::active()as $l){?>
              <form method="post" action="/admin/language"><?php echo csrf_field();?><input type="hidden" name="lang" value="<?php echo e($l);?>"><button type="submit" role="menuitem" class="tp-dropdown__item<?php echo $l===lang()?' is-active':'';?>" lang="<?php echo e($l);?>"><span class="tp-lang-code"><?php echo e(strtoupper($l));?></span> <?php echo e(\App\Core\I18n::name($l));?></button></form>
            <?php }?>
          </div>
        </div>
        <button class="tpa-iconbtn" type="button" data-tpa-mode aria-label="<?php echo e(t('theme.mode'));?>" title="<?php echo e(t('theme.mode'));?>"><span class="tpa-mode-icon tpa-mode-icon--to-dark"><?php echo icon('moon',18);?></span><span class="tpa-mode-icon tpa-mode-icon--to-light"><?php echo icon('sun',18);?></span></button>
        <button class="tpa-iconbtn" type="button" data-tpa-refresh aria-label="<?php echo e(t('adm.refresh'));?>" title="<?php echo e(t('adm.refresh'));?>"><?php echo icon('refresh',18);?></button>
        <form method="post" action="<?php echo e(url('/logout'));?>" class="tpa-logout"><?php echo csrf_field();?><button class="tpa-iconbtn" type="submit" aria-label="<?php echo e(t('nav.logout'));?>" title="<?php echo e(t('nav.logout'));?>"><?php echo icon('power',18);?></button></form>
      </div>
    </header>
    <main id="main" class="tpa-page tpa-page-enter">
      <div class="tpa-page-head">
        <div class="tpa-page-titles">
          <?php if($activeGroup!==''){?><div class="tpa-eyebrow"><?php echo e($activeGroup);?></div><?php }?>
          <h1><?php echo e($pageTitle);?><span class="tpa-help" tabindex="0" role="img" title="<?php echo e($meta['help']??t('adm.help'));?>" aria-label="<?php echo e($meta['help']??t('adm.help'));?>"><?php echo icon('help',15);?></span></h1>
        </div>
        <div class="tpa-page-actions">
          <?php foreach($pills as $p){?><span class="tpa-status-pill"><span class="tpa-dot is-<?php echo e($p['level']);?>" aria-hidden="true"></span><?php echo e($p['label']);?></span><?php }?>
          <button class="tpa-btn-outline" type="button" data-tpa-customize><?php echo icon('edit',16);?><span><?php echo e(t('customize.title'));?></span></button>
        </div>
      </div>
      <?php echo $content;?>
    </main>
  </div>
</div>
<?php partial('flashes',['flashes'=>$flashes??[]]);?>
<div class="tp-modal-root" data-modal-root></div>
<script nonce="<?php echo \App\Core\App::nonce();?>">window.TV={lang:<?php echo json_encode(lang());?>,csrf:<?php echo json_encode(csrf_token());?>,i18n:<?php echo json_encode(AdminUi::jsStrings(),JSON_UNESCAPED_UNICODE);?>,admin:<?php echo json_encode($tpa,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);?>};</script>
<script src="<?php echo e(asset('js/aurora.js'));?>" defer></script>
<script src="<?php echo e(asset('js/admin-ui.js'));?>" defer></script>
<script src="<?php echo e(asset('js/admin.js'));?>" defer></script>
</body>
</html>
