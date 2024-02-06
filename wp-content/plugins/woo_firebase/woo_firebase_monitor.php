<?php
namespace Woo\Firebase;

/**
 * send stats to firebase
 */
class Woo_Firebase_Monitor {
    
    public static function getInstance() {
        return new self();
    }

    /**
     * Initialization function.
     */
    public function init_hooks() {
        // Add hooks and actions
        //add_action('init', array($this, 'init'));

        //add_action('woocommerce_payment_complete', array($this,'send_new_order_data_to_server'), 10, 1);
        add_action('woocommerce_payment_complete_order_status', array($this,'send_new_order_data_to_server'), 10, 2);
        add_action('woocommerce_order_status_refunded', array($this, 'send_stats_on_refund'), 10, 1);
        add_action('woocommerce_order_status_cancelled', array($this, 'send_stats_on_cancel'), 10, 1);
        // add_action( 'woocommerce_order_status_changed', array($this,'send_new_order_data_to_server'),10,1);
        // add_action('woocommerce_new_order', array($this,'send_new_order_data_to_server'), 10, 1);
        add_filter( 'woocommerce_order_item_permalink', '__return_false' );
        add_action('phpmailer_init', array($this,'mailtrap'));
        add_filter('cron_schedules', array($this,'custom_cron_timer'));
    }


    /**
     * Send stats to Firebase on order completion.
     */
    public function send_stats_on_complete($order_id) {
        $this->send_stats_to_firebase($order_id, 'Completed');
    }
    /**
     * Send stats to Firebase on order refund.
     */
    public function send_stats_on_refund($order_id) {
        $this->send_stats_to_firebase($order_id, 'Refunded');
    }

    /**
     * Send stats to Firebase on order cancellation.
     */
    public function send_stats_on_cancel($order_id) {
        $this->send_stats_to_firebase($order_id, 'Cancelled');
    }

    /**
     * Send stats to Firebase.
     */
    private function send_stats_to_firebase($order_id, $status) {
        // Get the order object
        $order = wc_get_order($order_id);

        // Prepare data to send to Firebase
        $data_to_send = json_encode([
            'order_id' => $order_id,
            'status' => $status,
            'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'total_amount' => $order->get_total(),
        ]);

        //send data to Firebase
        $this->sending_data_to_firebase($data_to_send, $order_id); 
    }

    function send_new_order_data_to_server($status, $order_id) {
        // Get the order object
        $order = wc_get_order($order_id);
    
        // Get the order date
        $order_date = $order->get_date_created();
        // Check if the order was created today
        if ($order_date && $order_date->format('Y-m-d') === date('Y-m-d')) {

            $data_to_send = \Woo\Firebase\Woo_FireBase_Request::prepareData($order_id,$order);
             
            $this->sending_data_to_firebase($data_to_send, $order_id);
        }
    }

    function sending_data_to_firebase($data_to_send, $order_id=0, $collection=null) {
    
         $dbh = \Woo\Firebase\lib\get_firebase();  
         // Send data to the server
         $response = $dbh->addDocument(
             [   "id"=>$order_id?$order_id:uniqid(),
                 "type"=>"order",
                 "status"=>$collection?$collection:'new',
                 "date" => date('Y-m-d H:i:s'),
                 "data"=> $data_to_send
             ],$collection);
         
         // Check for errors in the response if needed
         if ($response) {
             error_log('Error sending order data to server: ' . $response->get_error_message());
         } else {
             // Log successful data submission
             error_log('Order data sent successfully to the server.');
         }
    }

    function mailtrap($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '311a9e05121c7b';
        $phpmailer->Password = '844f6c8ac5df60';
    }

    function custom_cron_timer($schedules){
        if(!isset($schedules["5min"])){
            $schedules["5min"] = array(
                'interval' => 5*60,
                'display' => __('Once every 5 minutes'));
        }
        if(!isset($schedules["3min"])){
            $schedules["3min"] = array(
                'interval' => 3*60,
                'display' => __('Once every 30 minutes'));
        }
        return $schedules;
    }
}

