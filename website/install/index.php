<?php declare(strict_types=1);if(!defined('TP_ROOT')){require dirname(__DIR__).'/app/bootstrap.php';}use App\Core\Crypto;use App\Core\I18n;use App\Services\Installer;if(is_file(TP_ROOT.'/config/install.lock')){http_response_code(403);echo'<h1>Installation déjà effectuée</h1>';return;}if(session_status()!==PHP_SESSION_ACTIVE){session_name('tp_install');session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','path'=>'/']);session_start();}$_SESSION['csrf']=$_SESSION['csrf']??bin2hex(random_bytes(16));$data=&$_SESSION['install'];$data=is_array($data)?$data:[];$step=max(1,min(7,(int)($_GET['step']??$data['step']??1)));$errors=[];$ok=[];$done=null;$post=$_SERVER['REQUEST_METHOD']==='POST'?$_POST:null;$h=fn($v)=>htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');$val=fn(string $k,string $d='')=>$h($data[$k]??$d);$scheme=!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off'?'https':'http';$defaultUrl=$scheme.'://'.($_SERVER['HTTP_HOST']??'localhost');if($post!==null){if(!hash_equals($_SESSION['csrf'],(string)($post['_csrf']??''))){$errors[]='Jeton CSRF invalide, réessayez.';}else{switch($step){case 1:if(!Installer::requirementsOk()){$errors[]='Des prérequis obligatoires manquent.';}else{$step=2;}break;case 2:$driver=($post['driver']??'sqlite')==='mysql'?'mysql':'sqlite';$db=['driver'=>$driver,'prefix'=>'tp_'];if($driver==='mysql'){$db+=['host'=>trim((string)$post['db_host']),'port'=>(int)($post['db_port']?:3306),'database'=>trim((string)$post['db_name']),'username'=>trim((string)$post['db_user']),'password'=>(string)$post['db_pass']];}else{$db['sqlite_path']=trim((string)($post['sqlite_path']?:TP_ROOT.'/storage/toutpvr.sqlite'));}try{Installer::testDb($db);$data['db']=$db;$data['app_url']=rtrim(trim((string)($post['app_url']?:$defaultUrl)),'/');$data['default_lang']=in_array($post['default_lang']??'fr',I18n::LANGUAGES,true)?$post['default_lang']:'fr';$step=isset($post['test_only'])?2:3;if(isset($post['test_only'])){$ok[]='Connexion réussie ('.$driver.').';}}catch(\Throwable $e){$errors[]='Connexion impossible : '.$e->getMessage();}break;case 3:$email=trim((string)$post['admin_email']);$pw=(string)$post['admin_password'];if(!filter_var($email,FILTER_VALIDATE_EMAIL)){$errors[]='E-mail administrateur invalide.';}if(mb_strlen($pw)<12){$errors[]='Mot de passe : 12 caractères minimum.';}if($pw!==(string)$post['admin_password2']){$errors[]='Les mots de passe ne correspondent pas.';}if(!$errors){$data['admin_email']=$email;$data['admin_password']=$pw;$data['admin_name']=trim((string)($post['admin_name']?:'Administrateur'));$step=4;}break;case 4:$data['billing']=['company_name'=>trim((string)$post['company_name']),'company_address'=>trim((string)$post['company_address']),'company_country'=>strtoupper(substr(trim((string)$post['company_country']),0,2))?:'FR','company_vat'=>trim((string)$post['company_vat']),'company_siret'=>trim((string)$post['company_siret']),'currency'=>strtoupper(substr(trim((string)$post['currency']),0,3))?:'EUR','vat_rate'=>(float)str_replace(',','.',(string)$post['vat_rate']),'invoice_prefix'=>trim((string)$post['invoice_prefix'])?:'TP'];$data['support_email']=trim((string)$post['support_email'])?:'support@toutpvr.example';$step=5;break;case 5:$data['payment']=['mode'=>in_array($post['payment_mode']??'',['stripe','sandbox','manual'],true)?$post['payment_mode']:'sandbox','stripe_secret_key'=>trim((string)$post['stripe_secret_key']),'stripe_publishable_key'=>trim((string)$post['stripe_publishable_key']),'stripe_webhook_secret'=>trim((string)$post['stripe_webhook_secret'])];$data['mail']=['driver'=>($post['mail_driver']??'log')==='smtp'?'smtp':'log','from_email'=>trim((string)$post['from_email'])?:'no-reply@toutpvr.example','from_name'=>trim((string)$post['from_name'])?:'ToutPVR','smtp'=>['host'=>trim((string)$post['smtp_host']),'port'=>(int)($post['smtp_port']?:587),'encryption'=>in_array($post['smtp_encryption']??'tls',['tls','ssl','none'],true)?$post['smtp_encryption']:'tls','username'=>trim((string)$post['smtp_user']),'password'=>(string)$post['smtp_pass']]];$step=6;break;case 6:$mode=($post['keys']??'generate')==='import'?'import':'generate';if($mode==='import'){$priv=trim((string)$post['private_key']);if($priv===''&&!empty($_FILES['private_key_file']['tmp_name'])){$priv=trim((string)file_get_contents($_FILES['private_key_file']['tmp_name']));}try{Crypto::publicFromPrivate($priv);$data['keys']='import';$data['private_key']=$priv;$step=7;}catch(\Throwable $e){$errors[]='Clé privée invalide : '.$e->getMessage();}}else{$data['keys']='generate';unset($data['private_key']);$step=7;}break;case 7:try{$log=[];$res=Installer::run($data,function($m)use(&$log){$log[]=$m;});$done=['log'=>$log,'public_key'=>$res['public_key'],'editor_token'=>$res['editor_token'],'url'=>$data['app_url']];$_SESSION=[];}catch(\Throwable $e){$errors[]='Échec : '.$e->getMessage();@unlink(TP_ROOT.'/config/config.php');}break;}}}if($done===null){$data['step']=$step;}$steps=['Prérequis','Base de données','Administrateur','Société & TVA','Paiement & e-mail','Clés Ed25519','Installation'];$field=fn(string $name,string $label,string $type='text',string $value='',string $extra='')=>'<div class="tp-field"><label for="'.$name.'">'.$label.'</label><input class="tp-input" id="'.$name.'" name="'.$name.'" type="'.$type.'" value="'.$h($value).'" '.$extra.'></div>';?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Installation — ToutPVR</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap">
<link rel="stylesheet" href="/assets/css/aurora.css">
<style>.tp-install{max-width:820px;margin:40px auto;padding:0 16px}.tp-install .tp-card{padding:32px}.tp-install h1{font-size:1.6rem}.req{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--tp-glass-border)}.req:last-child{border:0}.log{font-size:.85rem}.driver-fields{display:none}.driver-fields.is-active{display:block}</style>
</head>
<body class="tp-body">
<div class="tp-aurora-bg" aria-hidden="true"><span class="tp-aurora-bg__blob tp-aurora-bg__blob--1"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--2"></span><span class="tp-aurora-bg__blob tp-aurora-bg__blob--3"></span></div>
<main class="tp-install">
  <p class="tp-center"><a class="tp-brand" href="#"><span class="tp-brand__logo">TV</span><span class="tp-brand__name">ToutPVR — Installation</span></a></p>
  <?php if($done===null){?>
  <ol class="tp-steps"><?php foreach($steps as $i=>$s){$n=$i+1;?><li class="tp-steps__item<?php echo $n<$step?' is-done':($n===$step?' is-active':'');?>"><span class="tp-steps__num"><?php echo $n;?></span><span class="tp-steps__label"><?php echo $s;?></span></li><?php }?></ol>
  <?php }?>
  <?php foreach($errors as $e){?><div class="tp-toast tp-toast--error" style="position:static;margin-bottom:12px"><?php echo $h($e);?></div><?php }?>
  <?php foreach($ok as $e){?><div class="tp-toast tp-toast--success" style="position:static;margin-bottom:12px"><?php echo $h($e);?></div><?php }?>
  <form class="tp-card tp-form" method="post" action="/install/?step=<?php echo $step;?>" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?php echo $h($_SESSION['csrf']??'');?>">
    <?php if($done!==null){?>
      <h1>✅ Installation terminée</h1>
      <p class="tp-text-2">Le portail est prêt. Conservez précieusement les informations ci-dessous.</p>
      <div class="tp-field"><label>Clé publique de licence (à embarquer dans le serveur : <code>TP_LICENSE_PUBLIC_KEY</code>)</label><pre class="tp-code"><?php echo $h($done['public_key']);?></pre></div>
      <div class="tp-field"><label>Jeton éditeur (<code>X-Editor-Token</code>, aussi modifiable dans Admin → Paramètres → Mises à jour)</label><pre class="tp-code"><?php echo $h($done['editor_token']);?></pre></div>
      <details class="tp-details"><summary>Journal</summary><pre class="tp-code log"><?php echo $h(implode("\n",$done['log']));?></pre></details>
      <p class="tp-text-2">Pensez à planifier le cron : <code>0 * * * * php <?php echo $h(TP_ROOT);?>/cli/console.php cron</code> et à protéger les dossiers <code>config/</code> et <code>storage/</code> (déjà hors racine web).</p>
      <div class="tp-row"><a class="tp-btn tp-btn--primary" href="/admin/login">Ouvrir l'administration</a><a class="tp-btn tp-btn--ghost" href="<?php echo $h($done['url']);?>/">Voir le site</a></div>
    <?php }elseif($step===1){?>
      <h1>Prérequis</h1>
      <div><?php foreach(Installer::requirements()as $r){?><div class="req"><span><?php echo $h($r['label']);echo $r['required']?'':' <span class="tp-text-3">(optionnel)</span>';?></span><span class="tp-badge tp-badge--<?php echo $r['ok']?'success':($r['required']?'danger':'warning');?>"><?php echo $h($r['detail']);?></span></div><?php }?></div>
      <div class="tp-row"><button class="tp-btn tp-btn--primary" type="submit" <?php echo Installer::requirementsOk()?'':'disabled';?>>Continuer</button><a class="tp-btn tp-btn--ghost" href="/install/?step=1">Revérifier</a></div>
    <?php }elseif($step===2){?>
      <h1>Base de données</h1>
      <?php echo $field('app_url','URL publique du site','url',$data['app_url']??$defaultUrl,'required');?>
      <div class="tp-field"><label for="default_lang">Langue par défaut</label><select class="tp-select" id="default_lang" name="default_lang"><?php foreach(I18n::LANGUAGES as $l){?><option value="<?php echo $l;?>" <?php echo($data['default_lang']??'fr')===$l?'selected':'';?>><?php echo $h(I18n::name($l));?></option><?php }?></select></div>
      <div class="tp-field"><label>Pilote</label><div class="tp-segment"><label class="tp-segment__opt"><input type="radio" name="driver" value="sqlite" <?php echo($data['db']['driver']??'sqlite')==='sqlite'?'checked':'';?> data-driver><span>SQLite (simple)</span></label><label class="tp-segment__opt"><input type="radio" name="driver" value="mysql" <?php echo($data['db']['driver']??'')==='mysql'?'checked':'';?> data-driver><span>MySQL / MariaDB</span></label></div></div>
      <div class="driver-fields" data-driver-fields="sqlite"><?php echo $field('sqlite_path','Chemin du fichier SQLite','text',$data['db']['sqlite_path']??TP_ROOT.'/storage/toutpvr.sqlite');?></div>
      <div class="driver-fields" data-driver-fields="mysql"><div class="tp-grid tp-grid--2 tp-grid--tight"><?php echo $field('db_host','Hôte','text',$data['db']['host']??'127.0.0.1');echo $field('db_port','Port','number',(string)($data['db']['port']??3306));echo $field('db_name','Base','text',$data['db']['database']??'toutpvr');echo $field('db_user','Utilisateur','text',$data['db']['username']??'toutpvr');echo $field('db_pass','Mot de passe','password','');?></div></div>
      <div class="tp-row"><button class="tp-btn tp-btn--ghost" type="submit" name="test_only" value="1">Tester la connexion</button><button class="tp-btn tp-btn--primary" type="submit">Continuer</button></div>
    <?php }elseif($step===3){?>
      <h1>Compte administrateur</h1>
      <?php echo $field('admin_name','Nom','text',$data['admin_name']??'Administrateur');?>
      <?php echo $field('admin_email','E-mail','email',$data['admin_email']??'','required autocomplete="username"');?>
      <div class="tp-grid tp-grid--2 tp-grid--tight"><?php echo $field('admin_password','Mot de passe (12+ caractères)','password','','required minlength="12" autocomplete="new-password"');echo $field('admin_password2','Confirmation','password','','required autocomplete="new-password"');?></div>
      <div class="tp-row"><a class="tp-btn tp-btn--ghost" href="/install/?step=2">Retour</a><button class="tp-btn tp-btn--primary" type="submit">Continuer</button></div>
    <?php }elseif($step===4){?>
      <h1>Société & TVA</h1>
      <?php $b=$data['billing']??[];?>
      <div class="tp-grid tp-grid--2 tp-grid--tight">
        <?php echo $field('company_name','Raison sociale','text',$b['company_name']??'ToutPVR Éditions','required');?>
        <?php echo $field('support_email','E-mail support','email',$data['support_email']??'support@toutpvr.example');?>
        <?php echo $field('company_vat','N° TVA intracommunautaire','text',$b['company_vat']??'FR00000000000');?>
        <?php echo $field('company_siret','SIRET','text',$b['company_siret']??'');?>
        <?php echo $field('company_country','Pays (ISO 2)','text',$b['company_country']??'FR','maxlength="2"');?>
        <?php echo $field('currency','Devise','text',$b['currency']??'EUR','maxlength="3"');?>
        <?php echo $field('vat_rate','Taux de TVA (%)','text',(string)($b['vat_rate']??'20'));?>
        <?php echo $field('invoice_prefix','Préfixe des factures','text',$b['invoice_prefix']??'TP');?>
      </div>
      <div class="tp-field"><label for="company_address">Adresse</label><textarea class="tp-input" id="company_address" name="company_address" rows="3"><?php echo $h($b['company_address']??"1 rue de l'Exemple\n75001 Paris\nFrance");?></textarea></div>
      <div class="tp-row"><a class="tp-btn tp-btn--ghost" href="/install/?step=3">Retour</a><button class="tp-btn tp-btn--primary" type="submit">Continuer</button></div>
    <?php }elseif($step===5){?>
      <h1>Paiement & e-mail</h1>
      <?php $p=$data['payment']??[];$m=$data['mail']??[];?>
      <div class="tp-field"><label for="payment_mode">Mode de paiement</label><select class="tp-select" id="payment_mode" name="payment_mode"><option value="sandbox" <?php echo($p['mode']??'sandbox')==='sandbox'?'selected':'';?>>Sandbox (paiement simulé, pour tester)</option><option value="stripe" <?php echo($p['mode']??'')==='stripe'?'selected':'';?>>Stripe Checkout</option><option value="manual" <?php echo($p['mode']??'')==='manual'?'selected':'';?>>Manuel (virement)</option></select></div>
      <div class="tp-grid tp-grid--3 tp-grid--tight"><?php echo $field('stripe_publishable_key','Stripe publishable key','text',$p['stripe_publishable_key']??'');echo $field('stripe_secret_key','Stripe secret key','password',$p['stripe_secret_key']??'');echo $field('stripe_webhook_secret','Stripe webhook secret','password',$p['stripe_webhook_secret']??'');?></div>
      <hr style="border:0;border-top:1px solid var(--tp-glass-border)">
      <div class="tp-grid tp-grid--3 tp-grid--tight">
        <div class="tp-field"><label for="mail_driver">Envoi des e-mails</label><select class="tp-select" id="mail_driver" name="mail_driver"><option value="log" <?php echo($m['driver']??'log')==='log'?'selected':'';?>>Journal (storage/logs/mail.log)</option><option value="smtp" <?php echo($m['driver']??'')==='smtp'?'selected':'';?>>SMTP</option></select></div>
        <?php echo $field('from_email','Expéditeur','email',$m['from_email']??'no-reply@toutpvr.example');echo $field('from_name','Nom expéditeur','text',$m['from_name']??'ToutPVR');?>
        <?php echo $field('smtp_host','SMTP hôte','text',$m['smtp']['host']??'');echo $field('smtp_port','Port','number',(string)($m['smtp']['port']??587));?>
        <div class="tp-field"><label for="smtp_encryption">Chiffrement</label><select class="tp-select" id="smtp_encryption" name="smtp_encryption"><option value="tls">STARTTLS</option><option value="ssl">SSL</option><option value="none">Aucun</option></select></div>
        <?php echo $field('smtp_user','Utilisateur SMTP','text',$m['smtp']['username']??'');echo $field('smtp_pass','Mot de passe SMTP','password','');?>
      </div>
      <div class="tp-row"><a class="tp-btn tp-btn--ghost" href="/install/?step=4">Retour</a><button class="tp-btn tp-btn--primary" type="submit">Continuer</button></div>
    <?php }elseif($step===6){?>
      <h1>Clés Ed25519 (licences & mises à jour)</h1>
      <p class="tp-text-2">La clé privée signe les jetons de licence et les manifestes de mise à jour ; elle est stockée dans <code>config/license_private.key</code> (0600). La clé publique est distribuée aux serveurs.</p>
      <div class="tp-field"><label class="tp-check"><input type="radio" name="keys" value="generate" <?php echo($data['keys']??'generate')==='generate'?'checked':'';?>> <span><strong>Générer une nouvelle paire</strong> (recommandé en production)</span></label></div>
      <div class="tp-field"><label class="tp-check"><input type="radio" name="keys" value="import" <?php echo($data['keys']??'')==='import'?'checked':'';?>> <span><strong>Importer une clé privée existante</strong> (clé secrète libsodium 64 octets en base64)</span></label></div>
      <div class="tp-field"><label for="private_key">Clé privée base64</label><textarea class="tp-input tp-mono" id="private_key" name="private_key" rows="2" placeholder="ynHiCx…"></textarea></div>
      <div class="tp-field"><label for="private_key_file">…ou fichier</label><input class="tp-input" type="file" id="private_key_file" name="private_key_file"></div>
      <div class="tp-row"><a class="tp-btn tp-btn--ghost" href="/install/?step=5">Retour</a><button class="tp-btn tp-btn--primary" type="submit">Continuer</button></div>
    <?php }else{?>
      <h1>Récapitulatif</h1>
      <dl class="tp-dl">
        <dt>URL</dt><dd><?php echo $val('app_url');?></dd>
        <dt>Base</dt><dd><?php echo $h($data['db']['driver']??'');?> <?php echo $h(($data['db']['driver']??'')==='mysql'?$data['db']['host'].'/'.$data['db']['database']:$data['db']['sqlite_path']??'');?></dd>
        <dt>Admin</dt><dd><?php echo $val('admin_email');?></dd>
        <dt>Société</dt><dd><?php echo $h($data['billing']['company_name']??'');?> — TVA <?php echo $h((string)($data['billing']['vat_rate']??''));?> %</dd>
        <dt>Paiement</dt><dd><?php echo $h($data['payment']['mode']??'');?></dd>
        <dt>E-mail</dt><dd><?php echo $h($data['mail']['driver']??'');?></dd>
        <dt>Clés</dt><dd><?php echo($data['keys']??'generate')==='import'?'import de la clé fournie':'génération d\'une nouvelle paire';?></dd>
      </dl>
      <p class="tp-text-2">L'installation écrit <code>config/config.php</code>, exécute les migrations, insère les plans (trial, starter 29,99 €, pro 59,99 €, business 129,99 €), les pages légales, les menus, puis crée <code>config/install.lock</code>.</p>
      <div class="tp-row"><a class="tp-btn tp-btn--ghost" href="/install/?step=6">Retour</a><button class="tp-btn tp-btn--primary" type="submit">Installer</button></div>
    <?php }?>
  </form>
</main>
<script>
(function(){var r=document.querySelectorAll('[data-driver]'),f=document.querySelectorAll('[data-driver-fields]');function u(){var v='sqlite';r.forEach(function(x){if(x.checked)v=x.value});f.forEach(function(x){x.classList.toggle('is-active',x.dataset.driverFields===v)})}r.forEach(function(x){x.addEventListener('change',u)});u();})();
</script>
</body>
</html>
