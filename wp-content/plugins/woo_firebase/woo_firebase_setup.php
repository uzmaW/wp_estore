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

    public  function installFirebaseLibrary() {
        // This function will be called when the class is not found and the library needs to be installed asynchronously
        $descriptorspec = array(
            0 => array("pipe", "r"),       // stdin
            1 => array("pipe", "w"),       // stdout
            2 => array("pipe", "w"),       // stderr
        );

        $process = proc_open('composer require kreait/firebase-php', $descriptorspec, $pipes);

        if (is_resource($process)) {
            fclose($pipes[0]);             // Close the input pipe

            // Close the output and error pipes
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Wait for the process to complete
            while (proc_get_status($process)['running']) {
                usleep(100000); // Sleep for 0.1 seconds
            }

            
            proc_close($process);          // Close the process

            // Display a success message
            // echo '<div class="notice notice-success is-dismissible"><p>Firebase library installed successfully.</p></div>';

        
            // Reload the page to show the admin notice
            // wp_redirect(admin_url('admin.php?page=woo_firebase_setup'));
            // exit;
        }
    }
    
    public static function getInstance() {    
        if(self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }
    
    public function setupDayEndSchedule() {
        // Schedule the event to run daily at midnight
        if (!wp_next_scheduled('send_orders_daily')) {
            wp_schedule_event(strtotime('midnight'), 'daily', 'send_all_orders_daily');
        }

        // Hook the scheduled event to the function that sends all orders to the server
        add_action('send_all_orders_daily', array(Woo_Firebase_Report::getInstance(), 'send_all_orders_to_server'));
    }

    public function setupFirebaseMonitor() {
        // Check if the class exists
        if (!class_exists('Woo_Firebase_Monitor')) {
            // Install the library asynchronously
            add_action('admin_notices', array($this, 'installFirebaseLibrary'));
        }
        Woo_Firebase_Monitor::getInstance()->init_hooks(); // Create an instance of the class

    }
    
    public function setupSystemFiles() {
        // Include Composer autoloader
        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
        require_once plugin_dir_path(__FILE__) . 'woo_firebase_report.php';
        require_once plugin_dir_path(__FILE__) . 'woo_firebase_monitor.php';
        // Install setup files
        if (!class_exists('Kreait\Firebase\Factory')) {
            // The class from kreait/firebase-php is not found, so try to install the library asynchronously
            $this->getInstance()->installFirebaseLibrary();
        }
       
       

    }
}
