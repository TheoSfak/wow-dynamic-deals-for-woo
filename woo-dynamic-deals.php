<?php
/**
 * Plugin Name: Wow Dynamic Deals for Woo
 * Plugin URI: https://github.com/TheoSfak/wow-dynamic-deals-for-woo
 * Description: Advanced dynamic pricing, tiered discounts, cart-level promotions, and free gift management for WooCommerce
 * Version: 1.0.0
 * Author: Theodore Sfakianakis
 * Author URI: https://www.paypal.com/paypalme/TheodoreSfakianakis
 * Text Domain: woo-dynamic-deals
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 8.0
 * WC requires at least: 6.0
 * WC tested up to: 9.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WooDynamicDeals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'WDD_VERSION', '1.0.0' );
define( 'WDD_PLUGIN_FILE', __FILE__ );
define( 'WDD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WDD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WDD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once WDD_PLUGIN_DIR . 'includes/class-autoloader.php';

/**
 * Check if WooCommerce is active
 */
function wdd_check_woocommerce() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		add_action(
			'admin_notices',
			function() {
				echo '<div class="error"><p><strong>' . esc_html__( 'Woo Dynamic Deals', 'woo-dynamic-deals' ) . '</strong> ' .
					esc_html__( 'requires WooCommerce to be installed and activated.', 'woo-dynamic-deals' ) . '</p></div>';
			}
		);
		return false;
	}
	return true;
}

/**
 * Check PHP version
 */
function wdd_check_php_version() {
	if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
		add_action(
			'admin_notices',
			function() {
				echo '<div class="error"><p><strong>' . esc_html__( 'Woo Dynamic Deals', 'woo-dynamic-deals' ) . '</strong> ' .
					esc_html__( 'requires PHP 8.0 or higher. Your current PHP version is', 'woo-dynamic-deals' ) . ' ' . esc_html( PHP_VERSION ) . '</p></div>';
			}
		);
		return false;
	}
	return true;
}

/**
 * Initialize the plugin
 */
function wdd_init() {
	
	if ( ! wdd_check_php_version() || ! wdd_check_woocommerce() ) {
		return;
	}


	WDD\Autoloader::register();

	WDD\Plugin::get_instance();
	
}
add_action( 'plugins_loaded', 'wdd_init', 10 );

/**
 * WooCommerce HPOS compatibility declaration
 */
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

/**
 * Plugin activation hook
 */
function wdd_activate() {
	if ( ! wdd_check_php_version() || ! wdd_check_woocommerce() ) {
		wp_die(
			esc_html__( 'Plugin activation failed. Please check the requirements.', 'woo-dynamic-deals' ),
			esc_html__( 'Plugin Activation Error', 'woo-dynamic-deals' ),
			array( 'back_link' => true )
		);
	}

	require_once WDD_PLUGIN_DIR . 'includes/class-autoloader.php';
	WDD\Autoloader::register();

	WDD\Database::activate();

	set_transient( 'wdd_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'wdd_activate' );

/**
 * Plugin deactivation hook
 */
function wdd_deactivate() {
	require_once WDD_PLUGIN_DIR . 'includes/class-autoloader.php';
	WDD\Autoloader::register();

	WDD\Database::deactivate();

	wp_clear_scheduled_hook( 'wdd_cleanup_expired_rules' );
}
register_deactivation_hook( __FILE__, 'wdd_deactivate' );

/**
 * Plugin uninstall hook - defined in uninstall.php
 */
