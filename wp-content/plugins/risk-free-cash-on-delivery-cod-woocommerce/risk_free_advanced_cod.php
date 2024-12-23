<?php
/*
Plugin Name: Risk Free Cash On Delivery (COD) - WooCommerce
Plugin URI: #
Description: Risk Free Advanced COD method for Woocommerce
Version: 1.0.4
Author: EverythingWP
Author URI: https://www.everythingwp.co/wordpress-plugins/
domain : sb_risk_free_cod
*/

// to check wether accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if(!defined('SB_RISK_FREE_COD_PATH'))
{
  define('SB_RISK_FREE_COD_PATH', plugin_dir_path(__FILE__));
}
if(!defined('SB_RISK_FREE_COD_PATH_URL'))
{
  define('SB_RISK_FREE_COD_PATH_URL', plugin_dir_url(__FILE__));
}
if(!class_exists('SB_Risk_Free_COD'))
{
  class SB_Risk_Free_COD
  {
    function __construct()
    {
     add_action('init', array($this, 'sb_risk_free_init_language_domain'));
     add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'sb_risk_free_plugin_action_links'));
     add_action('admin_menu', array($this, 'sb_risk_free_plugin_menu'));
     add_action('admin_enqueue_scripts', array($this, 'sb_risk_free_plugin_scripts'));
	include_once('includes/sb_main_filter_class.php');
   }
	

   function sb_risk_free_plugin_scripts()
   {
      wp_enqueue_script("jquery");
      wp_enqueue_script('wc-enhanced-select');
      wp_enqueue_style('woocommerce_admin_styles');
   }
   function sb_risk_free_init_language_domain()
   {
    //load_plugin_textdomain('sb_risk_free_cod', false, HIT_ADS_NET_DIR_PATH . '/wpml');
  }
  function sb_risk_free_plugin_action_links($links)
  {
    $plugin_links = array(
      '<a href="' . wp_nonce_url(admin_url('admin.php?page=sb_risk_free_cod')) . '" style="color:blue;">' . __('Setup', 'sb_risk_free_cod') . '</a>',
      '<a href="#" style="color:blue;">' . __('Support', 'sb_risk_free_cod') . '</a>',
      );
    return array_merge($plugin_links, $links);
  }
  function sb_risk_free_plugin_menu()
  {
    add_submenu_page('woocommerce',__('Risk Free COD', 'sb_risk_free_cod'), __('Risk Free COD', 'sb_risk_free_cod'), 'manage_woocommerce', 'sb_risk_free_cod', array($this, 'sb_product_risk_free_settings_page'),'dashicons-money',51);

  }
  function sb_product_risk_free_settings_page()
  {

    $tab = (!empty($_GET['subtab'])) ? esc_attr($_GET['subtab']) : 'general';
                echo '<br/><hr />
<center>
  <h1 > '.__('Risk Free Cash On Delivery (COD) - WooCommerce','sb_risk_free_cod').' </h1>
  <h6>powerd by <a href="https://www.everythingwp.co/wordpress-plugins/" target="_blank">everthingwp</a></h6>
</center>

<hr>';
                echo '
                <div class="wrap">
                    
                    <hr class="wp-header-end">';
                $this->sb_tab_content_fetch($tab);
                switch ($tab) {
                    case "general":
                        echo '<div class="table-box table-box-main" id="available_offers_section" style="margin-top: 0px;
						border: 1px solid #ccc;border-top: unset !important;padding: 5px;">';
						require_once('includes/views/sb_cod_settings_page.php');
                        echo '</div>';
                        break;
						case "advanced":
                        echo '<div class="table-box table-box-main" id="available_offers_section" style="margin-top: 0px;
						border: 1px solid #ccc;border-top: unset !important;padding: 5px;">';
						require_once('includes/views/sb_cod_advanced_settings_page.php');
                        echo '</div>';
                        break;
                    case "proversion":
                        echo '<div class="table-box table-box-main" id="available_offers_section" style="margin-top: 0px;
						border: 1px solid #ccc;border-top: unset !important;padding: 5px;">';
                        echo '</div>';
                        break;
                    
                }
                echo '
                </div>';
               


       }
       function sb_tab_content_fetch($current = 'general')
       {
        $tabs = array(
                    'general' => __("<span class='dashicons dashicons-admin-settings' style='font-size: 18px;vertical-align: middle;'></span> General", 'sb_risk_free_cod'),
                    'advanced' => __("<span class='dashicons dashicons-admin-generic' style='font-size: 18px;vertical-align: middle;'></span> Advanced", 'sb_risk_free_cod'),
                    'proversion' => __("<span class='dashicons dashicons-admin-home' style='font-size: 18px;vertical-align: middle;' ></span> Pro Version", 'sb_risk_free_cod')
                    
                );
                $html = '<h2 class="nav-tab-wrapper">';
                foreach ($tabs as $tab => $name) {
                    $class = ($tab == $current) ? 'nav-tab-active' : '';
                    $style = ($tab == $current) ? 'border-bottom: 1px solid transparent !important;' : '';
                    $html .= '<a style="text-decoration:none !important;' . $style . '" class="nav-tab ' . $class . '" href="?page=sb_risk_free_cod&subtab=' . $tab . '">' . $name . '</a>';
                }
                $html .= '</h2>';
                echo $html;
            }
}
}

function sb_Risk_Free_cod_func() {
      new SB_Risk_Free_COD();
   }

 add_action("init", 'sb_Risk_Free_cod_func', 99);

