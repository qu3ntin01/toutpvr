<!DOCTYPE html>
<html lang="<?php echo e(lang());?>" dir="<?php echo \App\Core\I18n::isRtl()?'rtl':'ltr';?>" data-theme="dark">
<head>
<?php partial('head',['meta'=>$meta??[]]);?>
</head>
<body class="tp-body">
<div class="tp-aurora-bg" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<a class="tp-skip" href="#main"><?php echo e(t('common.skip'));?></a>
<?php partial('nav');?>
<main id="main" class="tp-main">
<?php echo $content;?>
</main>
<?php partial('footer');partial('flashes',['flashes'=>$flashes??[]]);?>
<div class="tp-modal-root" data-modal-root></div>
<script nonce="<?php echo \App\Core\App::nonce();?>">window.TV={lang:<?php echo json_encode(lang());?>,csrf:<?php echo json_encode(csrf_token());?>,i18n:<?php echo json_encode(array_intersect_key(\App\Core\I18n::load(lang()),array_flip(['common.close','common.copied','common.copy','window.minimize','window.maximize','window.close','pricing.per_month','pricing.per_year','pricing.billed_yearly'])),JSON_UNESCAPED_UNICODE);?>};</script>
<script src="<?php echo e(asset('js/aurora.js'));?>" defer></script>
</body>
</html>
