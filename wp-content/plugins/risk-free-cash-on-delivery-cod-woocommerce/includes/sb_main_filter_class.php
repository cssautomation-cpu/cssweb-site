<?php
// to check wether accessed directly
if (!defined('ABSPATH')) {
	exit;
}

add_filter( 'woocommerce_available_payment_gateways', 'sb_available_payment_gateways_check' ,99);
if(!function_exists('sb_available_payment_gateways_check'))
{
	function sb_available_payment_gateways_check( $gateways ) {
		global $woocommerce;
		$output_data = sb_get_all_rules_and_data($woocommerce);
		
		if($output_data)
		{	
			if(!empty($output_data['advance1']))
			{
				//echo '<style>.sb_cod_table_bar{display:table;}</style>';
				if(isset($gateways['cod']))
				{
					unset($gateways['cod']);
				}
				return $gateways;
			}
			
				//echo '<style>.sb_cod_table_bar{display:none;}</style>';
			return $gateways;
		}
		//echo '<style>.sb_cod_table_bar{display:none;}</style>';
		unset($gateways['cod']);
		//remove_action('woocommerce_review_order_before_payment','sb_review_order_before_pay');
	   return $gateways;
	}
}
add_action('woocommerce_review_order_before_payment','sb_review_order_before_pay');

add_filter('gettext', 'sb_cod_wc_renaming_checkout_total', 20, 3);
if(!function_exists('sb_cod_wc_renaming_checkout_total'))
{
	function sb_cod_wc_renaming_checkout_total( $translated_text, $untranslated_text, $domain ) {

		if( !is_admin() && is_checkout()) {
			if( $untranslated_text == 'Total' )
				{
				$plugin_settings_data = get_option('sb_cod_advance_general_sett_customize');
				if($plugin_settings_data['sb_cod_title_text_total'])
				{
					$translated_text = $plugin_settings_data['sb_cod_title_text_total'];
				}
				
			}
		}

		return $translated_text;
		
	}
}
if(!function_exists('sb_review_order_before_pay'))
{
	function sb_review_order_before_pay()
	{
		global $woocommerce;
		
		//$output_data = sb_get_all_rules_and_data($woocommerce);
		//if($output_data)
		//{
		//if(!empty($output_data['advance1']) && !empty($output_data['advance2']))
		//{
		
		?>
		<script>
		jQuery( document ).ready(function() {
	   
		jQuery('#sb_select_cod_payment').click(function(){
			if(jQuery('#sb_select_cod_payment').prop('checked'))
			{
				jQuery('#sb_cod_option_selector_hidden').val('1');
			}else
			{
				jQuery('#sb_cod_option_selector_hidden').val('0');
			}
			jQuery( 'body' ).trigger( 'update_checkout' );	
		});
	});	
		</script>
		<style>
		#sb_select_cod_payment{
			
		}
		</style>
		<?php
		$plugin_settings_data = get_option('sb_cod_advance_general_sett_customize');
		$sb_cod_header_text = (isset($plugin_settings_data['sb_cod_title_text'])) ? $plugin_settings_data['sb_cod_title_text'] : 'Cash On Delivery';
		$sb_cod_desc_text = (isset($plugin_settings_data['sb_cod_title_textarea'])) ? $plugin_settings_data['sb_cod_title_textarea'] : 'Pay with cash upon delivery.';
		echo '
		<table class="cod_shop_table sb_cod_table_bar">
		<thead>
			<tr>
				<th class="product-name"><input type="checkbox" id="sb_select_cod_payment" class="sb_select_cod_payment" style="display: inline-block;
		font: normal normal normal 14px/1 FontAwesome;
		font-size: inherit;
		text-rendering: auto;
		content: \f10c;
		margin-right: 0.5407911001em;
		-webkit-transition: color, ease, .2s;
		transition: color, ease, .2s"/> '.$sb_cod_header_text.'</th>
			</tr>
		</thead>
		<tbody>
		<tr class="cart_item">
			<td class="product-name">
				'.$sb_cod_desc_text.'
			</td>
		</tr>
		</tbody>
	</table>';
	//}
	//}
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'sb_woo_add_cart_fee' );
if(!function_exists('sb_woo_add_cart_fee'))
{
function sb_woo_add_cart_fee($cart) {
  global $woocommerce;

if(is_checkout())
{  //print_r($woocommerce->cart);

if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
        return;
    }
	
	if ( isset( $_POST['post_data'] ) ) {
        parse_str( $_POST['post_data'], $post_data );
		
    } else {
        $post_data = $_POST; // fallback for final checkout (non-ajax)
    }
	
	    //$extracost = 25; // not sure why you used intval($_POST['state']) ?
       $output_data = sb_get_all_rules_and_data($woocommerce);
	   
	   if($output_data)
	   {
			
		if (isset($post_data['sb_cod_option_selector_hidden']) && $post_data['sb_cod_option_selector_hidden'] == '1') {
    	   if(isset($output_data['advance1']) && !empty($output_data['advance1']))
		   {
			   $fee = (float) apply_filters('sb_cod_addtional_advance_amount_value',$output_data['advance1'],$output_data,$woocommerce);
				$cart_total = $woocommerce->cart->get_subtotal() + $woocommerce->cart->get_subtotal_tax() + $woocommerce->cart->get_shipping_total() + $woocommerce->cart->get_shipping_tax();
				$cart_total  = (float) preg_replace( '/[^.\d]/', '', $cart_total  );
				if($output_data['advance2'] == 'per')
				{
				$fee = $cart_total - ($cart_total /100)*$fee;
				}else{
					// when the user add the advance amount if it's greater then product just the same amount will be taken
				if($cart_total>=$fee){
				    $fee = $cart_total - $fee;
			        }else{
				    $fee = 0;
			        }
				}
				$plugin_settings_data = get_option('sb_cod_advance_general_sett_customize');
				$sb_cod_header_text = (isset($plugin_settings_data['sb_cod_title_text_review'])) ? $plugin_settings_data['sb_cod_title_text_review'] : 'Your Total Order Amount: {order_total} Remaining Amount to Pay in COD: {cod_eligible}';
				$woo_symbol = get_woocommerce_currency_symbol();
				$sb_cod_header_text = str_replace('{order_total}',$woo_symbol.floatval($cart_total),$sb_cod_header_text);
				$sb_cod_header_text = str_replace('{cod_eligible}',$woo_symbol.$fee,$sb_cod_header_text);
				$sb_cod_header_text = apply_filters('sb_cod_addtional_advance_amount_text',$sb_cod_header_text,$output_data,$woocommerce);
			   
				$woocommerce->cart->add_fee( $sb_cod_header_text, $fee*(-1) );
		   }
			if(isset($output_data['fee']) && !empty($output_data['fee']))
		   {
			   $name = apply_filters('sb_cod_addtional_fee_amount_text',$output_data['name'],$output_data,$woocommerce);
			   $fee = (float) apply_filters('sb_cod_addtional_fee_amount_value',$output_data['fee'],$output_data,$woocommerce);
			   $woocommerce->cart->add_fee( __($name, ''), $fee );
		   }
		}
		   
	   }
	
  
  //$woocommerce->cart->add_fee( __('COD Payment', 'woocommerce'), -$woocommerce->cart->cart_contents_total );
}	
}
}

// Create hidden checkout field type
add_filter( 'woocommerce_form_field_hidden', 'sb_cod_create_checkout_hidden_field_type', 5, 4 );
if(!function_exists('sb_cod_create_checkout_hidden_field_type'))
{
	function sb_cod_create_checkout_hidden_field_type( $field, $key, $args, $value ){
		return '<input type="hidden" name="'.esc_attr($key).'" id="'.esc_attr($args['id']).'" value="'.esc_attr($args['default']).'" />';
	}
}
// Add custom hidden billing checkout field
add_filter( 'woocommerce_checkout_fields', 'sb_cod_custom_billing_fields' );
if(!function_exists('sb_cod_custom_billing_fields'))
{
	function sb_cod_custom_billing_fields( $fields ){

		$value = "0";

		$fields['billing']['sb_cod_option_selector_hidden'] = array(
			'type' => 'hidden',
			'label'     => __('Purchase Order Number', 'woocommerce'),
			'placeholder'  => _x('Purchase Order Number', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true,
			'default'   => $value, // The custom field value
		);
		return $fields;
	}
}
if(!function_exists('sb_get_all_rules_and_data'))
{
	function sb_get_all_rules_and_data($woocommerce)
	{
		$get_saved_data = get_option('sb_cod_advance_general_sett');
		$rules = isset($get_saved_data['rules']) ? $get_saved_data['rules'] : '';
		if(!empty($rules))
		{
			$any_role_apply = false;
			foreach($rules as $key => $value)
			{
				
				if(isset($value['enabled']) && $value['enabled'] == 'yes')
				{		
					return $value;
				}
			}
			
		}
		else
		{ 
			return false;
		}
	}
}