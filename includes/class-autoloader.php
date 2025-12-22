<?php
/**
 * Autoloader for WDD Classes
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

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
		$len = strlen( self::$prefix );
		if ( strncmp( self::$prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );

		$relative_class = str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class );

		$file_name = self::convert_to_file_name( $relative_class );

		$file = self::$base_dir . $file_name . '.php';

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
		$parts = explode( DIRECTORY_SEPARATOR, $class_name );

		$processed_parts = array();
		foreach ( $parts as $part ) {
			$kebab = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $part ) );
			$processed_parts[] = $kebab;
		}

		$last_index = count( $processed_parts ) - 1;
		$processed_parts[ $last_index ] = 'class-' . $processed_parts[ $last_index ];

		return implode( DIRECTORY_SEPARATOR, $processed_parts );
	}
}
