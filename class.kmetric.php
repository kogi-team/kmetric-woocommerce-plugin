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
			 * User Tracking
			 */     
            // add_action( 'user_register', array( 'Kmetric', 'kmetric_wordpress_user_register') );
            // add_action('wp_login', array( 'Kmetric', 'kmetric_wordpress_user_login') );

			/**
			 * E-commerce Behavior Tracking
			 */
            add_action('woocommerce_after_single_product', array( 'Kmetric', 'kmetric_woocommerce_product_detail'), 5 );
            add_action('woocommerce_add_to_cart', array( 'Kmetric', 'kmetric_woocommerce_add_to_cart'), 10, 6 );
            add_action('woocommerce_remove_cart_item', array( 'Kmetric', 'kmetric_woocommerce_remove_cart_item'), 10, 2 );
            add_action('woocommerce_after_checkout_form', array( 'Kmetric', 'kmetric_woocommerce_cart_checkout') );

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

    public static function kmetric_woocommerce_product_detail(){
        $kmetric_product_id = self::get_product_id();
		if( $kmetric_product_id ) {
            $product = wc_get_product();
            echo '<script>
                var kmetric_params = [];
                kmetric_params["fp_id"] = "'.$_COOKIE['kmetric_fp_id'].'";
                kmetric_params["user_id"] = "'.get_current_user_id().'";
                kmetric_params["e_items"] = [{
                    "id" : "'.$product->get_id().'",
                    "name" : "'.$product->get_name().'"
                }];
                kmetric_params["e_action"] = "detail";
                kg("ecommerce", "'.$kmetric_product_id.'", kmetric_params);
            </script>';
        }
    }

    public static function kmetric_woocommerce_add_to_cart( $cart_item_key = null, $product_id = null, $quantity = null, $variation_id = null, $variation = null, $cart_item_data = null) {
        $kmetric_product_id = self::get_product_id();

        if( $kmetric_product_id && count(WC()->cart->get_cart()) === 1 ) {
            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params["e_action"] = 'add_card';
            
            if(get_current_user_id()) {
                $params["user_id"] = get_current_user_id();
            }

            $items = array();
            $product = wc_get_product( $product_id );
            array_push($items, array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'quantity' => $quantity
            ));
            $params["e_items"] = $items;

            wp_remote_post('https://api.kmetric.io/api/v1/server-side/ecommerce/behavior', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(
                    'content-type' => 'multipart/form-data'
                ),
                'body' => $params,
            ));
        }
    }

    public static function kmetric_woocommerce_remove_cart_item( $cart_item_key, $instance ) {
        $kmetric_product_id = self::get_product_id();

        if( $kmetric_product_id && count(WC()->cart->get_cart()) === 0 ) {
            $params = [];
            $params["fp_id"] = $_COOKIE['kmetric_fp_id'];
            $params["product_id"] = $kmetric_product_id;
            $params["e_action"] = 'remove_card';
            
            if(get_current_user_id()) {
                $params["user_id"] = get_current_user_id();
            }

            $items = array();
            $product = wc_get_product( $instance->cart_contents[$cart_item_key]['product_id'] );
            array_push($items, array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'quantity' => $instance->cart_contents[$cart_item_key]['quantity']
            ));
            $params["e_items"] = $items;

            wp_remote_post('https://api.kmetric.io/api/v1/server-side/ecommerce/behavior', array(
                'method' => 'POST',
                'timeout' => 5,
                'headers' => array(
                    'content-type' => 'multipart/form-data'
                ),
                'body' => $params,
            ));
        }
    }

    public static function kmetric_woocommerce_cart_checkout(){
        $kmetric_product_id = self::get_product_id();
		if( $kmetric_product_id ) {
            $product = wc_get_product();
            $return = '<script>
                var kmetric_params = [];
                kmetric_params["fp_id"] = "'.$_COOKIE['kmetric_fp_id'].'";
                kmetric_params["user_id"] = "'.get_current_user_id().'";
                kmetric_params["e_items"] = [];';
                
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $product = $cart_item['data'];
                if(!empty($product)){
                    $return .= 'kmetric_params["e_items"].push({
                        "id" : "'.$product->get_id().'",
                        "name" : "'.$product->get_name().'"
                    });';
                }
            };

            $return .= 'kmetric_params["e_action"] = "checkout";';
            $return .= 'kg("ecommerce", "'.$kmetric_product_id.'", kmetric_params);';
            $return .= '</script>';
            echo $return;
        }
    }
     
}