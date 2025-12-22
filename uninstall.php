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

// Increase timeout for uninstall
@set_time_limit( 300 );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';
WDD\Autoloader::register();

try {
	WDD\Database::uninstall();
} catch ( Exception $e ) {
	error_log( 'WDD Uninstall Error: ' . $e->getMessage() );
}

