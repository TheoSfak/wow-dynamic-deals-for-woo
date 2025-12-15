<?php
/**
 * Admin Menu
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Admin;

// Exit if accessed directly.
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
	}

	/**
	 * Add admin menu
	 */
	public function add_menu() {
		// Get settings to check which features are enabled
		$settings = get_option( 'wdd_settings', array(
			'enable_price_rules'     => 1,
			'enable_tiered_pricing'  => 1,
			'enable_cart_discounts'  => 1,
			'enable_gift_rules'      => 1,
		) );
		
		// Main menu page
		add_menu_page(
			__( 'Wow Dynamic Deals for Woo', 'woo-dynamic-deals' ),
			__( 'Dynamic Deals', 'woo-dynamic-deals' ),
			'manage_woocommerce',
			'woo-dynamic-deals',
			array( $this, 'render_main_page' ),
			'dashicons-tag',
			56
		);
		
		// Home submenu (rename the first item)
		add_submenu_page(
			'woo-dynamic-deals',
			__( '🏠 Home', 'woo-dynamic-deals' ),
			__( '🏠 Home', 'woo-dynamic-deals' ),
			'manage_woocommerce',
			'woo-dynamic-deals',
			array( $this, 'render_main_page' )
		);
		
		// Price Rules submenu
		if ( ! empty( $settings['enable_price_rules'] ) ) {
			add_submenu_page(
				'woo-dynamic-deals',
				__( '💰 Price Rules', 'woo-dynamic-deals' ),
				__( '💰 Price Rules', 'woo-dynamic-deals' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=price-rules',
				array( $this, 'render_main_page' )
			);
		}
		
		// Tiered Pricing submenu
		if ( ! empty( $settings['enable_tiered_pricing'] ) ) {
			add_submenu_page(
				'woo-dynamic-deals',
				__( '📊 Tiered Pricing', 'woo-dynamic-deals' ),
				__( '📊 Tiered Pricing', 'woo-dynamic-deals' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=tiered-pricing',
				array( $this, 'render_main_page' )
			);
		}
		
		// Cart Discounts submenu
		if ( ! empty( $settings['enable_cart_discounts'] ) ) {
			add_submenu_page(
				'woo-dynamic-deals',
				__( '🛒 Cart Discounts', 'woo-dynamic-deals' ),
				__( '🛒 Cart Discounts', 'woo-dynamic-deals' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=cart-discounts',
				array( $this, 'render_main_page' )
			);
		}
		
		// Free Gifts submenu
		if ( ! empty( $settings['enable_gift_rules'] ) ) {
			add_submenu_page(
				'woo-dynamic-deals',
				__( '🎁 Free Gifts', 'woo-dynamic-deals' ),
				__( '🎁 Free Gifts', 'woo-dynamic-deals' ),
				'manage_woocommerce',
				'woo-dynamic-deals&tab=gifts',
				array( $this, 'render_main_page' )
			);
		}
		
		// Settings submenu
		add_submenu_page(
			'woo-dynamic-deals',
			__( '⚙️ Settings', 'woo-dynamic-deals' ),
			__( '⚙️ Settings', 'woo-dynamic-deals' ),
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
}
