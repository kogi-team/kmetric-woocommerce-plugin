<?php

class Kmetric_Admin {

    const NONCE = 'kmetric-update-product-id';
    
	public static function init() {
		self::init_hooks();

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-product-id' ) {
			self::enter_product_id();
		}
	}

	public static function init_hooks() {
		add_action( 'admin_init', array( 'Kmetric_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Kmetric_Admin', 'admin_menu' ), 5 );
        add_action( 'admin_enqueue_scripts', array( 'Kmetric_Admin', 'load_resources' ) );
	}

	public static function admin_init() {
		load_plugin_textdomain( 'kmetric' );
	}

	public static function admin_menu() {
		add_options_page( __('Kmetric Tracking', 'kmetric'), __('Kmetric Tracking', 'kmetric'), 'manage_options', 'kmetric-product-config', array( 'Kmetric_Admin', 'display_page' ) );
	}

	public static function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;
	}

	public static function load_resources() {
		global $hook_suffix;

		if ( in_array( $hook_suffix, apply_filters( 'kmetric_admin_page_hook_suffixes', array(
			'settings_page_kmetric-product-config',
		) ) ) ) {
			wp_register_style( 'kmetric.css', plugin_dir_url( __FILE__ ) . 'assets/css/kmetric.css', array());
			wp_enqueue_style( 'kmetric.css');

			wp_register_script( 'kmetric.js', plugin_dir_url( __FILE__ ) . 'assets/js/kmetric.js', array('jquery'));
			wp_enqueue_script( 'kmetric.js' );
		}
	}

	public static function enter_product_id() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( __( 'Cheatin&#8217; uh?', 'kmetric' ) );
		}

		if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
			return false;

		$new_product_id = preg_replace( '/[^a-f0-9]/i', '', $_POST['product-id'] );

        self::save_product_id( $new_product_id );

		return true;
    }
    
	public static function save_product_id( $product_id ) {
        update_option( 'wordpress_kmetric_product_id', $product_id );
	}

	public static function display_page() {
		if ( !Kmetric::get_product_id() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) )
			self::display_start_page();
		else
			self::display_configuration_page();
	}

	public static function display_start_page() {
		if ( $product_id = Kmetric::get_product_id() ) {
			self::display_configuration_page();
			return;
        }

		if ( isset( $_GET['action'] ) ) {
			if ( $_GET['action'] == 'save-product-id' ) {
                self::save_product_id( $_GET['product-id'] );
				self::display_configuration_page();
                return;
			}
		}

		self::view( 'start');
	}

	public static function display_configuration_page() {
		$product_id = Kmetric::get_product_id();
		self::view( 'config' );
    }
    
    public static function get_page_url( $page = 'config' ) {

		$args = array( 'page' => 'kmetric-product-config' );

		$url = add_query_arg( $args, admin_url( 'options-general.php' ) );

		return $url;
    }
    
	public static function view( $name ) {
		$file = KMETRIC_PLUGIN_DIR . 'views/'. $name . '.php';
		include( $file );
    }
}
