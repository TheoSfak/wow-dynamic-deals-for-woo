<?php
/**
 * Uninstall script for Woo Dynamic Deals
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

// Exit if accessed directly or not in uninstall context.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Load the autoloader and database class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';
WDD\Autoloader::register();

// Run uninstall routine.
WDD\Database::uninstall();
