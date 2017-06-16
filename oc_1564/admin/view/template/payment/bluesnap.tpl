<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a class="button" id="verify_settings_button"><?php echo $button_verify_settings;?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
	  <div id="tabs" class="htabs"><a href="#tab-settings">Settings</a><a href="#tab-transactions">Transactions</a></div>
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <div id="tab-transactions">
			Transactions will be added here
	    </div>
		<div id="tab-settings">
			<table class="form">
			  <tr>
				<td><?php echo $entry_mode; ?></td>
				<td>
					<select id="input-mode" name="bluesnap_mode">
						<option value="production" <?php if ($bluesnap_mode == "production") { echo " selected='selected' "; } ?>><?php echo $text_mode_production;?></option>
						<option value="sandbox" <?php if ($bluesnap_mode == "sandbox") { echo " selected='selected' "; } ?>><?php echo $text_mode_sandbox;?></option>
				  </select>
				 </td>
			  </tr>
			  <tr>
				<td>
					<?php echo $entry_username; ?>
					<br/> <span class="help"><?php echo $help_username;?></span>
				</td>
				<td><input type="text" style="width: 90%" name="bluesnap_username" placeholder="<?php echo $entry_username;?>" value="<?php echo $bluesnap_username; ?>" id="input-username" /></td>
			  </tr>			
			  <tr>
				<td>
					<?php echo $entry_password; ?>
					<br/> <span class="help"><?php echo $help_password;?></span>
				</td>
				<td><input type="text" name="bluesnap_password" placeholder="<?php echo $entry_password;?>" value="<?php echo $bluesnap_password; ?>" id="input-password" /></td>
			  </tr>			
			  <tr>
				<td>
					<?php echo $entry_description_prefix; ?>
					<br/> <span class="help"><?php echo $help_description_prefix;?></span>
				</td>
				<td><input type="text" name="bluesnap_description_prefix" placeholder="<?php echo $entry_description_prefix;?>" value="<?php echo $bluesnap_description_prefix; ?>" id="input-description_prefix" /></td>
			  </tr>
			   <tr>
				<td>
					<?php echo $entry_server_ip; ?>
					<br/> <span class="help"><?php echo $help_server_ip;?></span>
				</td>
				<td><input type="text" style="width:90%;" name="bluesnap_server_ip" disabled="disabled" placeholder="<?php echo $entry_server_ip;?>" value="<?php echo $bluesnap_server_ip; ?>" id="input-server_ip" /></td>
			  </tr>
			  <tr>
				  <td><?php echo $entry_debug_enabled; ?></td>
				  <td><?php if ($bluesnap_debug_enabled) { ?>
					<input type="radio" name="bluesnap_debug_enabled" value="1" checked="checked" />
					<?php echo $text_yes; ?>
					<input type="radio" name="bluesnap_debug_enabled" value="0" />
					<?php echo $text_no; ?>
					<?php } else { ?>
					<input type="radio" name="bluesnap_debug_enabled" value="1" />
					<?php echo $text_yes; ?>
					<input type="radio" name="bluesnap_debug_enabled" value="0" checked="checked" />
					<?php echo $text_no; ?>
					<?php } ?></td>
			  </tr>

			  <tr>
				<td>
					<?php echo $entry_total; ?>
					<br/> <span class="help"><?php echo $help_total;?></span>
				</td>
				<td><input type="text" name="bluesnap_total" placeholder="<?php echo $entry_total;?>" value="<?php echo $bluesnap_total; ?>" id="input-total" /></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_order_status; ?></td>
				<td><select id="input-order-status" name="bluesnap_order_status_id">
					<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $bluesnap_order_status_id) { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_geo_zone; ?></td>
				<td><select name="bluesnap_geo_zone_id">
					<option value="0"><?php echo $text_all_zones; ?></option>
					<?php foreach ($geo_zones as $geo_zone) { ?>
					<?php if ($geo_zone['geo_zone_id'] == $bluesnap_geo_zone_id) { ?>
					<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_status; ?></td>
				<td><select name="bluesnap_status">
					<?php if ($bluesnap_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_sort_order; ?></td>
				<td><input type="text" name="bluesnap_sort_order" value="<?php echo $bluesnap_sort_order; ?>" size="1" /></td>
			  </tr>
			</table>
		</div>
      </form>
    </div>
  </div>
</div>
<script>
$("#verify_settings_button").on("click", function() {
	var mode = $("#input-mode").val();
	var username = $("#input-username").val();
	var password = $("#input-password").val();
	$("#verify_settings_button i")
		.removeClass("fa-play")
		.addClass("fa-circle-o-notch").addClass("fa-spin")
		.attr("disabled", "disabled")
	;
	$.ajax({
		type: 'POST',
        url: 'index.php?route=payment/bluesnap/verify_settings&token=<?php echo $token;?>',
        sync: false,
        data : { mode : mode, username : username, password : password }, 
        success: function(data) {
			alert(data.result_msg);
		},
		complete: function() {
			$("#verify_settings_button i")
				.removeClass("fa-circle-o-notch").removeClass("fa-spin")
				.addClass("fa-play")
				.removeAttr("disabled")
			;
		}
	});
});

</script>

<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 

<?php echo $footer; ?>
