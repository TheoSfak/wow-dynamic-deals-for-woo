<?php
/**
 * Admin Menu
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Menu class
 */
class AdminMenu {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'wp_ajax_wdd_update_from_github', array( $this, 'update_from_github' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_menu() {
		$settings = get_option( 'wdd_settings', array(
			'enable_price_rules'     => 1,
			'enable_tiered_pricing'  => 1,
			'enable_cart_discounts'  => 1,
			'enable_gift_rules'      => 1,
		) );
		
		add_menu_page(
			__( 'Wow Dynamic Deals for Woo', 'wow-dynamic-deals-for-woo' ),
			__( 'Dynamic Deals', 'wow-dynamic-deals-for-woo' ),
			'manage_woocommerce',
			'woo-dynamic-deals',
			array( $this, 'render_main_page' ),
			'dashicons-tag',
			56
		);
		
		add_submenu_page(
			'woo-dynamic-deals',
			__( '🏠 Home', 'wow-dynamic-deals-for-woo' ),
			__( '🏠 Home', 'wow-dynamic-deals-for-woo' ),
			'manage_woocommerce',
			'woo-dynamic-deals',
			array( $this, 'render_main_page' )
		);
		
		if ( ! empty( $settings['enable_price_rules'] ) ) {
			add_submenu_page(
				'woo-dynamic-deals',
				__( '💰 Price Rules', 'wow-dynamic-deals-for-woo' ),
				__( '💰 Price Rules', 'wow-dynamic-deals-for-woo' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=price-rules',
				array( $this, 'render_main_page' )
			);
		}
		
		if ( ! empty( $settings['enable_tiered_pricing'] ) ) {
			add_submenu_page(
			'woo-dynamic-deals',
				__( '📊 Tiered Pricing', 'wow-dynamic-deals-for-woo' ),
				__( '📊 Tiered Pricing', 'wow-dynamic-deals-for-woo' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=tiered-pricing',
				array( $this, 'render_main_page' )
			);
		}
		
		if ( ! empty( $settings['enable_cart_discounts'] ) ) {
			add_submenu_page(
			'woo-dynamic-deals',
				__( '🛒 Cart Discounts', 'wow-dynamic-deals-for-woo' ),
				__( '🛒 Cart Discounts', 'wow-dynamic-deals-for-woo' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=cart-discounts',
				array( $this, 'render_main_page' )
			);
		}
		
		if ( ! empty( $settings['enable_gift_rules'] ) ) {
			add_submenu_page(
			'woo-dynamic-deals',
				__( '🎁 Free Gifts', 'wow-dynamic-deals-for-woo' ),
				__( '🎁 Free Gifts', 'wow-dynamic-deals-for-woo' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=gifts',
				array( $this, 'render_main_page' )
			);
		}
		
		add_submenu_page(
		'woo-dynamic-deals',
			__( '⚙️ Settings', 'wow-dynamic-deals-for-woo' ),
			__( '⚙️ Settings', 'wow-dynamic-deals-for-woo' ),
			'manage_woocommerce',
			'woo-dynamic-deals&tab=settings',
			array( $this, 'render_main_page' )
		);
	}

	/**
	 * Render main admin page
	 */
	public function render_main_page() {
		include WDD_PLUGIN_DIR . '/admin/views/dashboard.php';
	}
	
	/**
	 * Update plugin from GitHub
	 */
	public function update_from_github() {
		// Verify nonce
		check_ajax_referer( 'wdd_admin_nonce', 'nonce' );
		
		// Check permissions
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wow-dynamic-deals-for-woo' ) ) );
		}
		
		// Get plugin basename for reactivation
		$plugin_basename = plugin_basename( WDD_PLUGIN_DIR . '/wow-dynamic-deals-for-woo.php' );
		$was_active = is_plugin_active( $plugin_basename );
		
		// Deactivate plugin before update
		if ( $was_active ) {
			deactivate_plugins( $plugin_basename );
		}
		
		// GitHub repository details
		$github_user = 'TheoSfak';
		$github_repo = 'wow-dynamic-deals-for-woo';
		$zip_url = "https://github.com/{$github_user}/{$github_repo}/archive/refs/heads/master.zip";
		
		// Include WordPress filesystem
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;
		
		// Clean up any old temp folders first
		$plugin_dir = WP_PLUGIN_DIR;
		$folders = $wp_filesystem->dirlist( $plugin_dir );
		if ( is_array( $folders ) ) {
			foreach ( $folders as $folder => $details ) {
				if ( strpos( $folder, 'wdd-temp-' ) === 0 || strpos( $folder, 'wdd-backup-' ) === 0 ) {
					$wp_filesystem->delete( $plugin_dir . '/' . $folder, true );
				}
			}
		}
		
		// Download the zip file
		$temp_file = download_url( $zip_url );
		
		if ( is_wp_error( $temp_file ) ) {
			wp_send_json_error( array( 'message' => $temp_file->get_error_message() ) );
		}
		
		// Unzip to temp directory
		$plugin_path = untrailingslashit( WDD_PLUGIN_DIR );
		$temp_dir = $plugin_dir . '/wdd-temp-update';
		
		$unzip_result = unzip_file( $temp_file, $temp_dir );
		
		// Clean up temp zip
		@unlink( $temp_file );
		
		if ( is_wp_error( $unzip_result ) ) {
			$wp_filesystem->delete( $temp_dir, true );
			wp_send_json_error( array( 'message' => $unzip_result->get_error_message() ) );
		}
		
		// Move files from extracted folder to plugin directory
		$extracted_folder = $temp_dir . '/' . $github_repo . '-master';
		
		if ( ! $wp_filesystem->exists( $extracted_folder ) ) {
			$wp_filesystem->delete( $temp_dir, true );
			wp_send_json_error( array( 'message' => __( 'Extracted folder not found.', 'wow-dynamic-deals-for-woo' ) ) );
		}
		
		// Verify plugin path exists and is writable
		if ( ! $wp_filesystem->exists( $plugin_path ) ) {
			$wp_filesystem->delete( $temp_dir, true );
			wp_send_json_error( array( 'message' => sprintf( __( 'Plugin directory not found: %s', 'wow-dynamic-deals-for-woo' ), $plugin_path ) ) );
		}
		
		if ( ! $wp_filesystem->is_writable( $plugin_path ) ) {
			$wp_filesystem->delete( $temp_dir, true );
			wp_send_json_error( array( 'message' => __( 'Plugin directory is not writable. Check file permissions.', 'wow-dynamic-deals-for-woo' ) ) );
		}
		
		// Delete current plugin files (keep the folder)
		$plugin_files = $wp_filesystem->dirlist( $plugin_path );
		if ( is_array( $plugin_files ) ) {
			foreach ( $plugin_files as $file => $details ) {
				if ( $file !== '.' && $file !== '..' ) {
					$delete_result = $wp_filesystem->delete( $plugin_path . '/' . $file, true );
					if ( ! $delete_result ) {
						$wp_filesystem->delete( $temp_dir, true );
						wp_send_json_error( array( 'message' => sprintf( __( 'Failed to delete file: %s', 'wow-dynamic-deals-for-woo' ), $file ) ) );
					}
				}
			}
		}
		
		// Copy new files to plugin directory
		$new_files = $wp_filesystem->dirlist( $extracted_folder );
		if ( ! is_array( $new_files ) || empty( $new_files ) ) {
			$wp_filesystem->delete( $temp_dir, true );
			wp_send_json_error( array( 'message' => __( 'No files found in downloaded package.', 'wow-dynamic-deals-for-woo' ) ) );
		}
		
		$copy_errors = array();
		foreach ( $new_files as $file => $details ) {
			if ( $file === '.' || $file === '..' ) {
				continue;
			}
			
			$source = $extracted_folder . '/' . $file;
			$destination = $plugin_path . '/' . $file;
			
			if ( $details['type'] === 'd' ) {
				$copy_result = copy_dir( $source, $destination );
				if ( is_wp_error( $copy_result ) ) {
					$copy_errors[] = $file . ': ' . $copy_result->get_error_message();
				}
			} else {
				$copy_result = $wp_filesystem->copy( $source, $destination, true );
				if ( ! $copy_result ) {
					$copy_errors[] = $file;
				}
			}
		}
		
		// Clean up temp directory
		$wp_filesystem->delete( $temp_dir, true );
		
		// Check if there were any copy errors
		if ( ! empty( $copy_errors ) ) {
			wp_send_json_error( array( 
				'message' => __( 'Some files failed to copy: ', 'wow-dynamic-deals-for-woo' ) . implode( ', ', $copy_errors )
			) );
		}
		
		// Clear any WordPress caches
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
		
		// Reactivate plugin if it was active before
		if ( $was_active ) {
			$activate_result = activate_plugin( $plugin_basename );
			if ( is_wp_error( $activate_result ) ) {
				wp_send_json_error( array( 'message' => __( 'Plugin updated but failed to reactivate: ', 'wow-dynamic-deals-for-woo' ) . $activate_result->get_error_message() ) );
			}
		}
		
		wp_send_json_success( array( 'message' => __( 'Plugin updated successfully from GitHub!', 'wow-dynamic-deals-for-woo' ) ) );
	}
}
