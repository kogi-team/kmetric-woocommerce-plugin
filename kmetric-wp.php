<?php
/**
 * Plugin Name: Kmetric Tracking
 * Plugin URI: https://www.kmetric.io/
 * Description: A tracking tool that helps you tracking anything.
 * Version: 0.0.1
 * Author: Kmetric
 * Author URI: https://www.kmetric.io/
 * Text Domain: kmetric
 * Domain Path: /i18n/languages/
 *
 * @package Kmetric
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'KMETRIC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KMETRIC_PLUGIN_FILE', __FILE__ );
define( 'KMETRIC_PLUGIN_BASENAME', plugin_basename( KMETRIC_PLUGIN_FILE ) );

require_once( KMETRIC_PLUGIN_DIR . 'class.kmetric.php' );

add_action( 'init', array( 'Kmetric', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( KMETRIC_PLUGIN_DIR . 'class.kmetric-admin.php' );
	add_action( 'init', array( 'Kmetric_Admin', 'init' ) );
}
