<?php
namespace Woo\Firebase;

use MrShan0\PHPFirestore\Fields\FirestoreObject;

class Woo_Firebase_Request
{
    public static function prepareData($order_id,$order)
    {
        $order_data = [    
                'order_id'=>$order_id,
                'order_number'=>$order->get_order_number(),
                'order_date'=>$order->get_date_created(),
                'order_status'=>$order->get_status(),
                'order_currency'=>$order->get_currency(),
                'order_total'=>$order->get_total(),
                'order_shipping'=>$order->get_shipping_total(),
                'order_tax'=>$order->get_total_tax(),
                'order_discount'=>$order->get_total_discount(),
                'order_items'=>$order->get_item_count(),
                'order_billing_first_name'=>$order->get_billing_first_name(),
                'order_billing_last_name'=>$order->get_billing_last_name(),
                'order_billing_company'=>$order->get_billing_company(),
                'order_billing_address_1'=>$order->get_billing_address_1(),
        ];    
        
        // Prepare data to send
        foreach ($order->get_items() as $item_key => $item ):

            //$item_id = $item->get_id();
        
            $product = wc_get_product($item->get_product_id());

            $pr_data[] = [
                'product_id'   => $item->get_product_id(), // the Product id
                'variation_id' => $item->get_variation_id(), // the Variation id
                'product_name'    => $item->get_name(), // Name of the product
                'quantity'     => $item->get_quantity(),  
                'tax_class'    => $item->get_tax_class(),
                'line_subtotal'     => $item->get_subtotal(), // Line subtotal (non discounted)
                'line_subtotal_tax' => $item->get_subtotal_tax(), // Line subtotal tax (non discounted)
                'line_total'        => $item->get_total(), // Line total (discounted)
                'line_total_tax'    => $item->get_total_tax(), // Line total tax (discounted)
                'product_type' => $product instanceof WC_Product ? $product->get_type():"",
                'product_sku' => $product instanceof WC_Product ? $product->get_sku():"",
                'product_price' => $product instanceof WC_Product ? $product->get_price():"",
            ];
 
        endforeach;

        return json_encode([...$order_data, ...['line-items'=>$pr_data]]);
    }
}