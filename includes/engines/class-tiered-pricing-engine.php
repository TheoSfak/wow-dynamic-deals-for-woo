<?php
/**
 * Tiered Pricing Engine
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Engines;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tiered Pricing Engine class
 */
class TieredPricingEngine extends RuleEngine {

	/**
	 * Processing flag to prevent infinite loops
	 *
	 * @var array
	 */
	private $processing = array();

	/**
	 * Processed cart items to prevent double-application
	 *
	 * @var array
	 */
	private $processed_items = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->init_hooks();
	}

	/**
	 * Initialize WooCommerce hooks
	 */
	private function init_hooks() {
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'apply_cart_tiered_pricing' ), 100 );
		
		add_filter( 'woocommerce_cart_item_price', array( $this, 'display_cart_item_discount' ), 10, 3 );
	}

	/**
	 * Apply tiered pricing to product
	 *
	 * @param float      $price Product price.
	 * @param \WC_Product $product Product object.
	 * @return float
	 */
	public function apply_tiered_pricing( $price, $product ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			return $price;
		}

		$product_id = $product->get_id();
		
		if ( isset( $this->processing[ $product_id ] ) ) {
			return $price;
		}
		
		$this->processing[ $product_id ] = true;

		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['enable_tiered_pricing'] ) ) {
			unset( $this->processing[ $product_id ] );
			return $price;
		}

		$quantity   = $this->get_product_quantity_in_cart( $product_id );

		if ( $quantity <= 0 ) {
			unset( $this->processing[ $product_id ] );
			return $price;
		}

		$rule = $this->get_applicable_tier_rule( $product_id );
		if ( ! $rule ) {
			unset( $this->processing[ $product_id ] );
			return $price;
		}

		$tier_price = $this->calculate_tier_price( $price, $quantity, $rule );

		unset( $this->processing[ $product_id ] );
		return $tier_price ?? $price;
	}

	/**
	 * Apply tiered pricing to cart
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	public function apply_cart_tiered_pricing( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['enable_tiered_pricing'] ) ) {
			return;
		}

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = $cart_item['product_id'];
			$quantity   = $cart_item['quantity'];

			$rule = $this->get_applicable_tier_rule( $product_id );
			if ( ! $rule ) {
				continue;
			}

			$calculation_mode = $rule['calculation_mode'] ?? 'per_line';

			if ( 'combined' === $calculation_mode ) {
				$quantity = $this->get_combined_product_quantity( $product_id, $cart );
			}

			$product = $cart_item['data'];
			
			$original_price = $product->get_regular_price();
			if ( empty( $original_price ) ) {
				$original_price = $product->get_price();
			}
			
			$tier_price = $this->calculate_tier_price( $original_price, $quantity, $rule );


			if ( $tier_price && $tier_price !== $original_price ) {
				$discount_amount = $original_price - $tier_price;
				$discount_percent = ( $discount_amount / $original_price ) * 100;
				
				
				WC()->cart->cart_contents[ $cart_item_key ]['wdd_rule_name'] = $rule['title'];
				WC()->cart->cart_contents[ $cart_item_key ]['wdd_original_price'] = $original_price;
				WC()->cart->cart_contents[ $cart_item_key ]['wdd_discount_amount'] = $discount_amount;
				WC()->cart->cart_contents[ $cart_item_key ]['wdd_discount_percent'] = $discount_percent;
				WC()->cart->cart_contents[ $cart_item_key ]['wdd_price_applied'] = true;
				
				$cart_item['data']->set_price( $tier_price );
			} else {
			}
		}
	}

	/**
	 * Get applicable tier rule for product
	 *
	 * @param int $product_id Product ID.
	 * @return array|null
	 */
	private function get_applicable_tier_rule( $product_id ) {
		$rules = $this->get_rules( 'tiered_pricing' );


		if ( empty( $rules ) ) {
			return null;
		}

		foreach ( $rules as $rule ) {
			
			if ( ! $this->is_valid_datetime( $rule ) ) {
				continue;
			}

			if ( ! $this->is_valid_user( $rule ) ) {
				continue;
			}

			if ( ! $this->is_valid_product( $rule, $product_id ) ) {
				continue;
			}

			return $rule;
		}

		return null;
	}

	/**
	 * Calculate tier price based on quantity
	 *
	 * @param float $original_price Original price.
	 * @param int   $quantity Quantity.
	 * @param array $rule Tier rule.
	 * @return float|null
	 */
	private function calculate_tier_price( $original_price, $quantity, $rule ) {
		
		if ( empty( $rule['tiers'] ) ) {
			return null;
		}

		$tiers = maybe_unserialize( $rule['tiers'] );
		if ( ! is_array( $tiers ) ) {
			return null;
		}


		usort( $tiers, function( $a, $b ) {
			return ( $b['min_quantity'] ?? 0 ) <=> ( $a['min_quantity'] ?? 0 );
		});

		foreach ( $tiers as $tier ) {
			$min_qty = intval( $tier['min_quantity'] ?? 0 );
			$max_qty = ! empty( $tier['max_quantity'] ) ? intval( $tier['max_quantity'] ) : PHP_INT_MAX;


			if ( $quantity >= $min_qty && $quantity <= $max_qty ) {
				$new_price = $this->apply_tier_discount( $original_price, $tier );
				return $new_price;
			}
		}

		return null;
	}

	/**
	 * Apply tier discount
	 *
	 * @param float $price Original price.
	 * @param array $tier Tier data.
	 * @return float
	 */
	private function apply_tier_discount( $price, $tier ) {
		$discount_type  = $tier['discount_type'] ?? 'percentage';
		$discount_value = floatval( $tier['discount_value'] ?? 0 );

		switch ( $discount_type ) {
			case 'fixed_price':
				return max( 0, $discount_value );

			case 'percentage':
				$discount = ( $price * $discount_value ) / 100;
				return max( 0, $price - $discount );

			case 'fixed':
				return max( 0, $price - $discount_value );

			default:
				return $price;
		}
	}

	/**
	 * Get product quantity in cart
	 *
	 * @param int $product_id Product ID.
	 * @return int
	 */
	private function get_product_quantity_in_cart( $product_id ) {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return 0;
		}

		$quantity = 0;
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			if ( $cart_item['product_id'] === $product_id ) {
				$quantity += $cart_item['quantity'];
			}
		}

		return $quantity;
	}

	/**
	 * Get combined product quantity (for combined calculation mode)
	 *
	 * @param int      $product_id Product ID.
	 * @param \WC_Cart $cart Cart object.
	 * @return int
	 */
	private function get_combined_product_quantity( $product_id, $cart ) {
		$quantity = 0;
		foreach ( $cart->get_cart() as $cart_item ) {
			if ( $cart_item['product_id'] === $product_id ) {
				$quantity += $cart_item['quantity'];
			}
		}
		return $quantity;
	}

	/**
	 * Get tiers for product (for display purposes)
	 *
	 * @param int $product_id Product ID.
	 * @return array|null
	 */
	public function get_product_tiers( $product_id ) {
		$rule = $this->get_applicable_tier_rule( $product_id );
		if ( ! $rule || empty( $rule['tiers'] ) ) {
			return null;
		}

		$tiers = maybe_unserialize( $rule['tiers'] );
		if ( ! is_array( $tiers ) ) {
			return null;
		}

		usort( $tiers, function( $a, $b ) {
			return ( $a['min_quantity'] ?? 0 ) <=> ( $b['min_quantity'] ?? 0 );
		});

		return $tiers;
	}

	/**
	 * Check if product has tiered pricing
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function has_tiered_pricing( $product_id ) {
		return null !== $this->get_applicable_tier_rule( $product_id );
	}

	/**
	 * Display discount information in cart item price
	 *
	 * @param string $price_html Original price HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string Modified price HTML with discount info.
	 */
	public function display_cart_item_discount( $price_html, $cart_item, $cart_item_key ) {
		if ( ! isset( $cart_item['wdd_price_applied'] ) ) {
			return $price_html;
		}

		$original = wc_price( $cart_item['wdd_original_price'] ?? 0 );
		$discount = wc_price( $cart_item['wdd_discount_amount'] ?? 0 );
		$percent = number_format( $cart_item['wdd_discount_percent'] ?? 0, 0 );
		$rule = esc_html( $cart_item['wdd_rule_name'] ?? '' );

		return sprintf(
			'<div class="wdd-cart-price"><del>%s</del> %s</div><small class="wdd-discount-info"><strong>%s:</strong> %s<br>Save %s (%s%% off)</small>',
			$original,
			$price_html,
			__( 'Quantity Discount Applied', 'wow-dynamic-deals-for-woo' ),
			$rule,
			$discount,
			$percent
		);
	}
}

