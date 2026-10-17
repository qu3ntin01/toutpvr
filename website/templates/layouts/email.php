<?php $site=(string)setting('site.name','ToutPVR');$base=rtrim((string)config('app.url'),'/');?>
<!DOCTYPE html>
<html lang="<?php echo e($lang??lang());?>" dir="<?php echo \App\Core\I18n::isRtl($lang??null)?'rtl':'ltr';?>">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title><?php echo e($subject??$site);?></title>
<style>
body{margin:0;padding:0;background:#070A12;font-family:Inter,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:#F5F7FF}
.wrap{max-width:600px;margin:0 auto;padding:32px 16px}
.card{background:linear-gradient(160deg,rgba(255,255,255,.10),rgba(255,255,255,.04));border:1px solid rgba(255,255,255,.14);border-radius:26px;padding:32px;box-shadow:0 24px 64px -24px rgba(0,0,0,.7)}
.aurora{height:6px;border-radius:6px;background:linear-gradient(90deg,#6D5DF6,#22D3EE,#F472B6,#34D399);margin-bottom:24px}
h1{font-size:22px;margin:0 0 16px;font-weight:700}
p{line-height:1.6;color:rgba(245,247,255,.8);margin:0 0 14px}
.btn{display:inline-block;padding:12px 22px;border-radius:12px;background:linear-gradient(135deg,#7C6CFF,#22D3EE);color:#fff !important;text-decoration:none;font-weight:600}
.key{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:20px;letter-spacing:1px;background:rgba(255,255,255,.08);border:1px dashed rgba(255,255,255,.25);border-radius:12px;padding:14px;text-align:center;margin:18px 0;color:#fff}
.muted{color:rgba(245,247,255,.5);font-size:12px}
table{width:100%;border-collapse:collapse}td{padding:6px 0;color:rgba(245,247,255,.8)}td.r{text-align:right}
.brand{font-weight:800;font-size:18px;color:#fff;text-decoration:none}
.logo{display:inline-block;width:28px;height:28px;border-radius:9px;background:linear-gradient(135deg,#6D5DF6,#22D3EE);vertical-align:middle;margin-right:8px}
</style></head>
<body><div class="wrap">
<p style="margin-bottom:18px"><a class="brand" href="<?php echo e($base);?>"><span class="logo"></span><?php echo e($site);?></a></p>
<div class="card"><div class="aurora"></div>
<?php echo $content;?>
</div>
<p class="muted" style="text-align:center;margin-top:20px"><?php echo e(setting('billing.company_name','ToutPVR Éditions'));?> · <a href="<?php echo e($base);?>" style="color:#22D3EE"><?php echo e(preg_replace('#^https?://#','',$base));?></a> · <?php echo e(t('email.footer_auto'));?></p>
</div></body></html>
