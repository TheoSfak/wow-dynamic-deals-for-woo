<?php
/**
 * Cache Manager
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
 * Cache Manager class
 */
class CacheManager {

	/**
	 * Cache group
	 *
	 * @var string
	 */
	const CACHE_GROUP = 'wdd_rules';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Hook into rule updates to invalidate cache.
		add_action( 'wdd_rule_updated', array( $this, 'invalidate_rule_cache' ), 10, 1 );
		add_action( 'wdd_rule_deleted', array( $this, 'invalidate_rule_cache' ), 10, 1 );
		add_action( 'wdd_rule_created', array( $this, 'invalidate_all_caches' ), 10 );
	}

	/**
	 * Get cache
	 *
	 * @param string $key Cache key.
	 * @return mixed|false
	 */
	public function get( $key ) {
		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['cache_enabled'] ) ) {
			return false;
		}

		return wp_cache_get( $key, self::CACHE_GROUP );
	}

	/**
	 * Set cache
	 *
	 * @param string $key Cache key.
	 * @param mixed  $value Cache value.
	 * @param int    $expiration Expiration in seconds.
	 * @return bool
	 */
	public function set( $key, $value, $expiration = 3600 ) {
		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['cache_enabled'] ) ) {
			return false;
		}

		$cache_duration = isset( $settings['cache_duration'] ) ? intval( $settings['cache_duration'] ) : 3600;
		
		return wp_cache_set( $key, $value, self::CACHE_GROUP, $cache_duration );
	}

	/**
	 * Delete cache
	 *
	 * @param string $key Cache key.
	 * @return bool
	 */
	public function delete( $key ) {
		return wp_cache_delete( $key, self::CACHE_GROUP );
	}

	/**
	 * Invalidate rule cache
	 *
	 * @param string $table_name Table name.
	 */
	public function invalidate_rule_cache( $table_name ) {
		// Pattern: wdd_rules_{table}_{hash}.
		// Since we can't iterate wp_cache keys, we flush the entire group.
		$this->flush_group();
	}

	/**
	 * Invalidate all caches
	 */
	public function invalidate_all_caches() {
		$this->flush_group();
	}

	/**
	 * Flush cache group
	 */
	public function flush_group() {
		// WordPress doesn't have a built-in group flush, so we use a workaround.
		// We'll store a version number in options and append it to cache keys.
		$current_version = get_option( 'wdd_cache_version', 1 );
		update_option( 'wdd_cache_version', $current_version + 1, false );
	}

	/**
	 * Get cache version
	 *
	 * @return int
	 */
	public static function get_cache_version() {
		return intval( get_option( 'wdd_cache_version', 1 ) );
	}

	/**
	 * Build versioned cache key
	 *
	 * @param string $base_key Base cache key.
	 * @return string
	 */
	public static function get_versioned_key( $base_key ) {
		$version = self::get_cache_version();
		return $base_key . '_v' . $version;
	}

	/**
	 * Get cache stats
	 *
	 * @return array
	 */
	public function get_stats() {
		return array(
			'enabled'  => $this->is_enabled(),
			'version'  => self::get_cache_version(),
			'group'    => self::CACHE_GROUP,
			'duration' => $this->get_cache_duration(),
		);
	}

	/**
	 * Check if cache is enabled
	 *
	 * @return bool
	 */
	private function is_enabled() {
		$settings = get_option( 'wdd_settings', array() );
		return ! empty( $settings['cache_enabled'] );
	}

	/**
	 * Get cache duration
	 *
	 * @return int
	 */
	private function get_cache_duration() {
		$settings = get_option( 'wdd_settings', array() );
		return isset( $settings['cache_duration'] ) ? intval( $settings['cache_duration'] ) : 3600;
	}

	/**
	 * Clear all cache (static method for external use)
	 */
	public static function clear_all_cache() {
		$instance = new self();
		$instance->flush_group();
	}
}
