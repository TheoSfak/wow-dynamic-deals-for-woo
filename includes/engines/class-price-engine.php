<?php
/**
 * Price Engine
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Engines;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Price Engine class
 */
class PriceEngine extends RuleEngine {

	/**
	 * Processing flag to prevent infinite loops
	 *
	 * @var array
	 */
	private $processing = array();

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
		add_filter( 'woocommerce_product_get_price', array( $this, 'adjust_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'adjust_regular_price' ), 99, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'adjust_sale_price' ), 99, 2 );

		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'adjust_product_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'adjust_regular_price' ), 99, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'adjust_sale_price' ), 99, 2 );
	}

	/**
	 * Adjust product price based on rules
	 *
	 * @param float      $price Product price.
	 * @param \WC_Product $product Product object.
	 * @return float
	 */
	public function adjust_product_price( $price, $product ) {
		if ( empty( $price ) || ! is_numeric( $price ) ) {
			return $price;
		}

		$product_id = $product->get_id();
		
		if ( isset( $this->processing[ $product_id ] ) ) {
			return $price;
		}
		
		$this->processing[ $product_id ] = true;

		$settings = get_option( 'wdd_settings', array() );
		
		if ( empty( $settings['enable_price_rules'] ) ) {
			unset( $this->processing[ $product_id ] );
			return $price;
		}

		$rules = $this->get_applicable_rules( $product_id );

		if ( empty( $rules ) ) {
			unset( $this->processing[ $product_id ] );
			return $price;
		}

		foreach ( $rules as $rule ) {
			$new_price = $this->calculate_adjusted_price( $price, $product, $rule );

			if ( false !== $new_price ) {
				$price = $new_price;
				$this->debug_log( 'Price adjusted', array(
					'product_id' => $product_id,
					'rule_id'    => $rule['id'],
					'old_price'  => $price,
					'new_price'  => $new_price,
				) );

				if ( ! empty( $rule['stop_further_rules'] ) ) {
					break;
				}
			}
		}

		unset( $this->processing[ $product_id ] );
		return $price;
	}

	/**
	 * Adjust regular price
	 *
	 * @param float      $price Regular price.
	 * @param \WC_Product $product Product object.
	 * @return float
	 */
	public function adjust_regular_price( $price, $product ) {
		$rules = $this->get_applicable_rules( $product->get_id(), 'regular_price' );

		if ( empty( $rules ) ) {
			return $price;
		}

		foreach ( $rules as $rule ) {
			$new_price = $this->calculate_adjusted_price( $price, $product, $rule );
			if ( false !== $new_price ) {
				$price = $new_price;
			}
		}

		return $price;
	}

	/**
	 * Adjust sale price
	 *
	 * @param float      $price Sale price.
	 * @param \WC_Product $product Product object.
	 * @return float
	 */
	public function adjust_sale_price( $price, $product ) {
		$rules = $this->get_applicable_rules( $product->get_id(), 'sale_price' );

		if ( empty( $rules ) ) {
			return $price;
		}

		foreach ( $rules as $rule ) {
			$new_price = $this->calculate_adjusted_price( $price, $product, $rule );
			if ( false !== $new_price ) {
				$price = $new_price;
			}
		}

		return $price;
	}

	/**
	 * Get applicable rules for product
	 *
	 * @param int    $product_id Product ID.
	 * @param string $price_type Price type filter.
	 * @return array
	 */
	private function get_applicable_rules( $product_id, $price_type = null ) {
		$rules = $this->get_rules( 'pricing_rules' );

		if ( empty( $rules ) ) {
			return array();
		}

		$applicable = array();

		foreach ( $rules as $rule ) {
			$rule_id = $rule['id'] ?? 'unknown';
			$rule_name = $rule['name'] ?? 'unnamed';
			
			if ( null !== $price_type && ! empty( $rule['apply_to'] ) && $rule['apply_to'] !== $price_type ) {
				continue;
			}

			if ( ! $this->is_valid_datetime( $rule ) ) {
				continue;
			}

			if ( ! $this->is_valid_user( $rule ) ) {
				continue;
			}

			if ( ! $this->is_valid_product( $rule, $product_id ) ) {
				continue;
			}
			

			if ( ! empty( $rule['conditions'] ) ) {
				$conditions = maybe_unserialize( $rule['conditions'] );
				if ( is_array( $conditions ) ) {
					$logic   = $conditions['logic'] ?? 'AND';
					$context = $this->get_cart_context();

					if ( ! $this->evaluate_conditions( $conditions['rules'] ?? array(), $context, $logic ) ) {
						continue;
					}
				}
			}

			$applicable[] = $rule;
		}

		return $this->sort_by_priority( $applicable );
	}

	/**
	 * Calculate adjusted price based on rule
	 *
	 * @param float      $original_price Original price.
	 * @param \WC_Product $product Product object.
	 * @param array      $rule Rule data.
	 * @return float|false
	 */
	private function calculate_adjusted_price( $original_price, $product, $rule ) {
		if ( empty( $original_price ) || $original_price <= 0 ) {
			return false;
		}

		$adjustment_type  = $rule['adjustment_type'] ?? '';
		$adjustment_value = floatval( $rule['adjustment_value'] ?? 0 );

		if ( empty( $adjustment_type ) || 0 === $adjustment_value ) {
			return false;
		}

		switch ( $adjustment_type ) {
			case 'fixed_price':
				$new_price = max( 0, $adjustment_value );
				return $new_price;

			case 'percentage_discount':
				$discount = ( $original_price * $adjustment_value ) / 100;
				$new_price = max( 0, $original_price - $discount );
				return $new_price;

			case 'fixed_discount':
				$new_price = max( 0, $original_price - $adjustment_value );
				return $new_price;

			case 'percentage_increase':
				$increase = ( $original_price * $adjustment_value ) / 100;
				return $original_price + $increase;

			case 'fixed_increase':
				return $original_price + $adjustment_value;

			default:
				return apply_filters( 'wdd_calculate_custom_price_adjustment', false, $original_price, $product, $rule );
		}
	}

	/**
	 * Get active rule for product (for display purposes)
	 *
	 * @param int $product_id Product ID.
	 * @return array|null
	 */
	public function get_active_rule_for_product( $product_id ) {
		$rules = $this->get_applicable_rules( $product_id );
		return ! empty( $rules ) ? $rules[0] : null;
	}

	/**
	 * Check if product has active pricing rule
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function has_active_rule( $product_id ) {
		return ! empty( $this->get_applicable_rules( $product_id ) );
	}
}

