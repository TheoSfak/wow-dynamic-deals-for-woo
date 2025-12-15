<?php
/**
 * Gift Engine
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Engines;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gift Engine class
 */
class GiftEngine extends RuleEngine {

	/**
	 * Gift meta key
	 *
	 * @var string
	 */
	const GIFT_META_KEY = '_wdd_is_gift';

	/**
	 * Flag to prevent infinite loops
	 *
	 * @var bool
	 */
	private static $processing = false;

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
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'add_free_gifts' ), 99 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'display_gift_price' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'prevent_gift_removal' ), 10, 2 );
	}

	/**
	 * Add free gifts to cart based on rules
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	public function add_free_gifts( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( self::$processing ) {
			return;
		}

		self::$processing = true;

		$settings = get_option( 'wdd_settings', array() );
		if ( empty( $settings['enable_gift_rules'] ) ) {
			self::$processing = false;
			return;
		}

		$this->remove_all_gifts( $cart );

		$rules = $this->get_applicable_gift_rules();

		if ( empty( $rules ) ) {
			self::$processing = false;
			return;
		}

		$context = $this->get_cart_context();

		foreach ( $rules as $rule ) {
			if ( ! $this->check_gift_triggers( $rule, $context ) ) {
				continue;
			}

			if ( ! $this->check_purchase_history( $rule ) ) {
				continue;
			}

			$this->add_gift_products( $rule, $cart );
		}

		self::$processing = false;
	}

	/**
	 * Get applicable gift rules
	 *
	 * @return array
	 */
	private function get_applicable_gift_rules() {
		$rules = $this->get_rules( 'gift_rules' );

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
	 * Check gift triggers
	 *
	 * @param array $rule Rule data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_gift_triggers( $rule, $context ) {
		$main_trigger_result = $this->check_single_trigger( $rule['trigger_type'] ?? '', $rule, $context );
		
		if ( empty( $rule['additional_triggers'] ) ) {
			return $main_trigger_result;
		}
		
		$additional_triggers = json_decode( $rule['additional_triggers'], true );
		if ( ! is_array( $additional_triggers ) || empty( $additional_triggers ) ) {
			return $main_trigger_result;
		}
		
		$trigger_logic = $rule['trigger_logic'] ?? 'and';
		
		$results = array( $main_trigger_result );
		
		foreach ( $additional_triggers as $trigger ) {
			$trigger_type = $trigger['type'] ?? '';
			$result = $this->check_single_trigger( $trigger_type, $trigger, $context );
			$results[] = $result;
		}
		
		if ( $trigger_logic === 'or' ) {
			return in_array( true, $results, true );
		} else {
			return ! in_array( false, $results, true );
		}
	}

	/**
	 * Check a single trigger condition
	 *
	 * @param string $trigger_type Type of trigger.
	 * @param array  $data Trigger data.
	 * @param array  $context Cart context.
	 * @return bool
	 */
	private function check_single_trigger( $trigger_type, $data, $context ) {
		switch ( $trigger_type ) {
			case 'product':
				return $this->check_product_trigger( $data, $context );

			case 'category':
				return $this->check_category_trigger( $data, $context );

			case 'cart_total':
				return $this->check_cart_total_trigger( $data, $context );

			case 'cart_quantity':
				return $this->check_cart_quantity_trigger( $data, $context );

			default:
				return false;
		}
	}

	/**
	 * Check product trigger
	 *
	 * @param array $data Rule or trigger data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_product_trigger( $data, $context ) {
		$trigger_products = $data['trigger_products'] ?? $data['products'] ?? null;
		
		if ( empty( $trigger_products ) ) {
			return false;
		}

		if ( is_string( $trigger_products ) ) {
			$trigger_products = maybe_unserialize( $trigger_products );
		}
		
		if ( ! is_array( $trigger_products ) ) {
			return false;
		}

		$cart_products = $context['cart_products'] ?? array();
		return ! empty( array_intersect( $trigger_products, $cart_products ) );
	}

	/**
	 * Check category trigger
	 *
	 * @param array $data Rule or trigger data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_category_trigger( $data, $context ) {
		$trigger_categories = $data['trigger_categories'] ?? $data['categories'] ?? null;
		
		if ( empty( $trigger_categories ) ) {
			return false;
		}

		if ( is_string( $trigger_categories ) ) {
			$trigger_categories = maybe_unserialize( $trigger_categories );
		}
		
		if ( ! is_array( $trigger_categories ) ) {
			return false;
		}

		$cart_categories = $context['cart_categories'] ?? array();
		return ! empty( array_intersect( $trigger_categories, $cart_categories ) );
	}

	/**
	 * Check cart total trigger
	 *
	 * @param array $data Rule or trigger data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_cart_total_trigger( $data, $context ) {
		$trigger_amount = floatval( $data['trigger_amount'] ?? $data['amount'] ?? 0 );
		$cart_total = $context['cart_total'] ?? 0;

		return $cart_total >= $trigger_amount;
	}

	/**
	 * Check cart quantity trigger
	 *
	 * @param array $data Rule or trigger data.
	 * @param array $context Cart context.
	 * @return bool
	 */
	private function check_cart_quantity_trigger( $data, $context ) {
		$trigger_quantity = intval( $data['trigger_quantity'] ?? $data['quantity'] ?? 1 );
		$cart_quantity = $context['cart_quantity'] ?? 0;

		return $cart_quantity >= $trigger_quantity;
	}

	/**
	 * Check purchase history conditions
	 *
	 * @param array $rule Rule data.
	 * @return bool
	 */
	private function check_purchase_history( $rule ) {
		if ( empty( $rule['purchase_history_conditions'] ) ) {
			return true;
		}

		$conditions = maybe_unserialize( $rule['purchase_history_conditions'] );
		if ( ! is_array( $conditions ) ) {
			return true;
		}

		$purchase_history = \WDD\Plugin::get_instance()->get_component( 'purchase_history' );
		if ( ! $purchase_history ) {
			return true;
		}

		$user_id = get_current_user_id();

		if ( isset( $conditions['min_total_spent'] ) ) {
			$total_spent = $purchase_history->get_total_spent( $user_id );
			if ( $total_spent < floatval( $conditions['min_total_spent'] ) ) {
				return false;
			}
		}

		if ( isset( $conditions['min_order_count'] ) ) {
			$order_count = $purchase_history->get_order_count( $user_id );
			if ( $order_count < intval( $conditions['min_order_count'] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Add gift products to cart
	 *
	 * @param array    $rule Rule data.
	 * @param \WC_Cart $cart Cart object.
	 */
	private function add_gift_products( $rule, $cart ) {
		if ( empty( $rule['gift_products'] ) ) {
			return;
		}

		$gift_products = maybe_unserialize( $rule['gift_products'] );
		if ( ! is_array( $gift_products ) ) {
			return;
		}

		$max_gifts = intval( $rule['max_gifts_per_order'] ?? 1 );
		$added_count = 0;
		
		$rule_reason = $this->generate_gift_reason( $rule );

		foreach ( $gift_products as $gift_product_id ) {
			if ( $added_count >= $max_gifts ) {
				break;
			}

			$product = wc_get_product( $gift_product_id );
			if ( ! $product || ! $product->is_in_stock() ) {
				continue;
			}

			$cart_item_key = $cart->add_to_cart( $gift_product_id, 1 );

			if ( $cart_item_key ) {
				$cart->cart_contents[ $cart_item_key ][ self::GIFT_META_KEY ] = true;
				$cart->cart_contents[ $cart_item_key ]['wdd_gift_rule_id'] = $rule['id'];
				$cart->cart_contents[ $cart_item_key ]['wdd_gift_rule_title'] = $rule['title'] ?? '';
				$cart->cart_contents[ $cart_item_key ]['wdd_gift_rule_reason'] = $rule_reason;
				$added_count++;

				$this->debug_log( 'Gift added to cart', array(
					'rule_id'    => $rule['id'],
					'product_id' => $gift_product_id,
				));
			}
		}
	}
	
	/**
	 * Generate gift reason explanation
	 *
	 * @param array $rule Rule data.
	 * @return string
	 */
	private function generate_gift_reason( $rule ) {
		$reasons = array();
		$trigger_logic = $rule['trigger_logic'] ?? 'and';
		$logic_text = ( $trigger_logic === 'or' ) ? __( ' OR ', 'wow-dynamic-deals-for-woo' ) : __( ' AND ', 'wow-dynamic-deals-for-woo' );
		
		$main_reason = $this->generate_single_trigger_reason( $rule['trigger_type'] ?? '', $rule );
		if ( ! empty( $main_reason ) ) {
			$reasons[] = $main_reason;
		}
		
		if ( ! empty( $rule['additional_triggers'] ) ) {
			$additional_triggers = json_decode( $rule['additional_triggers'], true );
			if ( is_array( $additional_triggers ) ) {
				foreach ( $additional_triggers as $trigger ) {
					$trigger_reason = $this->generate_single_trigger_reason( $trigger['type'] ?? '', $trigger );
					if ( ! empty( $trigger_reason ) ) {
						$reasons[] = $trigger_reason;
					}
				}
			}
		}
		
		if ( count( $reasons ) > 1 ) {
			return implode( $logic_text, $reasons );
		} elseif ( count( $reasons ) === 1 ) {
			return $reasons[0];
		}
		
		return '';
	}
	
	/**
	 * Generate reason for a single trigger
	 *
	 * @param string $trigger_type Trigger type.
	 * @param array  $data Trigger data.
	 * @return string
	 */
	private function generate_single_trigger_reason( $trigger_type, $data ) {
		$reason = '';
		
		switch ( $trigger_type ) {
			case 'product':
				$trigger_products = $data['trigger_products'] ?? $data['products'] ?? null;
				if ( is_string( $trigger_products ) ) {
					$trigger_products = maybe_unserialize( $trigger_products );
				}
				if ( is_array( $trigger_products ) && count( $trigger_products ) > 0 ) {
					$product_names = array();
					foreach ( array_slice( $trigger_products, 0, 2 ) as $product_id ) {
						$product = wc_get_product( $product_id );
						if ( $product ) {
							$product_names[] = $product->get_name();
						}
					}
					if ( ! empty( $product_names ) ) {
						$reason = sprintf( __( 'purchasing: %s', 'wow-dynamic-deals-for-woo' ), implode( ', ', $product_names ) );
						if ( count( $trigger_products ) > 2 ) {
							$reason .= ' ' . __( 'and more', 'wow-dynamic-deals-for-woo' );
						}
					}
				}
				break;
				
			case 'category':
				$trigger_categories = $data['trigger_categories'] ?? $data['categories'] ?? null;
				if ( is_string( $trigger_categories ) ) {
					$trigger_categories = maybe_unserialize( $trigger_categories );
				}
				if ( is_array( $trigger_categories ) && count( $trigger_categories ) > 0 ) {
					$category_names = array();
					foreach ( array_slice( $trigger_categories, 0, 2 ) as $cat_id ) {
						$term = get_term( $cat_id, 'product_cat' );
						if ( $term && ! is_wp_error( $term ) ) {
							$category_names[] = $term->name;
						}
					}
					if ( ! empty( $category_names ) ) {
						$reason = sprintf( __( 'purchasing from: %s', 'wow-dynamic-deals-for-woo' ), implode( ', ', $category_names ) );
						if ( count( $trigger_categories ) > 2 ) {
							$reason .= ' ' . __( 'and more', 'wow-dynamic-deals-for-woo' );
						}
					}
				}
				break;
				
			case 'cart_total':
				$trigger_amount = floatval( $data['trigger_amount'] ?? $data['amount'] ?? 0 );
				if ( $trigger_amount > 0 ) {
					$reason = sprintf( __( 'cart total over %s', 'wow-dynamic-deals-for-woo' ), wc_price( $trigger_amount ) );
				}
				break;
				
			case 'cart_quantity':
				$trigger_quantity = intval( $data['trigger_quantity'] ?? $data['quantity'] ?? 0 );
				if ( $trigger_quantity > 0 ) {
					$reason = sprintf( __( 'buying %d or more items', 'wow-dynamic-deals-for-woo' ), $trigger_quantity );
				}
				break;
		}
		
		return $reason;
	}

	/**
	 * Remove all gift products from cart
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	private function remove_all_gifts( $cart ) {
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( ! empty( $cart_item[ self::GIFT_META_KEY ] ) ) {
				$cart->remove_cart_item( $cart_item_key );
			}
		}
	}

	/**
	 * Display gift price as FREE
	 *
	 * @param string $price Price HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function display_gift_price( $price, $cart_item, $cart_item_key ) {
		if ( ! empty( $cart_item[ self::GIFT_META_KEY ] ) ) {
			return '<span class="wdd-gift-price">' . __( 'FREE', 'wow-dynamic-deals-for-woo' ) . '</span>';
		}

		return $price;
	}

	/**
	 * Prevent manual removal of gift items
	 *
	 * @param string $link Remove link HTML.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	public function prevent_gift_removal( $link, $cart_item_key ) {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );

		if ( ! empty( $cart_item[ self::GIFT_META_KEY ] ) ) {
			return '<span class="wdd-gift-badge">🎁 GIFT</span>';
		}

		return $link;
	}

	/**
	 * Get active gifts in cart
	 *
	 * @return array
	 */
	public function get_active_gifts() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return array();
		}

		$gifts = array();
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			if ( ! empty( $cart_item[ self::GIFT_META_KEY ] ) ) {
				$gifts[] = $cart_item;
			}
		}

		return $gifts;
	}
}

