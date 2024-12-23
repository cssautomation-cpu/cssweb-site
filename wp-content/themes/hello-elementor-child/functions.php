<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

// Enqueue RTL stylesheet if it exists
if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ) {
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) ) {
            $uri = get_template_directory_uri() . '/rtl.css';
        }
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// Enqueue child theme's main CSS file
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// Enqueue JavaScript file script.js
if ( !function_exists( 'enqueue_child_theme_scripts' ) ):
    function enqueue_child_theme_scripts() {
        // Enqueue script.js located in the child theme directory
        wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/script.js', array(), null, true ); // true for loading in the footer
    }
endif;
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_scripts', 20 ); // 20 ensures it loads after CSS



// END ENQUEUE PARENT ACTION
function add_page_name_to_body_class( $classes ) {
    global $post;

    if ( isset( $post ) ) {
        $classes[] = 'page-name-' . $post->post_name;
    }

    return $classes;
}
add_filter( 'body_class', 'add_page_name_to_body_class' );

if(function_exists("register_sidebar")) {
    register_sidebar();
}

add_action('admin_menu', 'register_sales_by_agent_menu_page');

function register_sales_by_agent_menu_page() {
    add_submenu_page(
        'woocommerce',
        __('Sales by Agent', 'woocommerce'),
        __('Sales by Agent', 'woocommerce'),
        'manage_woocommerce',
        'sales-by-agent',
        'sales_by_agent_report_page'
    );
}
add_action( 'woocommerce_add_to_cart', function( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
    $product = wc_get_product( $product_id );
    $product_name = $product->get_name(); // Get the product name
    error_log( $product_name ); // Example action, like logging the product name
}, 10, 6 );

function sales_by_agent_report_page() {
    // Get the selected agent code from the query parameters
    $selected_agent_code = isset($_GET['agent_code']) ? sanitize_text_field($_GET['agent_code']) : '';

    ?>
    <div class="wrap">
        <h1><?php _e('Sales by Agent', 'woocommerce'); ?></h1>
        
        <form method="GET">
            <input type="hidden" name="page" value="sales-by-agent">
            <select name="agent_code" onchange="this.form.submit()">
                <option value=""><?php _e('Select Agent Code', 'woocommerce'); ?></option>
                <option value="Tarun001" <?php selected($selected_agent_code, 'JE017'); ?>>JE017</option>
                <option value="Jhony002" <?php selected($selected_agent_code, 'JE012'); ?>>JE012</option>
                <option value="Abhinav004" <?php selected($selected_agent_code, 'JE028'); ?>>JE028</option>
                <option value="Karuna005" <?php selected($selected_agent_code, 'JE029'); ?>>JE029</option>
            </select>
        </form>

        <?php
        if (!empty($selected_agent_code)) {
            display_sales_by_agent_report($selected_agent_code);
        }
        ?>
    </div>
    <?php
}







function display_sales_by_agent_report($agent_code) {
    global $wpdb;

    // Fetch products associated with the agent code
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT DISTINCT p.ID AS product_id, p.post_title AS product_name
        FROM {$wpdb->prefix}posts AS p
        JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON p.ID = order_items.order_id
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_1 ON order_items.order_item_id = order_itemmeta_1.order_item_id
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_agent_code ON order_items.order_item_id = order_itemmeta_agent_code.order_item_id
        JOIN {$wpdb->prefix}woocommerce_order_items AS order_items_2 ON order_items.order_id = order_items_2.order_id
        WHERE order_items_2.order_item_type = 'line_item'
        AND order_itemmeta_1.meta_key = '_product_id'
        AND order_itemmeta_agent_code.meta_key = 'agent_code'
        AND order_itemmeta_agent_code.meta_value = %s
        AND p.post_type = 'product'
    ", $agent_code));

    // Display the report
    if ($results) {
        echo '<h2>' . sprintf(__('Products for Agent: %s', 'woocommerce'), $agent_code) . '</h2>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>' . __('Product ID', 'woocommerce') . '</th><th>' . __('Product Name', 'woocommerce') . '</th></tr></thead>';
        echo '<tbody>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->product_id) . '</td>';
            echo '<td>' . esc_html($row->product_name) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>' . __('No products found for this agent.', 'woocommerce') . '</p>';
    }
}


//session user for not logged in add to cart

// Step 1: Ensure WooCommerce sessions are initialized
add_action('init', function() {
    if (!WC()->session) {
        WC()->session = new WC_Session_Handler();
        WC()->session->init();
    }
});

// Step 2: Save the cart contents to a unique transient when a product is added
add_action('woocommerce_add_to_cart', function() {
    // Only for guest users
    if (!is_user_logged_in()) {
        // Get the current cart contents
        $cart_contents = WC()->cart->get_cart();
        
        // Generate a unique key for the cart
        $unique_key = uniqid('cart_', true);
        
        // Save the cart contents in a transient (valid for 1 hour)
        set_transient('cart_' . $unique_key, $cart_contents, HOUR_IN_SECONDS);
        
        // Store the unique link in the session for display later
        WC()->session->set('restore_cart_link', home_url('/restore-cart/' . $unique_key));
    }
});

// Step 3: Create a custom endpoint to handle cart restoration
add_action('init', function() {
    // Add rewrite rule for the custom endpoint
    add_rewrite_rule('^restore-cart/([^/]+)/?', 'index.php?restore_cart_key=$matches[1]', 'top');
    add_rewrite_tag('%restore_cart_key%', '([^&]+)');
});

// Step 4: Process the cart restoration when the unique link is accessed
add_action('template_redirect', function() {
    $restore_cart_key = get_query_var('restore_cart_key');
    if ($restore_cart_key) {
        // Retrieve the saved cart contents from the transient
        $cart_data = get_transient('cart_' . $restore_cart_key);
        if ($cart_data) {
            // Clear the current cart and restore the saved cart
            WC()->cart->empty_cart();
            foreach ($cart_data as $cart_item) {
                WC()->cart->add_to_cart($cart_item['product_id'], $cart_item['quantity'], $cart_item['variation_id'], $cart_item['variation'], $cart_item['cart_item_data']);
            }
            // Delete the transient after restoration
            delete_transient('cart_' . $restore_cart_key);
            // Redirect to the cart page
            wp_redirect(wc_get_cart_url());
            exit;
        } else {
            // If the transient is not found, redirect to the home page
            wp_redirect(home_url());
            exit;
        }
    }
});

// Step 5: Display the unique cart link on the cart page for guest users
add_action('woocommerce_before_cart', function() {
    // Only for guest users
    if (!is_user_logged_in()) {
        // Retrieve the restore link from the session
        $restore_cart_link = WC()->session->get('restore_cart_link');
        if ($restore_cart_link) {
            echo '<div class="woocommerce-info">';
            echo 'Save or share your cart with this unique link: ';
            echo '<a href="' . esc_url($restore_cart_link) . '">' . esc_url($restore_cart_link) . '</a>';
            echo '</div>';
        }
    }
});


//new techs

// add_action('woocommerce_add_to_cart', function() {
//     if (!is_user_logged_in()) {
//         $cart_contents = WC()->cart->get_cart();
//         $unique_key = uniqid('cart_', true);
//         set_transient('cart_' . $unique_key, $cart_contents, HOUR_IN_SECONDS);
//         WC()->session->set('restore_cart_link', home_url('/restore-cart/' . $unique_key));
//     }
// });
// add_action('wp_enqueue_scripts', function() {
//     // Enqueue a custom script for the popup
//     wp_enqueue_script('cart-popup-script', get_template_directory_uri() . '/js/cart-popup.js', ['jquery'], '1.0', true);

//     // Pass the cart link to the script
//     if (isset(WC()->session) && !is_user_logged_in()) {
//         $restore_cart_link = WC()->session->get('restore_cart_link');
//         wp_localize_script('cart-popup-script', 'cartPopupData', [
//             'restoreCartLink' => $restore_cart_link
//         ]);
//     }
// });


//end new techs



//End of session user for not logged in add to cart




// Add the agent code select field to the checkout form
add_filter('woocommerce_checkout_fields', 'add_agent_code_select_field');

function add_agent_code_select_field($fields) {
    $fields['billing']['billing_agent_code'] = array(
        'type'        => 'select',
        'label'       => __('Agent Code'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'options'     => array(
            ''           => __('Select Agent Code', 'woocommerce'),
            'JE017'   => 'JE017',
            'JE012'   => 'JE012',
            'JE028' => 'JE028',
            'JE029'  => 'JE029'
        )
    );
    return $fields;
}

// Save the agent code field
add_action('woocommerce_checkout_update_order_meta', 'save_agent_code_field');
function save_agent_code_field($order_id) {
    if (!empty($_POST['billing_agent_code'])) {
        update_post_meta($order_id, 'agent_code', sanitize_text_field($_POST['billing_agent_code']));
    }
}

// Display agent code in the order edit page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_agent_code_in_admin_order_meta', 10, 1);
function display_agent_code_in_admin_order_meta($order) {
    $agent_code = get_post_meta($order->get_id(), 'agent_code', true);
    if ($agent_code) {
        echo '<p><strong>' . __('Agent Code') . ':</strong> ' . $agent_code . '</p>';
    }
}

// Add a filter to search orders by agent code
// // Add a filter to search orders by agent code
add_action('restrict_manage_posts', 'filter_orders_by_agent_code', 20);
function filter_orders_by_agent_code() {
    global $typenow, $wp_query;
    if ('shop_order' === $typenow) {
        $selected_agent_code = isset($_GET['_agent_code']) ? $_GET['_agent_code'] : '';

        ?>
        <select name="_agent_code" id="dropdown_agent_code">
            <option value=""><?php _e('All Agent Codes', 'woocommerce'); ?></option>
            <option value="JE017" <?php selected($selected_agent_code, 'JE017'); ?>>JE017</option>
            <option value="JE012" <?php selected($selected_agent_code, 'JE012'); ?>>JE012</option>
            <option value="JE028" <?php selected($selected_agent_code, 'JE028'); ?>>JE028</option>
            <option value="JE029" <?php selected($selected_agent_code, 'JE029'); ?>>JE029</option>
        </select>
        <?php
    }
}

add_filter('request', 'filter_orders_by_agent_code_query');
function filter_orders_by_agent_code_query($vars) {
    global $typenow;
    if ('shop_order' === $typenow && isset($_GET['_agent_code']) && !empty($_GET['_agent_code'])) {
        $vars['meta_query'] = isset($vars['meta_query']) ? $vars['meta_query'] : [];
        $vars['meta_query'][] = array(
            'key' => 'agent_code',
            'value' => sanitize_text_field($_GET['_agent_code']),
            'compare' => 'LIKE'
        );
    }
    return $vars;
}

//blog posts

function display_category_posts_with_read_more($atts) {
    $atts = shortcode_atts(
        array(
            'category' => '',
            'posts_per_page' => '5',
        ),
        $atts,
        'category_posts'
    );

    $query = new WP_Query(array(
        'category_name' => $atts['category'],
        'posts_per_page' => $atts['posts_per_page']
    ));

    $output = '<div class="category-posts">';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $output .= '<div class="post">';
            if (has_post_thumbnail()) {
                $output .= '<div class="post-thumbnail">' . get_the_post_thumbnail() . '</div>';
            }
            $output .= '<h2>' . get_the_title() . '</h2>';
            
            $post_content = wp_trim_words( get_the_content(), 32    , '...' );
            $output .= '<p>' . $post_content . '</p>';
            
            $output .= '<a href="' . get_permalink() . '" class="read-more">Read More</a>';
            $output .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No posts found in this category.</p>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('category_posts', 'display_category_posts_with_read_more');



// Phone Number

// Add mobile number field to the registration form
function custom_woocommerce_registration_form() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_billing_phone"><?php _e( 'Mobile Number', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" pattern="\d{10}" title="Please enter exactly 10 digits" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) echo esc_attr( wp_unslash( $_POST['billing_phone'] ) ); ?>" />
    </p>
    <?php
}
add_action( 'woocommerce_register_form_start', 'custom_woocommerce_registration_form' );

// Validate the mobile number field
function custom_woocommerce_registration_errors( $errors, $username, $email ) {
    if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
        $errors->add( 'billing_phone_error', __( 'Mobile number is required!', 'woocommerce' ) );
    } elseif ( isset( $_POST['billing_phone'] ) && ! preg_match( '/^\d{10}$/', $_POST['billing_phone'] ) ) {
        $errors->add( 'billing_phone_error', __( 'Mobile number must be exactly 10 digits!', 'woocommerce' ) );
    }
    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'custom_woocommerce_registration_errors', 10, 3 );

// Save the mobile number field
function custom_woocommerce_save_account_details( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'custom_woocommerce_save_account_details' );

// Display the mobile number field in account details
function custom_woocommerce_edit_account_form() {
    $user = wp_get_current_user();
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_phone"><?php _e( 'Mobile Number', 'woocommerce' ); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( get_user_meta( $user->ID, 'billing_phone', true ) ); ?>" pattern="\d{10}" title="Please enter exactly 10 digits" />
    </p>
    <?php
}
add_action( 'woocommerce_edit_account_form', 'custom_woocommerce_edit_account_form' );

// Save the mobile number field in account details
function custom_woocommerce_save_account_details_account_details( $user_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}
add_action( 'woocommerce_save_account_details', 'custom_woocommerce_save_account_details_account_details' );


//Date of Birth

// Add Date of Birth field to registration form
function add_date_of_birth_field() {
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_birthday"><?php _e( 'Date of Birth', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="date" class="input-text" name="birthday" id="reg_birthday" value="<?php if ( ! empty( $_POST['birthday'] ) ) echo esc_attr( wp_unslash( $_POST['birthday'] ) ); ?>" />
    </p>
    <?php
}
add_action( 'woocommerce_register_form_start', 'add_date_of_birth_field' );

// Validate Date of Birth field
function validate_date_of_birth_field( $username, $email, $validation_errors ) {
    if ( isset( $_POST['birthday'] ) && empty( $_POST['birthday'] ) ) {
        $validation_errors->add( 'birthday_error', __( '<strong>Error</strong>: Date of Birth is required!', 'woocommerce' ) );
    }
}
add_action( 'woocommerce_register_post', 'validate_date_of_birth_field', 10, 3 );

// Save Date of Birth field
function save_date_of_birth_field( $customer_id ) {
    if ( isset( $_POST['birthday'] ) ) {
        update_user_meta( $customer_id, 'birthday', sanitize_text_field( $_POST['birthday'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'save_date_of_birth_field' );

// Display Date of Birth field in account details
function show_date_of_birth_in_account( $user ) {
    ?>
    <h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="birthday"><?php _e( 'Date of Birth', 'woocommerce' ); ?></label></th>
            <td>
                <input type="date" name="birthday" id="birthday" value="<?php echo esc_attr( get_user_meta( $user->ID, 'birthday', true ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'show_date_of_birth_in_account' );
add_action( 'edit_user_profile', 'show_date_of_birth_in_account' );

// Save Date of Birth field in account details
function save_date_of_birth_in_account( $user_id ) {
    if ( isset( $_POST['birthday'] ) ) {
        update_user_meta( $user_id, 'birthday', sanitize_text_field( $_POST['birthday'] ) );
    }
}
add_action( 'personal_options_update', 'save_date_of_birth_in_account' );
add_action( 'edit_user_profile_update', 'save_date_of_birth_in_account' );





////////////////////////////////////////////////


// Add custom "Notify Me" button when product is out of stock
add_filter('woocommerce_product_add_to_cart_text', 'replace_read_more_with_notify_phone', 10, 2);

function replace_read_more_with_notify_phone($text, $product) {
    if (!$product->is_in_stock()) {
        // Change the "Add to Cart" button text to "Get Notified" for out-of-stock products
        $text = 'Get Notified';
    }
    return $text;
}





