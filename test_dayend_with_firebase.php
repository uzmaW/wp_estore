<?php
include 'wp-load.php';
include __DIR__.'/wp-content/plugins/woo_firebase/vendor/autoload.php';
    try {
            global $woocommerce;
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

            $dbh = \Woo\Firebase\lib\get_firebase();  
            // Send data to the server
            $response = $dbh->addDocument(
                [   "id"=>uniqid(),
                    "type"=>"order",
                    "status"=>'dayend',
                    "date" => date('Y-m-d H:i:s'),
                    "data"=> json_encode($data_to_send)
                ],'dayend');
            
            // Check for errors in the response if needed
            if ($response) {
                error_log('Error sending order data to server: ' . $response->get_error_message());
            } else {
                // Log successful data submission
                error_log('Order data sent successfully to the server.');
            }

        } catch(\Throwable $e)
        {
           var_export([$e->getMessage(),$e->getFile(),$e->getLine()]);
        }
