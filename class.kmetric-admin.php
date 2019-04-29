<?php

class Kmetric_Admin {

    const NONCE = 'kmetric-update-product-id';
    
	public static function init() {
		self::init_hooks();
	}

	public static function init_hooks() {
		add_action( 'admin_init', array( 'Kmetric_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Kmetric_Admin', 'admin_menu' ), 5 );
        add_action( 'admin_enqueue_scripts', array( 'Kmetric_Admin', 'load_resources' ) );
	}

	public static function admin_init() {
		load_plugin_textdomain( 'kmetric' );
		register_setting( 'kmetric-set-product-id', 'wordpress_kmetric_product_id' );
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

	public static function display_page() {
		if ( !Kmetric::get_product_id() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) ) {
			self::view( 'start');
		} else {
			$product_id = Kmetric::get_product_id();
			self::view( 'config' );
		}
	}
    
	public static function view( $name ) {
		$file = KMETRIC_PLUGIN_DIR . 'views/'. $name . '.php';
		include( $file );
    }
}
