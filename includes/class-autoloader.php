<?php
/**
 * Autoloader for WDD Classes
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloader class
 */
class Autoloader {

	/**
	 * Namespace prefix
	 *
	 * @var string
	 */
	private static $prefix = 'WDD\\';

	/**
	 * Base directory
	 *
	 * @var string
	 */
	private static $base_dir;

	/**
	 * Register autoloader
	 */
	public static function register() {
		self::$base_dir = WDD_PLUGIN_DIR . 'includes/';
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload classes
	 *
	 * @param string $class The class name.
	 */
	public static function autoload( $class ) {
		// Check if the class uses our namespace.
		$len = strlen( self::$prefix );
		if ( strncmp( self::$prefix, $class, $len ) !== 0 ) {
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class, $len );

		// Convert namespace separators to directory separators.
		$relative_class = str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class );

		// Convert class name to file name (PascalCase to kebab-case).
		$file_name = self::convert_to_file_name( $relative_class );

		// Build the file path.
		$file = self::$base_dir . $file_name . '.php';

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require $file;
		}
	}

	/**
	 * Convert class name to file name
	 * Example: Admin\AdminMenu -> admin/class-admin-menu
	 *
	 * @param string $class_name The class name.
	 * @return string
	 */
	private static function convert_to_file_name( $class_name ) {
		// Split by directory separator.
		$parts = explode( DIRECTORY_SEPARATOR, $class_name );

		// Process each part.
		$processed_parts = array();
		foreach ( $parts as $part ) {
			// Convert PascalCase to kebab-case.
			$kebab = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $part ) );
			$processed_parts[] = $kebab;
		}

		// Last part is the class file with 'class-' prefix.
		$last_index = count( $processed_parts ) - 1;
		$processed_parts[ $last_index ] = 'class-' . $processed_parts[ $last_index ];

		// Join with directory separator.
		return implode( DIRECTORY_SEPARATOR, $processed_parts );
	}
}
