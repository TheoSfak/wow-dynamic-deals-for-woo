<?php
/**
 * Uninstall script for Wow Dynamic Deals for Woo
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';
WDD\Autoloader::register();

WDD\Database::uninstall();
