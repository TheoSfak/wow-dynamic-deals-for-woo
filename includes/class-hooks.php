<?php
/**
 * WooCommerce Hooks Integration
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
 * Hooks class
 */
class Hooks {

	/**
	 * Constructor
	 */
	public function __construct() {
		error_log( 'WDD: Hooks class constructed' );
		// Register hooks - use init hook with priority 30 (after engines at priority 20)
		add_action( 'init', array( $this, 'init_hooks' ), 30 );
	}

	/**
	 * Initialize hooks after WooCommerce is loaded
	 */
	public function init_hooks() {
		error_log( 'WDD: Hooks init_hooks called' );
		$this->register_product_hooks();
		$this->register_cart_hooks();
		$this->register_admin_hooks();
	}

	/**
	 * Register product-related hooks
	 */
	private function register_product_hooks() {
		error_log( 'WDD: Registering product hooks' );
		// Display tiered pricing tables on product pages (after short description).
		add_action( 'woocommerce_single_product_summary', array( $this, 'display_tiered_pricing_table' ), 25 );

		// Display active discounts badges.
		add_action( 'woocommerce_single_product_summary', array( $this, 'display_discount_badge' ), 6 );

		// Filter price HTML for custom display format.
		add_filter( 'woocommerce_get_price_html', array( $this, 'filter_product_price_html' ), 99, 2 );
	}

	/**
	 * Register cart-related hooks
	 */
	private function register_cart_hooks() {
		// Display savings summary in cart.
		add_action( 'woocommerce_cart_totals_after_order_total', array( $this, 'display_cart_savings' ), 10 );

		// Display gift messages.
		add_action( 'woocommerce_before_cart_table', array( $this, 'display_gift_messages' ), 10 );

		// Filter cart item price display
		add_filter( 'woocommerce_cart_item_price', array( $this, 'filter_cart_item_price' ), 99, 3 );
		
		// Filter cart item subtotal display
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'filter_cart_item_subtotal' ), 99, 3 );
	}

	/**
	 * Register admin hooks
	 */
	private function register_admin_hooks() {
		// Add settings link on plugins page.
		add_filter( 'plugin_action_links_' . WDD_PLUGIN_BASENAME, array( $this, 'add_settings_link' ), 10, 1 );
	}

	/**
	 * Display tiered pricing table
	 */
	public function display_tiered_pricing_table() {
		global $product;

		error_log( "WDD Hooks: display_tiered_pricing_table called" );

		if ( ! $product ) {
			error_log( "WDD Hooks: No global product found" );
			return;
		}

		error_log( "WDD Hooks: Product ID: " . $product->get_id() );

		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend ) {
			$frontend->display_tiered_pricing( $product->get_id() );
		} else {
			error_log( "WDD Hooks: No frontend_display component found" );
		}
	}

	/**
	 * Display discount badge
	 */
	public function display_discount_badge() {
		global $product;

		if ( ! $product ) {
			return;
		}

		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend ) {
			$frontend->display_discount_badge( $product->get_id() );
		}
	}

	/**
	 * Display cart savings summary
	 */
	public function display_cart_savings() {
		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend ) {
			$frontend->display_savings_summary();
		}
	}

	/**
	 * Display gift messages
	 */
	public function display_gift_messages() {
		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend ) {
			$frontend->display_gift_messages();
		}
	}

	/**
	 * Filter product price HTML
	 *
	 * @param string      $price Product price HTML.
	 * @param \WC_Product $product Product object.
	 * @return string
	 */
	public function filter_product_price_html( $price, $product ) {
		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend ) {
			return $frontend->filter_price_html( $price, $product );
		}
		return $price;
	}

	/**
	 * Filter cart item price
	 *
	 * @param string $price Price HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function filter_cart_item_price( $price, $cart_item, $cart_item_key ) {
		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend && isset( $cart_item['data'] ) ) {
			return $frontend->filter_cart_item_price( $price, $cart_item['data'] );
		}
		return $price;
	}

	/**
	 * Filter cart item subtotal
	 *
	 * @param string $subtotal Subtotal HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function filter_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
		$frontend = Plugin::get_instance()->get_component( 'frontend_display' );
		if ( $frontend && isset( $cart_item['data'] ) ) {
			return $frontend->filter_cart_item_subtotal( $subtotal, $cart_item['data'], $cart_item['quantity'] );
		}
		return $subtotal;
	}

	/**
	 * Add settings link to plugins page
	 *
	 * @param array $links Plugin links.
	 * @return array
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=woo-dynamic-deals' ) ) . '">' . __( 'Settings', 'woo-dynamic-deals' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}
