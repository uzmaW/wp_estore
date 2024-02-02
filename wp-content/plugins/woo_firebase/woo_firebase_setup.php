<?php

/** Plugin setup */
class Woo_Firebase_Setup {
    
    public static $instance;


    public static function init() {
        $instance = self::getInstance();
        
        $instance->setupSystemFiles();     // setup system files
        $instance->setupFirebaseMonitor(); // setup firebase log
        $instance->setupDayEndSchedule();  // setup dayend schedule cron | firebase log

    }

    public function installFirebaseLibrary() {
            system('composer require kreait/firebase-php');        
    }
    
    public static function getInstance() {    
        if(self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }
    
    public function setupDayEndSchedule() {
        // Schedule the event to run daily at midnight
        if (!wp_next_scheduled('send_all_orders_daily')) {
            wp_schedule_event(strtotime('midnight'), 'daily', 'send_all_orders_daily');
        }

        // Hook the scheduled event to the function that sends all orders to the server
        add_action('send_all_orders_daily', array(Woo_Firebase_Report::getInstance(), 'send_all_orders_to_server'));
    }

    public function setupFirebaseMonitor() {
        // Check if the class exists
        if (!class_exists('Woo_Firebase_Monitor')) {
            // Install the library asynchronously
            // add_action('admin_notices', array($this, 'installFirebaseLibrary'));
        }
        Woo_Firebase_Monitor::getInstance()->init_hooks(); // Create an instance of the class

    }
    
    public function setupSystemFiles() {
        try {
            // Include Composer autoloader
            $plug_dir=plugin_dir_path(__FILE__);
            
            if(file_exists($plug_dir . 'vendor/autoload.php'))
                require_once $plug_dir . 'vendor/autoload.php';
            if(file_exists($plug_dir. 'woo_firebase_report.php'))
                require_once $plug_dir . 'woo_firebase_report.php';
            require_once $plug_dir . 'woo_firebase_monitor.php';
            
            // Install setup files
            if (!class_exists('Kreait\Firebase\Factory')) {
                // The class from kreait/firebase-php is not found, so try to install the library asynchronously    
                //$this->installFirebaseLibrary();
            }
        } catch (Exception $e) {
            // Handle the exception
            wp_die('Error: ' . $e->getMessage());
            //new WP_Error('Error: ' . $e->getMessage());
        }   
    }

    public static function destroy() {
        wp_clear_scheduled_hook('send_all_orders_daily');
    }
}
