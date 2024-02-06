<?php
/*
Plugin Name: Custom Woocommerce Firebase Monitor
Plugin URI: http://vsee.space
Description: Tracking Woocommerce orders firebase logger
Version: 1.0
Author: UI
Author URI: http://vsee.space
*/

 if(!defined( 'ABSPATH' ))   exit;

 check_woocommerce_dependency();

// First check system is in place 
if (!file_exists(plugin_dir_path(__FILE__) . 'woo_firebase_setup.php')
||  !file_exists(plugin_dir_path(__FILE__) . 'woo_firebase_report.php')
||  !file_exists(plugin_dir_path(__FILE__) . 'data/expanded-poet.json')
||  !file_exists(plugin_dir_path(__FILE__) . 'woo_firebase_report.php')
||  !file_exists(plugin_dir_path(__FILE__) . 'lib/woo_firebase.php'))
{
    throw new WP_Error("plugins files are missing");
}

define('WOO_FIREBASE_MONITOR_VERSION', '1.0');
define('WOO_FIREBASE_MONITOR_PATH', plugin_dir_path(__FILE__));

function activate_firebase_monitor() {
  // silence is golden    
}

register_activation_hook(__FILE__, 'activate_firebase_monitor');

function deactivate_firebase_monitor() {
    require_once plugin_dir_path(__FILE__) . 'woo_firebase_setup.php';
    \Woo\Firebase\Woo_Firebase_Setup::destroy();
}

register_deactivation_hook(__FILE__, 'deactivate_firebase_monitor');

function woo_firebase_monitor_init() {
    require_once plugin_dir_path(__FILE__) . 'woo_firebase_setup.php';
    \Woo\Firebase\Woo_Firebase_Setup::init();
}

woo_firebase_monitor_init();    

function check_woocommerce_dependency() {
    $plugins = get_option( 'active_plugins', array() );
    $site_plugins = is_multisite() ? (array) maybe_unserialize( get_site_option('active_sitewide_plugins' ) ) : array();

    if ( !in_array( 'woocommerce/woocommerce.php', $plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) 
    {     
        echo '<div class="error"><p>WooCommerce Firebase Monitor requires WooCommerce to be installed and activated.</p></div>';
        deactivate_firebase_monitor();
        return;
    }
}




