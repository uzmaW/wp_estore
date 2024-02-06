<?php
namespace Woo\Firebase;

class Woo_Firebase_Report {

    // Function to send all orders to the server
    public function send_all_orders_to_server() {
        // Get all orders placed today
        $orders = wc_get_orders(array(
            'date_created' => '>=' . (time() - 86400), // Orders created in the last 24 hours
            'status' => 'completed', // Adjust as needed based on your order status
        ));

        foreach ($orders as $order) {
            // Prepare data to send
            $order_data = $order->get_data();

            // Adjust the data structure as needed
            $data_to_send[] = [
                'order_id' => $order_data['id'],
                'billing_address' => $order_data['billing'],
                'shipping_address' => $order_data['shipping'],
                'order_items' => $order_data['line_items'],
            ];            
        }
        // Send data to the server
        $this->sending_data_to_firebase(json_decode($data_to_send), $order_id,'dayend');
               
        // Check for errors in the response if needed
        if (is_wp_error($response)) {
            error_log('Error sending order data to server: ' . $response->get_error_message());
        } else {
            // Log successful data submission
            error_log('Order data sent successfully to the server.');
        }

    }
    
    public static function getInstance() {
        return new self();
    }
}
