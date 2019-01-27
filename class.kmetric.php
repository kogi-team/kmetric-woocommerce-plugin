<?php

class Kmetric {

    public static function init() {
		self::init_hooks();
    }
    
    /**
	 * Initializes WordPress hooks
	 */
    private static function init_hooks() {
		add_action('wp_head', array( 'Kmetric', 'kmetric_load_sdk' ));
		/**
		 * Check if WooCommerce is active
		 **/
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			/**
			 * Register Tracking
			 */     

			/**
			 * Login Tracking
			 */  

			/**
			 * Ecommerce Tracking
			 */
			add_action('woocommerce_thankyou', array( 'Kmetric', 'kmetric_woocommerce_order_success'));

		}
    }

    public static function get_product_id() {
		return get_option('wordpress_kmetric_product_id');
    }

    /**
	 * Kmetric Javascript SDK
	 */
    public static function kmetric_load_sdk() {
		$product_id = self::get_product_id();
		if( $product_id ) {
			echo '<script>
				(function (vari, src, a, m) {
				window[vari] = window[vari] || function () {
				(window[vari].q = window[vari].q || []).push(arguments)};
				a = document.createElement("script"),
				m = document.getElementsByTagName("script")[0];
				a.async = 1;
				a.src = src;
				m.parentNode.insertBefore(a, m)
				})("kg", "https://api.kmetric.io/js/sdk-kogi-v1.js");
				kg("pageview", "'.$product_id.'");
			</script>';
		}
	}

	public static function kmetric_woocommerce_order_success( $order_id ) {
		$product_id = self::get_product_id();
		if( $product_id ) {
			// Lets grab the order
			$order = wc_get_order( $order_id );

			/**
			 * Put your tracking code here
			 * You can get the order total etc e.g. $order->get_total();
			 */
			
			// This is the order total
			$order->get_total();
			
			// This is how to grab line items from the order 
			$line_items = $order->get_items();
			$items = array();
			// This loops over line items
			foreach ( $line_items as $item_product ) {
		  		// This will be a product
				$product = $order->get_product_from_item( $item_product );

				$item = array();
				$item['id'] = $item_product->get_product_id();
				$item['name'] = $product->get_name();
				$item['sku'] = $product->get_sku();
				$item['qty'] = $item_product['qty'];
				$item['total'] = $order->get_line_total( $item_product, true, true );
				array_push($items, $item);
			}
			
			echo '<script> 
				var params = [];
				params["payment_transaction_id"] = "'.$order->get_transaction_id().'";
				params["payment_amount"] = "'.$order->get_total().'";
				params["payment_tax_amount"] = "'.$order->get_total_tax().'";
				params["payment_shipping_amount"] = "'.$order->get_shipping_total().'";
				params["payment_affiliation"] = "";
				params["user_id"] = "'.$order->get_customer_id().'";
				params["payment_items"] = '.json_encode($items).';
				params["payment_status"] = "'.$order->get_status().'";
				params["payment_order_id"] = "'.$order_id.'";
				kg("payment", "'.$product_id.'", params);
			</script>';
		}

	}

     
}