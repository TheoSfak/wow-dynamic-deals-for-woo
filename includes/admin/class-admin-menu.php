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
}
