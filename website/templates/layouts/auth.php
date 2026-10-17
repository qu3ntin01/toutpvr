<!DOCTYPE html>
<html lang="<?php echo e(lang());?>" dir="<?php echo \App\Core\I18n::isRtl()?'rtl':'ltr';?>" data-theme="dark">
<head>
<?php partial('head',['meta'=>$meta??[]]);?>
</head>
<body class="tp-body tp-body--center">
<div class="tp-aurora-bg" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<div class="tp-auth-topbar"><?php partial('lang_switcher');partial('theme_toggle');?></div>
<main id="main" class="tp-auth-wrap">
<?php echo $content;?>
</main>
<?php partial('flashes',['flashes'=>$flashes??[]]);?>
<script nonce="<?php echo \App\Core\App::nonce();?>">window.TV={lang:<?php echo json_encode(lang());?>,csrf:<?php echo json_encode(csrf_token());?>,i18n:{}};</script>
<script src="<?php echo e(asset('js/aurora.js'));?>" defer></script>
</body>
</html>
