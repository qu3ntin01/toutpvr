<?php $i=$invoice;$b=$i['billing'];$s=$i['seller'];$cur=$i['currency'];?>
<!DOCTYPE html>
<html lang="<?php echo e(lang());?>"><head><meta charset="utf-8"><title><?php echo e(t('invoice.title'));?> <?php echo e($i['number']);?></title>
<style>body{font-family:Inter,Helvetica,Arial,sans-serif;color:#0B1020;margin:0;padding:40px;max-width:800px}h1{margin:0;font-size:28px}.head{display:flex;justify-content:space-between;border-bottom:4px solid #6D5DF6;padding-bottom:16px;margin-bottom:24px}.grid{display:flex;gap:40px;margin-bottom:24px}.grid>div{flex:1}.label{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#666}table{width:100%;border-collapse:collapse;margin-bottom:16px}th{text-align:left;background:#EEF1FA;padding:8px;font-size:12px}td{padding:8px;border-bottom:1px solid #e5e7f0}.r{text-align:right}.total{font-weight:700;font-size:18px;background:#6D5DF6;color:#fff}.note{font-size:12px;color:#555}@media print{body{padding:0}.noprint{display:none}}</style></head>
<body>
<p class="noprint"><button onclick="window.print()">🖨 <?php echo e(t('invoice.print'));?></button></p>
<div class="head"><div><h1>ToutPVR</h1><div><?php echo e($s['name']);?></div></div><div class="r"><h1><?php echo e(t('invoice.title'));?></h1><div><?php echo e($i['number']);?></div><div><?php echo e(t('invoice.date'));?> : <?php echo e(date_fmt($i['issued_at']));?></div></div></div>
<div class="grid">
  <div><div class="label"><?php echo e(t('invoice.seller'));?></div><div><?php echo nl2br(e($s['name']."\n".$s['address']));echo $s['vat']?'<br>'.e(t('invoice.vat_number')).' : '.e($s['vat']):'';?></div></div>
  <div><div class="label"><?php echo e(t('invoice.customer'));?></div><div><?php echo e($b['company']??'');echo!empty($b['company'])?'<br>':'';echo e($b['name']??'');?><br><?php echo e($b['address_line1']??'');echo!empty($b['address_line2'])?'<br>'.e($b['address_line2']):'';?><br><?php echo e(($b['postal_code']??'').' '.($b['city']??''));?><br><?php echo e($b['country']??'');echo!empty($b['vat_number'])?'<br>'.e(t('invoice.vat_number')).' : '.e($b['vat_number']):'';?><br><?php echo e($b['email']??'');?></div></div>
</div>
<table><thead><tr><th><?php echo e(t('invoice.description'));?></th><th class="r"><?php echo e(t('invoice.qty'));?></th><th class="r"><?php echo e(t('invoice.unit_price'));?></th><th class="r"><?php echo e(t('invoice.amount'));?></th></tr></thead><tbody>
<?php foreach($i['lines']as $line){?><tr><td><?php echo e($line['label']);?></td><td class="r"><?php echo(int)$line['qty'];?></td><td class="r"><?php echo money((int)$line['unit'],$cur);?></td><td class="r"><?php echo money((int)$line['total'],$cur);?></td></tr><?php }?>
<tr><td colspan="3" class="r"><?php echo e(t('invoice.subtotal'));?></td><td class="r"><?php echo money((int)$i['subtotal']-(int)$i['discount'],$cur);?></td></tr>
<tr><td colspan="3" class="r"><?php echo e(t('invoice.vat'));?> <?php echo rtrim(rtrim(number_format((float)$i['tax_rate'],2,'.',''),'0'),'.');?> %</td><td class="r"><?php echo money((int)$i['tax'],$cur);?></td></tr>
<tr class="total"><td colspan="3" class="r"><?php echo e(t('invoice.total'));?></td><td class="r"><?php echo money((int)$i['total'],$cur);?></td></tr>
</tbody></table>
<?php if((int)$i['reverse_charge']){?><p class="note"><?php echo e(t('invoice.reverse_charge_note'));?></p><?php }elseif((float)$i['tax_rate']==0){?><p class="note"><?php echo e(t('invoice.no_vat_note'));?></p><?php }?>
<p class="note"><?php echo e(t('invoice.paid_note'));?></p>
</body></html>
