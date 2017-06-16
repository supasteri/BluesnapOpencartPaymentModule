<h2><?php echo $text_payment_info; ?></h2>
<?php foreach ($bluesnap_audit_entries as $i=>$bluesnap_audit_entry) {
	$id = $bluesnap_audit_entry['bluesnap_audit_id'];
?>
	<h3><?php echo $text_audit_entry_title;?><?php echo $id;?></h3>
	<table border="1">
	<?php foreach ($bluesnap_audit_entry as $key=>$value) {?>
		<tr><th><?php echo $key;?></th><td><pre><?php echo $value;?></pre></td></th></tr>
	<?php }?>
	</table>
<?php }?>
