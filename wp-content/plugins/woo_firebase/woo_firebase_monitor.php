<?php
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
        add_action('init', array($this, 'init'));
        add_action('woocommerce_order_status_refunded', array($this, 'send_stats_on_refund'), 10, 1);
        add_action('woocommerce_order_status_cancelled', array($this, 'send_stats_on_cancel'), 10, 1);
        add_action('woocommerce_new_order', array($this,'send_new_order_data_to_server'), 10, 1);
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
        $data_to_send = array(
            'order_id' => $order_id,
            'status' => $status,
            'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'total_amount' => $order->get_total(),
            // Add more data as needed
        );

        // Use wp_remote_post to send data to Firebase
        $response = wp_remote_post('https://your-firebase-endpoint.com', array(
            'body' => json_encode($data_to_send),
            'headers' => array('Content-Type' => 'application/json'),
        ));

        // Check for errors in the response if needed
        if (is_wp_error($response)) {
            error_log('Error sending data to Firebase: ' . $response->get_error_message());
        } else {
            // Log successful data submission
            error_log('Data sent successfully to Firebase.');
        }
    }

    function send_new_order_data_to_server($order_id) {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get the order date
        $order_date = $order->get_date_created();

        // Check if the order was created today
        if ($order_date && $order_date->format('Y-m-d') === date('Y-m-d')) {
            // Prepare data to send
            $order_data = $order->get_data();

            $data_to_send = array(
                'order_id' => $order_id,
                'billing_address' => $order_data['billing'],
                'shipping_address' => $order_data['shipping'],
                'order_items' => $order_data['line_items'],
            );

            // Send data to the server
            $response = wp_remote_post('https://your-server-endpoint.com', array(
                'body' => json_encode($data_to_send),
                'headers' => array('Content-Type' => 'application/json'),
            ));

            // Check for errors in the response if needed
            if (is_wp_error($response)) {
                error_log('Error sending order data to server: ' . $response->get_error_message());
            } else {
                // Log successful data submission
                error_log('Order data sent successfully to the server.');
            }
        }
    }
}

