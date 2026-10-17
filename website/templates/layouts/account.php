<?php $path=preg_replace('#^/[a-z]{2}(/|$)#','/',parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/');$items=[['/account','layout',t('account.dashboard'),true],['/account/licenses','key',t('account.licenses'),false],['/account/invoices','file-text',t('account.invoices'),false],['/account/downloads','download',t('account.downloads'),false],['/account/profile','user',t('account.profile'),false],['/account/security','shield',t('account.security'),false],['/account/api-keys','terminal',t('account.api_keys'),false]];?>
<!DOCTYPE html>
<html lang="<?php echo e(lang());?>" dir="<?php echo \App\Core\I18n::isRtl()?'rtl':'ltr';?>" data-theme="dark">
<head>
<?php partial('head',['meta'=>$meta??[]]);?>
</head>
<body class="tp-body">
<div class="tp-aurora-bg tp-aurora-bg--soft" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<?php partial('nav');?>
<div class="tp-container tp-app">
  <aside class="tp-sidebar" aria-label="<?php echo e(t('account.menu'));?>">
    <div class="tp-sidebar__user">
      <div class="tp-avatar"><?php echo e(mb_strtoupper(mb_substr($auth['name']?:$auth['email'],0,1)));?></div>
      <div><strong><?php echo e($auth['name']?:$auth['email']);?></strong><span class="tp-text-3"><?php echo e($auth['email']);?></span></div>
    </div>
    <nav class="tp-sidebar__nav">
      <?php foreach($items as[$href,$ic,$label,$exact]){$active=$exact?$path===$href:str_starts_with($path,$href);?>
        <a class="tp-sidebar__link<?php echo $active?' is-active':'';?>" href="<?php echo e(url($href));?>"<?php echo $active?' aria-current="page"':'';?>><?php echo icon($ic,18);?><span><?php echo e($label);?></span></a>
      <?php }?>
      <?php if(\App\Core\Auth::isAdmin()){?><a class="tp-sidebar__link" href="/admin"><?php echo icon('sliders',18);?><span><?php echo e(t('nav.admin'));?></span></a><?php }?>
      <form method="post" action="<?php echo e(url('/logout'));?>"><?php echo csrf_field();?><button class="tp-sidebar__link" type="submit"><?php echo icon('log-out',18);?><span><?php echo e(t('nav.logout'));?></span></button></form>
    </nav>
  </aside>
  <main id="main" class="tp-app__main">
    <?php echo $content;?>
  </main>
</div>
<?php partial('footer');partial('flashes',['flashes'=>$flashes??[]]);?>
<div class="tp-modal-root" data-modal-root></div>
<script nonce="<?php echo \App\Core\App::nonce();?>">window.TV={lang:<?php echo json_encode(lang());?>,csrf:<?php echo json_encode(csrf_token());?>,i18n:<?php echo json_encode(array_intersect_key(\App\Core\I18n::load(lang()),array_flip(['common.close','common.copied','common.copy','window.minimize','window.maximize','window.close'])),JSON_UNESCAPED_UNICODE);?>};</script>
<script src="<?php echo e(asset('js/aurora.js'));?>" defer></script>
</body>
</html>
