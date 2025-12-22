<?php
/**
 * Security & Validation
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Security class
 */
class Security {

	/**
	 * Nonce action
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'wdd_ajax_action';

	/**
	 * Nonce name
	 *
	 * @var string
	 */
	const NONCE_NAME = 'wdd_nonce';

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Verify nonce
	 *
	 * @param string $nonce Nonce value.
	 * @param string $action Action name (optional).
	 * @return bool
	 */
	public function verify_nonce( $nonce, $action = null ) {
		if ( null === $action ) {
			$action = self::NONCE_ACTION;
		}

		return (bool) wp_verify_nonce( $nonce, $action );
	}

	/**
	 * Create nonce
	 *
	 * @param string $action Action name (optional).
	 * @return string
	 */
	public function create_nonce( $action = null ) {
		if ( null === $action ) {
			$action = self::NONCE_ACTION;
		}

		return wp_create_nonce( $action );
	}

	/**
	 * Check user capability
	 *
	 * @param string $capability Capability to check.
	 * @return bool
	 */
	public function check_capability( $capability = 'manage_woocommerce' ) {
		return current_user_can( $capability );
	}

	/**
	 * Verify AJAX request
	 *
	 * @param string $capability Required capability.
	 * @return bool
	 */
	public function verify_ajax_request( $capability = 'manage_woocommerce' ) {
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! $this->verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ) ) ) {
			return false;
		}

		if ( ! $this->check_capability( $capability ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitize rule data
	 *
	 * @param array $data Rule data.
	 * @return array
	 */
	public function sanitize_rule_data( $data ) {
		$sanitized = array();

		$id_fields = array( 'rule_id', 'id' );
		foreach ( $id_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_text_field( $data[ $field ] );
			}
		}

		$text_fields = array( 'title', 'adjustment_type', 'discount_type', 'trigger_type', 'calculation_mode', 'apply_to', 'status', 'rule_type' );
		foreach ( $text_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_text_field( $data[ $field ] );
			}
		}

		$numeric_fields = array( 'priority', 'adjustment_value', 'discount_value', 'trigger_amount', 'trigger_quantity', 'max_gifts_per_order', 'min_cart_total', 'max_cart_total', 'min_cart_quantity', 'max_cart_quantity', 'trigger_cart_total', 'trigger_cart_quantity' );
		foreach ( $numeric_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$sanitized[ $field ] = floatval( $data[ $field ] );
			}
		}

		$boolean_fields = array( 'stop_further_rules', 'apply_free_shipping' );
		foreach ( $boolean_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$sanitized[ $field ] = (bool) $data[ $field ];
			}
		}

		$datetime_fields = array( 'date_from', 'date_to', 'time_from', 'time_to' );
		foreach ( $datetime_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_text_field( $data[ $field ] );
			}
		}

		$array_fields = array( 'product_ids', 'category_ids', 'user_roles', 'user_ids', 'days_of_week', 'tiers', 'conditions', 'trigger_products', 'trigger_categories', 'gift_products', 'purchase_history_conditions' );
		foreach ( $array_fields as $field ) {
			if ( isset( $data[ $field ] ) ) {
				if ( is_array( $data[ $field ] ) ) {
					if ( $field === 'tiers' && isset( $data[ $field ][0] ) && is_array( $data[ $field ][0] ) ) {
						$sanitized[ $field ] = array();
						foreach ( $data[ $field ] as $tier ) {
							$sanitized_tier = array();
							foreach ( $tier as $tier_key => $tier_value ) {
								$sanitized_tier[ $tier_key ] = sanitize_text_field( $tier_value );
							}
							$sanitized[ $field ][] = $sanitized_tier;
						}
					} else {
						$sanitized[ $field ] = array_map( 'sanitize_text_field', $data[ $field ] );
					}
				} else {
					$sanitized[ $field ] = array();
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize POST data
	 *
	 * @param array $data POST data.
	 * @return array
	 */
	public function sanitize_post_data( $data ) {
		$sanitized = array();

		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$sanitized[ $key ] = $this->sanitize_post_data( $value );
			} else {
				$sanitized[ $key ] = sanitize_text_field( $value );
			}
		}

		return $sanitized;
	}

	/**
	 * Validate product ID
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function validate_product_id( $product_id ) {
		$product_id = intval( $product_id );
		if ( $product_id <= 0 ) {
			return false;
		}

		$product = wc_get_product( $product_id );
		return $product && $product->exists();
	}

	/**
	 * Validate user ID
	 *
	 * @param int $user_id User ID.
	 * @return bool
	 */
	public function validate_user_id( $user_id ) {
		$user_id = intval( $user_id );
		if ( $user_id <= 0 ) {
			return false;
		}

		$user = get_user_by( 'id', $user_id );
		return $user && $user->exists();
	}

	/**
	 * Rate limit check (simple implementation)
	 *
	 * @param string $action Action name.
	 * @param int    $limit Max requests.
	 * @param int    $period Time period in seconds.
	 * @return bool True if allowed, false if rate limited.
	 */
	public function check_rate_limit( $action, $limit = 10, $period = 60 ) {
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			return false;
		}

		$transient_key = 'wdd_rate_' . $action . '_' . $user_id;
		$attempts = get_transient( $transient_key );

		if ( false === $attempts ) {
			set_transient( $transient_key, 1, $period );
			return true;
		}

		if ( $attempts >= $limit ) {
			return false;
		}

		set_transient( $transient_key, $attempts + 1, $period );
		return true;
	}
}
