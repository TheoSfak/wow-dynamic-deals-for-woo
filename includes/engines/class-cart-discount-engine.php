<?php
/**
 * Cart Discount Engine
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Engines;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cart Discount Engine class
 */
class CartDiscountEngine extends RuleEngine {

	/**
	 * Processing flag to prevent infinite loops
	 *
	 * @var bool
	 */
	private $processing = false;

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
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'apply_cart_discounts' ), 20 );
		add_filter( 'woocommerce_package_rates', array( $this, 'apply_free_shipping' ), 10, 2 );
		add_filter( 'woocommerce_shipping_free_shipping_is_available', array( $this, 'enable_free_shipping' ), 10, 3 );
	}

	/**
	 * Apply cart-level discounts
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	public function apply_cart_discounts( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['enable_cart_discounts'] ) ) {
			return;
		}

		$rules = $this->get_applicable_cart_rules();

		if ( empty( $rules ) ) {
			return;
		}

		$context = $this->get_cart_context();
		
		$settings = get_option( 'woo_dynamic_deals_settings', array() );
		$cart_discount_color = ! empty( $settings['cart_discount_color'] ) ? $settings['cart_discount_color'] : '#10b981';
		$free_shipping_text = ! empty( $settings['free_shipping_text'] ) ? $settings['free_shipping_text'] : 'Free Shipping';
		$free_shipping_color = ! empty( $settings['free_shipping_color'] ) ? $settings['free_shipping_color'] : '#4caf50';

		foreach ( $rules as $rule ) {
			if ( ! $this->check_cart_conditions( $rule, $context ) ) {
				continue;
			}

			$discount_amount = $this->calculate_discount_amount( $rule, $context );

			if ( $discount_amount > 0 ) {
				$discount_label = '<span style="color: ' . esc_attr( $cart_discount_color ) . ';">' . esc_html( $rule['title'] ) . '</span>';
				
				$cart->add_fee(
					$discount_label,
					-$discount_amount,
					false
				);

				$this->debug_log( 'Cart discount applied', array(
					'rule_id' => $rule['id'],
					'amount'  => $discount_amount,
				));
			}
			
			if ( ! empty( $rule['apply_free_shipping'] ) ) {
				
				$shipping_total = floatval( WC()->cart->get_shipping_total() );
				$shipping_tax = floatval( WC()->cart->get_shipping_tax() );
				$total_shipping = $shipping_total + $shipping_tax;
				
				
				if ( $total_shipping > 0 ) {
					$free_shipping_label = '<span style="color: ' . esc_attr( $free_shipping_color ) . ';">' . 
						sprintf( __( '%s (%s discount)', 'woo-dynamic-deals' ), esc_html( $free_shipping_text ), esc_html( $rule['title'] ) ) . 
						'</span>';
					
					$cart->add_fee(
						$free_shipping_label,
						-$total_shipping,
						false
					);
				}
			}

			if ( ! empty( $rule['stop_further_rules'] ) ) {
				break;
			}
		}
	}

	/**
	 * Apply free shipping based on rules
	 *
	 * @param array $rates Shipping rates.
	 * @param array $package Shipping package (optional).
	 * @return array
	 */
	public function apply_free_shipping( $rates, $package = array() ) {
		
		$rules = $this->get_applicable_cart_rules();

		if ( empty( $rules ) ) {
			return $rates;
		}


		$context = $this->get_cart_context();
		$has_free_shipping = false;

		foreach ( $rules as $rule ) {
			
			if ( empty( $rule['apply_free_shipping'] ) ) {
				continue;
			}

			if ( ! $this->check_cart_conditions( $rule, $context ) ) {
				continue;
			}

			$has_free_shipping = true;
			break;
		}

		if ( $has_free_shipping ) {
			
			$rates = array();
			
			$free_shipping_rate = new \WC_Shipping_Rate(
				'wdd_free_shipping',
				__( 'Free Shipping (Discount Applied)', 'woo-dynamic-deals' ),
				0,
				array(),
				'wdd_free_shipping'
			);

			$rates['wdd_free_shipping'] = $free_shipping_rate;
		}

		return $rates;
	}

	/**
	 * Enable free shipping based on cart discount rules
	 *
	 * @param bool   $is_available Whether free shipping is available.
	 * @param array  $package Shipping package.
	 * @param object $shipping_method Shipping method instance.
	 * @return bool
	 */
	public function enable_free_shipping( $is_available, $package, $shipping_method ) {
		
		$rules = $this->get_applicable_cart_rules();

		if ( empty( $rules ) ) {
			return $is_available;
		}

		$context = $this->get_cart_context();

		foreach ( $rules as $rule ) {
			if ( empty( $rule['apply_free_shipping'] ) ) {
				continue;
			}

			if ( $this->check_cart_conditions( $rule, $context ) ) {
				return true;
			}
		}

		return $is_available;
	}

	/**
	 * Get applicable cart discount rules
	 *
	 * @return array
	 */
	private function get_applicable_cart_rules() {
		$rules = $this->get_rules( 'cart_discount_rules' );

		if ( empty( $rules ) ) {
			return array();
		}

		$applicable = array();

		foreach ( $rules as $rule ) {
			if ( ! $this->is_valid_datetime( $rule ) ) {
				continue;
			}

			if ( ! $this->is_valid_user( $rule ) ) {
				continue;
			}

			$applicable[] = $rule;
		}

		return $this->sort_by_priority( $applicable );
	}

	/**
	 * Check cart conditions for rule
	 *
	 * @param array $rule Rule data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_cart_conditions( $rule, $context ) {
		if ( ! empty( $rule['first_order_only'] ) && ! $this->is_first_order() ) {
			return false;
		}

		if ( ! empty( $rule['min_cart_total'] ) && $context['cart_total'] < floatval( $rule['min_cart_total'] ) ) {
			return false;
		}

		if ( ! empty( $rule['max_cart_total'] ) && $context['cart_total'] > floatval( $rule['max_cart_total'] ) ) {
			return false;
		}

		if ( ! empty( $rule['min_cart_quantity'] ) && $context['cart_quantity'] < intval( $rule['min_cart_quantity'] ) ) {
			return false;
		}

		if ( ! empty( $rule['max_cart_quantity'] ) && $context['cart_quantity'] > intval( $rule['max_cart_quantity'] ) ) {
			return false;
		}

		if ( ! empty( $rule['conditions'] ) ) {
			$conditions = maybe_unserialize( $rule['conditions'] );
			if ( is_array( $conditions ) ) {
				$logic = $conditions['logic'] ?? 'AND';
				if ( ! $this->evaluate_conditions( $conditions['rules'] ?? array(), $context, $logic ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Calculate discount amount
	 *
	 * @param array $rule Rule data.
	 * @param array $context Cart context.
	 * @return float
	 */
	private function calculate_discount_amount( $rule, $context ) {
		$discount_type  = $rule['discount_type'] ?? '';
		$discount_value = floatval( $rule['discount_value'] ?? 0 );

		if ( empty( $discount_type ) || 0 === $discount_value ) {
			return 0;
		}

		$cart_total = $context['cart_total'];

		switch ( $discount_type ) {
			case 'percentage':
				return ( $cart_total * $discount_value ) / 100;

			case 'fixed':
				return min( $discount_value, $cart_total );

			default:
				return apply_filters( 'wdd_calculate_custom_cart_discount', 0, $rule, $context );
		}
	}

	/**
	 * Get active cart discounts (for display)
	 *
	 * @return array
	 */
	public function get_active_discounts() {
		$rules = $this->get_applicable_cart_rules();
		$context = $this->get_cart_context();
		$active = array();

		foreach ( $rules as $rule ) {
			if ( $this->check_cart_conditions( $rule, $context ) ) {
				$active[] = $rule;
			}
		}

		return $active;
	}

	/**
	 * Check if free shipping is available
	 *
	 * @return bool
	 */
	public function has_free_shipping() {
		$rules = $this->get_applicable_cart_rules();
		$context = $this->get_cart_context();

		foreach ( $rules as $rule ) {
			if ( ! empty( $rule['free_shipping'] ) && $this->check_cart_conditions( $rule, $context ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if this is customer's first order
	 *
	 * @return bool
	 */
	private function is_first_order() {
		$user_id = get_current_user_id();
		$billing_email = WC()->customer ? WC()->customer->get_billing_email() : '';

		if ( $user_id > 0 ) {
			$orders = wc_get_orders( array(
				'customer_id' => $user_id,
				'status'      => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
				'limit'       => 1,
			) );

			return empty( $orders );
		}

		if ( ! empty( $billing_email ) ) {
			$orders = wc_get_orders( array(
				'billing_email' => $billing_email,
				'status'        => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
				'limit'         => 1,
			) );

			return empty( $orders );
		}

		return true;
	}
}

