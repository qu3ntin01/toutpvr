<?php use App\Services\AdminUi;$siteName=(string)setting('site.name',config('app.name','ToutPVR'));$pageTitle=(string)($meta['title']??'');$docTitle=($pageTitle!==''?$pageTitle.' — ':'').$siteName.' Admin';$tpa=['canApplyAll'=>false,'canSettings'=>false,'palette'=>[],'icons'=>[]];foreach(['circle','sun','moon','check']as $ic){$tpa['icons'][$ic]=icon($ic,16);}?>
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
<script nonce="<?php echo \App\Core\App::nonce();?>">(function(){try{if(window.TPTheme)window.TPTheme.boot();}catch(e){}})();</script>
</head>
<body class="tp-admin tpa-login-body">
<div class="tp-aurora-bg tpa-bg" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<div class="tpa-login-top">
  <?php partial('lang_switcher');?>
  <button class="tpa-iconbtn" type="button" data-tpa-mode aria-label="<?php echo e(t('theme.mode'));?>" title="<?php echo e(t('theme.mode'));?>"><span class="tpa-mode-icon tpa-mode-icon--to-dark"><?php echo icon('moon',18);?></span><span class="tpa-mode-icon tpa-mode-icon--to-light"><?php echo icon('sun',18);?></span></button>
</div>
<main id="main" class="tpa-login-wrap">
<?php echo $content;?>
</main>
<?php partial('flashes',['flashes'=>$flashes??[]]);?>
<script nonce="<?php echo \App\Core\App::nonce();?>">window.TV={lang:<?php echo json_encode(lang());?>,csrf:<?php echo json_encode(csrf_token());?>,i18n:<?php echo json_encode(AdminUi::jsStrings(),JSON_UNESCAPED_UNICODE);?>,admin:<?php echo json_encode($tpa,JSON_UNESCAPED_UNICODE);?>};</script>
<script src="<?php echo e(asset('js/aurora.js'));?>" defer></script>
<script src="<?php echo e(asset('js/admin-ui.js'));?>" defer></script>
</body>
</html>
