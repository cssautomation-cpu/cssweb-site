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

	$wc_main_settings['sb_cod_title_text'] = isset($_POST['sb_cod_title_text']) ? sanitize_text_field($_POST['sb_cod_title_text']) : 'Cash On Delivery';
	$wc_main_settings['sb_cod_title_textarea'] = isset($_POST['sb_cod_title_textarea']) ? sanitize_text_field($_POST['sb_cod_title_textarea']) : 'Pay with cash upon delivery.';
	$wc_main_settings['sb_cod_title_text_total'] = isset($_POST['sb_cod_title_text_total']) ? sanitize_text_field($_POST['sb_cod_title_text_total']) : 'Total';
	$wc_main_settings['sb_cod_title_text_review'] = isset($_POST['sb_cod_title_text_review']) ? sanitize_text_field($_POST['sb_cod_title_text_review']) : 'Your Total Order Amount: {order_total} Remaining Amount to Pay in COD: {cod_eligible}';
	
	update_option('sb_cod_advance_general_sett_customize',$wc_main_settings);
	
}
$plugin_settings_data = get_option('sb_cod_advance_general_sett_customize');
?>

<style>

</style>


<div style="width:100%;">
	<form method="post">
		<h3> <?php _e('Customization for COD Payment Method:',''); ?> </h3><br/>
		<table style="width:80%;font-size: 13px;">
			<tr valign="top">
				<td style="width:20%;font-weight:700;padding-left:5px;">
					<label for="sb_cod_title_text"><?php _e('Title',''); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input" size="60" type="text" name="sb_cod_title_text" id="sb_cod_title_text" style="padding:5px;" value="<?php echo (isset($plugin_settings_data['sb_cod_title_text'])) ? $plugin_settings_data['sb_cod_title_text'] : 'Cash On Delivery' ?>" placeholder=""> 
					</fieldset>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="width:20%;font-weight:700;padding-left:5px;">
					<label for="sb_cod_title_textarea"><?php _e('Description',''); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<textarea name="sb_cod_title_textarea" cols="100" id="sb_cod_title_textarea" style="padding:5px;"><?php echo (isset($plugin_settings_data['sb_cod_title_textarea'])) ? $plugin_settings_data['sb_cod_title_textarea'] : 'Pay with cash upon delivery.' ?></textarea>
					</fieldset>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="width:20%;font-weight:700;padding-left:5px;">
					<label for="sb_cod_title_text_total"><?php _e('Customize Text - Total',''); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input" size="60" type="text" name="sb_cod_title_text_total" id="sb_cod_title_text_total" style="padding:5px;" value="<?php echo (isset($plugin_settings_data['sb_cod_title_text_total'])) ? $plugin_settings_data['sb_cod_title_text_total'] : 'Total' ?>" placeholder=""> 
					</fieldset>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="width:20%;font-weight:700;padding-left:5px;">
					<label for="sb_cod_title_text_review"><?php _e('Customize Text - Order Review',''); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input" size="100" type="text" name="sb_cod_title_text_review" id="sb_cod_title_text_review" style="padding:5px;" value="<?php echo (isset($plugin_settings_data['sb_cod_title_text_review'])) ? $plugin_settings_data['sb_cod_title_text_review'] : 'Your Total Order Amount: {order_total} Remaining Amount to Pay in COD: {cod_eligible}' ?>" placeholder=""> 
					</fieldset>
					<small><strong>{order_total}</strong> - Short Code to show Order total.</small>
					<small><strong>{cod_eligible}</strong> - Short Code to show Eligible COD Amount.</small>
				</td>
				
			</tr>
			<tr valign="top">
				<td style="text-align: left;padding-left: 10px;" colspan="2">
					<input name="sb_update_settings" type="hidden" value="<?php echo wp_create_nonce('sb_update_settings'); ?>" />

					<button type='submit' name="sb_cod_save_button" class="button button-primary"><?php _e('Save and Go Live !','hit-tech-market-product-add'); ?></button>
				</td>
				
			</tr>
	</form>
</div>