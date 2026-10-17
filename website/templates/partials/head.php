<?php $meta=$meta??[];$siteName=(string)setting('site.name',config('app.name','ToutPVR'));$title=isset($meta['title'])&&$meta['title']!==''?$meta['title'].' — '.$siteName:$siteName.' — '.t('home.tagline');$desc=$meta['description']??t('home.meta_description');$path=preg_replace('#^/[a-z]{2}(/|$)#','/',parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/');$isPublic=!str_starts_with($path,'/admin')&&!str_starts_with($path,'/account');?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?php echo e($title);?></title>
<meta name="description" content="<?php echo e($desc);?>">
<meta name="theme-color" content="#070A12">
<meta property="og:title" content="<?php echo e($title);?>">
<meta property="og:description" content="<?php echo e($desc);?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo e($siteName);?>">
<meta property="og:image" content="<?php echo e(rtrim((string)config('app.url'),'/'));?>/assets/img/og.svg">
<?php if($isPublic){?>
<link rel="canonical" href="<?php echo e(url($path));?>">
<?php foreach(\App\Core\I18n::active()as $l){?>
<link rel="alternate" hreflang="<?php echo $l;?>" href="<?php echo e(url($path,$l));?>">
<?php }?>
<link rel="alternate" hreflang="x-default" href="<?php echo e(url($path,(string)config('app.default_lang','fr')));?>">
<?php }?>
<link rel="icon" type="image/svg+xml" href="<?php echo e(rtrim((string)config('app.url'),'/'));?>/assets/img/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap">
<link rel="stylesheet" href="<?php echo e(asset('css/aurora.css'));?>">
<script nonce="<?php echo \App\Core\App::nonce();?>">(function(){try{var t=localStorage.getItem('tp.theme')||'<?php echo setting('site.default_theme','dark')==='light'?'light':'dark';?>';document.documentElement.setAttribute('data-theme',t);}catch(e){}})();</script>
