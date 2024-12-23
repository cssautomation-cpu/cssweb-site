<?php
// to check wether accessed directly
if (!defined('ABSPATH')) {
	exit;
}
$wc_main_settings = array();
if(isset($_POST['sb_cod_save_button']))
{
	if (!isset($_POST['sb_update_settings'])) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");
	if (!wp_verify_nonce($_POST['sb_update_settings'],'sb_update_settings')) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");

	$wc_main_settings['sb_cod_enable_disable'] = isset($_POST['sb_cod_enable_disable']) ? 'yes' : 'no';
	$rule = array();
	$rule_data = $_POST['rules_name'];

	if(!empty($rule_data))
	{
	$i= 1;
	foreach ( $rule_data as $key => $value) {
		$rule_id = $_POST['rules_id'][$key];
		
		if(!empty($_POST['rules_name'][$key]))
		{
		$rule_name = empty($_POST['rules_name'][$key]) ? 'New Rule' : sanitize_text_field($_POST['rules_name'][$key]);
		$rules_advance1 = empty($_POST['rules_advance1'][$key]) ? '' : sanitize_text_field($_POST['rules_advance1'][$key]); 
		$rules_advance2 = empty($_POST['rules_advance2'][$key]) ? '' : sanitize_text_field($_POST['rules_advance2'][$key]); 
		$rules_fee = empty($_POST['rules_fee'][$key]) ? '' : sanitize_text_field($_POST['rules_fee'][$key]); 
		$rules_enabled = isset($_POST['rules_enabled'][$key]) ? true : false; 
		$rule[$i] = array(
				'id' => $rule_id,
				'name' => $rule_name,
				'advance1' => $rules_advance1,
				'advance2' => $rules_advance2,
				'fee' => $rules_fee,
				'enabled' => $rules_enabled,
				);
		}
		$i++;
	}
	}
	$wc_main_settings['rules'] = $rule;
	update_option('sb_cod_advance_general_sett',$wc_main_settings);
	
}
$get_saved_data = get_option('sb_cod_advance_general_sett');
$this->rules = isset($get_saved_data['rules']) ? $get_saved_data['rules'] : '';
?>

<style>

</style>


<div style="width:100%;">
	<form method="post">
		<h3> <?php _e('Configuration for Show / Hide Payment Method:',''); ?> </h3><br/>
		<table style="width:100%;font-size: 13px;">
			<tr valign="top">
				<td style="width:450px;font-weight:700;">
					<label for="sb_cod_enable_disable"><?php _e('Enable / Disable Risk Free COD for WooCommerce',''); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input " type="checkbox" name="sb_cod_enable_disable" id="sb_cod_enable_disable" style="" value="yes" <?php echo (isset($get_saved_data['sb_cod_enable_disable']) && $get_saved_data['sb_cod_enable_disable'] !='no') ? 'checked' : '' ?> placeholder=""> <?php _e('Enable!',''); ?> 
					</fieldset>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="text-align: left;padding-left: 10px;" colspan="2">
					<?php include_once('sb_cod_payment_option.php'); ?>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="text-align: left;padding-left: 10px;" colspan="2">
					<input name="sb_update_settings" type="hidden" value="<?php echo wp_create_nonce('sb_update_settings'); ?>" />

					<br/><button type='submit' name="sb_cod_save_button" class="button button-primary"><?php _e('Save and Go Live !','hit-tech-market-product-add'); ?></button>
				</td>
				
			</tr>
	</form>
</div>