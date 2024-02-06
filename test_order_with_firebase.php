<?php
include 'wp-load.php';
include __DIR__.'/wp-content/plugins/woo_firebase/vendor/autoload.php';
    try {
            global $woocommerce;
            $address = array(
                'first_name' => 'Tanmoy',
                'last_name'  => 'San',
                'company'    => 'Automattic',
                'email'      => 'no@spam.com',
                'phone'      => '123-123-123',
                'address_1'  => '123 Main Woo st.',
                'address_2'  => '100',
                'city'       => 'San Francisco',
                'state'      => 'CA',
                'postcode'   => '92121',
                'country'    => 'US'
            );
            
            // Now we create the order
            $order = wc_create_order();
            // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
            $order->add_product( wc_get_product( 113 ), 1); // Use the product IDs to add
            
            // Set addresses
            $order->set_address( $address, 'billing' );
            $order->set_address( $address, 'shipping' );
            
            // Set payment gateway
            $payment_gateways = WC()->payment_gateways->payment_gateways();

            $order->set_payment_method( $payment_gateways['cod'] );
            
            // Calculate totals
            $order->calculate_totals();
            WC()->session->set( 'order_awaiting_payment', false );
            $order->set_status( 'completed');
            
            $order->save();

        } catch(\Throwable $e)
        {
           var_export([$e->getMessage(),$e->getFile(),$e->getLine()]);
        }
