<?php
/**
 * Database Schema Management
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
 * Database class
 */
class Database {

	/**
	 * Database version
	 *
	 * @var string
	 */
	const DB_VERSION = '1.1.0';

	/**
	 * Database version option name
	 *
	 * @var string
	 */
	const DB_VERSION_OPTION = 'wdd_db_version';

	/**
	 * Activate plugin - create tables
	 */
	public static function activate() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$current_version = get_option( self::DB_VERSION_OPTION );

		// Check if we need to create or update tables.
		if ( $current_version === self::DB_VERSION ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Table names.
		$pricing_rules_table      = $wpdb->prefix . 'wdd_pricing_rules';
		$tiered_pricing_table     = $wpdb->prefix . 'wdd_tiered_pricing';
		$cart_discount_rules_table = $wpdb->prefix . 'wdd_cart_discount_rules';
		$gift_rules_table         = $wpdb->prefix . 'wdd_gift_rules';

		// Pricing Rules Table.
		$sql[] = "CREATE TABLE $pricing_rules_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			status varchar(20) DEFAULT 'active',
			priority int(11) DEFAULT 10,
			adjustment_type varchar(50) NOT NULL,
			adjustment_value decimal(10,2) NOT NULL,
			apply_to varchar(20) DEFAULT 'regular_price',
			product_ids longtext,
			category_ids longtext,
			user_roles longtext,
			user_ids longtext,
			conditions longtext,
			date_from datetime DEFAULT NULL,
			date_to datetime DEFAULT NULL,
			days_of_week varchar(255) DEFAULT NULL,
			time_from time DEFAULT NULL,
			time_to time DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY status (status),
			KEY priority (priority)
		) $charset_collate;";

		// Tiered Pricing Table.
		$sql[] = "CREATE TABLE $tiered_pricing_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			status varchar(20) DEFAULT 'active',
			priority int(11) DEFAULT 10,
			product_ids longtext,
			category_ids longtext,
			calculation_mode varchar(20) DEFAULT 'per_line',
			tiers longtext NOT NULL,
			user_roles longtext,
			user_ids longtext,
			date_from datetime DEFAULT NULL,
			date_to datetime DEFAULT NULL,
			days_of_week varchar(255) DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY status (status),
			KEY priority (priority)
		) $charset_collate;";

		// Cart Discount Rules Table.
		$sql[] = "CREATE TABLE $cart_discount_rules_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			status varchar(20) DEFAULT 'active',
			priority int(11) DEFAULT 10,
			discount_type varchar(50) NOT NULL,
			discount_value decimal(10,2) NOT NULL,
			conditions longtext,
			first_order_only tinyint(1) DEFAULT 0,
			min_cart_total decimal(10,2) DEFAULT NULL,
			max_cart_total decimal(10,2) DEFAULT NULL,
			min_cart_quantity int(11) DEFAULT NULL,
			max_cart_quantity int(11) DEFAULT NULL,
			apply_free_shipping tinyint(1) DEFAULT 0,
			user_roles longtext,
			user_ids longtext,
			date_from datetime DEFAULT NULL,
			date_to datetime DEFAULT NULL,
			days_of_week varchar(255) DEFAULT NULL,
			time_from time DEFAULT NULL,
			time_to time DEFAULT NULL,
			stop_further_rules tinyint(1) DEFAULT 0,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY status (status),
			KEY priority (priority)
		) $charset_collate;";

		// Gift Rules Table.
		$sql[] = "CREATE TABLE $gift_rules_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			status varchar(20) DEFAULT 'active',
			priority int(11) DEFAULT 10,
			trigger_type varchar(50) NOT NULL,
			trigger_products longtext,
			trigger_categories longtext,
			trigger_quantity int(11) DEFAULT 1,
			trigger_amount decimal(10,2) DEFAULT NULL,
			additional_triggers longtext,
			trigger_logic varchar(10) DEFAULT 'and',
			gift_products longtext NOT NULL,
			gift_mode varchar(20) DEFAULT 'auto',
			gift_message text,
			conditions longtext,
			purchase_history_conditions longtext,
			user_roles longtext,
			user_ids longtext,
			date_from datetime DEFAULT NULL,
			date_to datetime DEFAULT NULL,
			days_of_week varchar(255) DEFAULT NULL,
			max_gifts_per_order int(11) DEFAULT 1,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY status (status),
			KEY priority (priority)
		) $charset_collate;";

		// Execute SQL.
		foreach ( $sql as $query ) {
			dbDelta( $query );
		}

		// Manual column additions for existing installations.
		self::upgrade_database( $current_version );

		// Update database version.
		update_option( self::DB_VERSION_OPTION, self::DB_VERSION );

		// Set default options.
		self::set_default_options();
	}

	/**
	 * Upgrade database schema for existing installations
	 *
	 * @param string $current_version Current database version.
	 */
	private static function upgrade_database( $current_version ) {
		global $wpdb;

		// Upgrade from 1.0.0 to 1.1.0 - Add first_order_only column.
		if ( version_compare( $current_version, '1.1.0', '<' ) ) {
			$cart_table = $wpdb->prefix . 'wdd_cart_discount_rules';
			
			// Check if column exists.
			$column_exists = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
					DB_NAME,
					$cart_table,
					'first_order_only'
				)
			);

			// Add column if it doesn't exist.
			if ( empty( $column_exists ) ) {
				$wpdb->query(
					"ALTER TABLE $cart_table ADD COLUMN first_order_only tinyint(1) DEFAULT 0 AFTER conditions"
				); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			}
		}
		
		// Upgrade - Rename free_shipping to apply_free_shipping column.
		$cart_table = $wpdb->prefix . 'wdd_cart_discount_rules';
		
		// Check if old column exists.
		$old_column_exists = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
				DB_NAME,
				$cart_table,
				'free_shipping'
			)
		);
		
		// Check if new column exists.
		$new_column_exists = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
				DB_NAME,
				$cart_table,
				'apply_free_shipping'
			)
		);

		// Rename column if old exists and new doesn't.
		if ( ! empty( $old_column_exists ) && empty( $new_column_exists ) ) {
			$wpdb->query(
				"ALTER TABLE $cart_table CHANGE COLUMN free_shipping apply_free_shipping tinyint(1) DEFAULT 0"
			); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		} elseif ( empty( $old_column_exists ) && empty( $new_column_exists ) ) {
			// Neither exists, add the new column.
			$wpdb->query(
				"ALTER TABLE $cart_table ADD COLUMN apply_free_shipping tinyint(1) DEFAULT 0 AFTER max_cart_quantity"
			); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

	/**
	 * Deactivate plugin - clean up scheduled events
	 */
	public static function deactivate() {
		// Clear any scheduled events.
		wp_clear_scheduled_hook( 'wdd_cleanup_expired_rules' );
	}

	/**
	 * Uninstall plugin - remove tables and options
	 */
	public static function uninstall() {
		global $wpdb;

		// Check if user wants to keep data.
		$keep_data = get_option( 'wdd_keep_data_on_uninstall', false );

		if ( ! $keep_data ) {
			// Drop tables.
			$tables = array(
				$wpdb->prefix . 'wdd_pricing_rules',
				$wpdb->prefix . 'wdd_tiered_pricing',
				$wpdb->prefix . 'wdd_cart_discount_rules',
				$wpdb->prefix . 'wdd_gift_rules',
			);

			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE IF EXISTS $table" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

		// Delete options.
		delete_option( self::DB_VERSION_OPTION );
		delete_option( 'wdd_settings' );
		delete_option( 'wdd_keep_data_on_uninstall' );
	}
}	/**
	 * Set default plugin options
	 */
	private static function set_default_options() {
		$default_settings = array(
			'enable_pricing_rules'         => true,
			'enable_tiered_pricing'        => true,
			'enable_cart_discounts'        => true,
			'enable_gift_rules'            => true,
			'debug_mode'                   => false,
			'show_tiered_pricing_table'    => true,
			'show_discount_badge'          => true,
			'show_gift_messages'           => true,
		);

		add_option( 'wdd_settings', $default_settings );
	}
}
