<?php
/*
Plugin Name: Custom Woocommerce Firebase Monitor
Plugin URI: http://vsee.space
Description: Tracking Woocommerce orders firebase logger
Version: 1.0
Author: UI
Author URI: http://vsee.space
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
    
    //var_dump(Woo_Firebase_Setup::$instance);
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger);
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db);
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db->getReference('orders'));
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db->getReference('orders')->getSnapshot()->getValue());
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db->getReference('orders')->getSnapshot()->getValue());
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db->getReference('orders')->getSnapshot()->getValue());
    //var_dump(Woo_Firebase_Setup::$instance->firebase_logger->firebase_db->getReference('orders')->getSnapshot()->getValue());
    Woo_Firebase_Setup::destroy();
}
register_deactivation_hook(__FILE__, 'deactivate_firebase_monitor');

function woo_firebase_monitor_init() {
    require_once plugin_dir_path(__FILE__) . 'woo_firebase_setup.php';
    Woo_Firebase_Setup::init();
}

woo_firebase_monitor_init();    





