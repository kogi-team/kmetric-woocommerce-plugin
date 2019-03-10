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
            add_action( 'user_register', array( 'Kmetric', 'kmetric_wordpress_user_register') );

			/**
			 * Login Tracking
			 */  
            add_action('wp_login', array( 'Kmetric', 'kmetric_wordpress_user_login') );

			/**
			 * E-commerce Behavior Tracking
			 */
            add_action('woocommerce_add_to_cart', array( 'Kmetric', 'kmetric_woocommerce_add_to_cart') );
            add_action('woocommerce_remove_cart_item', array( 'Kmetric', 'kmetric_woocommerce_remove_cart_item') );

             /**
			 * Ecommerce Tracking - Payment
			 */
			add_action('woocommerce_payment_complete', array( 'Kmetric', 'kmetric_woocommerce_payment_complete'));

		}
    }

    public static function get_product_id() {
		return get_option('wordpress_kmetric_product_id');
    }

    /**
	 * Kmetric Javascript SDK
	 */
    public static function kmetric_load_sdk() {
		$kmetric_product_id = self::get_product_id();
		if( $kmetric_product_id ) {
			echo '<script>(function (vari, src, a, m) {
				window[vari] = window[vari] || function () {
				(window[vari].q = window[vari].q || []).push(arguments)};
				a = document.createElement("script"),
				m = document.getElementsByTagName("script")[0];
				a.async = 1;
				a.src = src;
				m.parentNode.insertBefore(a, m)
			})("kg", "https://api.kmetric.io/js/sdk-kogi-v1.js");
			kg("pageview", "'.$kmetric_product_id.'");
			</script>';
		}
	}

    public static function kmetric_wordpress_user_register( $user_id ) {
        $kmetric_product_id = self::get_product_id();

        if( $kmetric_product_id ) {
            $user_info = get_userdata($user_id);

            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params['user_id'] = $user_id;
            $params["username"] = $user_info->user_login;
            $params['user_email'] = $user_info->user_email;
            
            wp_remote_post('https://api.kmetric.io/api/v1/server-side/register', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(),
                'body' => json_encode($params),
            ));
        }
    }

    public static function kmetric_wordpress_user_login( $user_login, $user ){
        $kmetric_product_id = self::get_product_id();
        if( $kmetric_product_id ) {
            $user_info = get_userdata($user_id);

            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params['user_id'] = $user->ID;
            
            wp_remote_post('https://api.kmetric.io/api/v1/login', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(),
                'body' => json_encode($params),
            ));
        }
    }

    public static function kmetric_woocommerce_add_to_cart() {
        $kmetric_product_id = self::get_product_id();

        if( $kmetric_product_id ) {
            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params["e_action"] = 'add_card';

            $items = array();
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if($cart_item_key === $cart_remove_item_key) {
                    $product = wc_get_product( $cart_item['product_id'] );
                    array_push($items, array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name()
                    ));
                }
            }

            $params["e_items"] = $items;

            wp_remote_post('https://api.kmetric.io/api/v1/ecommerce/behavior', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(),
                'body' => json_encode($params),
            ));
        }
    }

    public static function kmetric_woocommerce_remove_cart_item( $cart_remove_item_key ) {
        $kmetric_product_id = self::get_product_id();

        if( $kmetric_product_id ) {
            $items = array();
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if($cart_item_key === $cart_remove_item_key) {
                    $product = wc_get_product( $cart_item['product_id'] );
                    array_push($items, array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name()
                    ));
                }
            }

            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params["e_action"] = 'remove_card';
            $params["e_items"] = $items;

            wp_remote_post('https://api.kmetric.io/api/v1/ecommerce/behavior', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(),
                'body' => json_encode($params),
            ));
        }
    }

	public static function kmetric_woocommerce_payment_complete( $order_id ) {
        $kmetric_product_id = self::get_product_id();

		if( $kmetric_product_id ) {
            $params = [];
            
			$order = wc_get_order( $order_id );
			$order->get_total();
            $line_items = $order->get_items();
            
			$items = array();
			foreach ( $line_items as $item_product ) {
				$product = $order->get_product_from_item( $item_product );

				$item = array();
				$item['id'] = $item_product->get_product_id();
				$item['name'] = $product->get_name();
				$item['sku'] = $product->get_sku();
				$item['qty'] = $item_product['qty'];
				$item['total'] = $order->get_line_total( $item_product, true, true );
				array_push($items, $item);
            }
            
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params['user_id'] = $user->ID;
            $params['payment_transaction_id'] = $order->get_transaction_id();
            $params["payment_amount"] = $order->get_total();
            $params["payment_tax_amount"] = $order->get_total_tax();
            $params["payment_shipping_amount"] = $order->get_shipping_total();
            $params["payment_affiliation"] = '';
            $params["user_id"] = $order->get_customer_id();
            $params["payment_items"] = $items;
            $params["payment_status"] = $order->get_status();
            $params["payment_order_id"] = $order_id;

            wp_remote_post('https://api.kmetric.io/api/v1/login', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(),
                'body' => json_encode($params),
            ));
		}
	}

     
}