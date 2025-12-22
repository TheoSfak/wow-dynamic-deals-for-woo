<?php
/**
 * Base Rule Engine
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD\Engines;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rule Engine Base Class
 */
class RuleEngine {

	/**
	 * Cache manager instance
	 *
	 * @var \WDD\CacheManager
	 */
	protected $cache_manager;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->cache_manager = \WDD\Plugin::get_instance()->get_component( 'cache' );
	}

	/**
	 * Get rules from database with caching
	 *
	 * @param string $table_name Table name.
	 * @param array  $conditions Additional WHERE conditions.
	 * @return array
	 */
	protected function get_rules( $table_name, $conditions = array() ) {
		global $wpdb;

		$cache_key = 'wdd_rules_' . $table_name . '_' . md5( wp_json_encode( $conditions ) );

		$rules = wp_cache_get( $cache_key, 'wdd_rules' );

		if ( false !== $rules ) {
			return $rules;
		}

		$where = array( "status = 'active'" );

		foreach ( $conditions as $key => $value ) {
			if ( is_array( $value ) ) {
				$placeholders = implode( ', ', array_fill( 0, count( $value ), '%s' ) );
				$where[]      = $wpdb->prepare( "$key IN ($placeholders)", ...$value ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			} else {
				$where[] = $wpdb->prepare( "$key = %s", $value ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			}
		}

		$where_clause = implode( ' AND ', $where );
		$table_full_name = $wpdb->prefix . 'wdd_' . $table_name;

		$rules = $wpdb->get_results(
			"SELECT * FROM {$table_full_name} WHERE {$where_clause} ORDER BY priority ASC, id ASC", // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			ARRAY_A
		);

		wp_cache_set( $cache_key, $rules, 'wdd_rules', HOUR_IN_SECONDS );

		return $rules;
	}

	/**
	 * Sort rules by priority
	 *
	 * @param array $rules Array of rules.
	 * @return array
	 */
	protected function sort_by_priority( $rules ) {
		usort(
			$rules,
			function( $a, $b ) {
				if ( $a['priority'] === $b['priority'] ) {
					return $a['id'] <=> $b['id'];
				}
				return $a['priority'] <=> $b['priority'];
			}
		);

		return $rules;
	}

	/**
	 * Check if rule is valid for current date/time
	 *
	 * @param array $rule Rule data.
	 * @return bool
	 */
	protected function is_valid_datetime( $rule ) {
		$now = current_time( 'timestamp' );
		$rule_id = $rule['id'] ?? 'unknown';
		

		if ( ! empty( $rule['date_from'] ) && $rule['date_from'] !== '0000-00-00' && $rule['date_from'] !== '0000-00-00 00:00:00' ) {
			$date_from = strtotime( $rule['date_from'] );
			if ( $date_from !== false && $now < $date_from ) {
				return false;
			}
		} else {
		}

		if ( ! empty( $rule['date_to'] ) && $rule['date_to'] !== '0000-00-00' && $rule['date_to'] !== '0000-00-00 00:00:00' ) {
			$date_to = strtotime( $rule['date_to'] );
			if ( $date_to !== false && $now > $date_to ) {
				return false;
			}
		} else {
		}

		if ( ! empty( $rule['days_of_week'] ) ) {
			$days_of_week = maybe_unserialize( $rule['days_of_week'] );
			if ( is_array( $days_of_week ) ) {
				$current_day = gmdate( 'N', $now ); // 1 (Monday) through 7 (Sunday).
				if ( ! in_array( $current_day, $days_of_week, true ) ) {
					return false;
				}
			}
		}

		if ( ! empty( $rule['time_from'] ) && ! empty( $rule['time_to'] ) ) {
			if ( $rule['time_from'] === '00:00:00' && $rule['time_to'] === '00:00:00' ) {
			} else {
				$current_time = gmdate( 'H:i:s', $now );
				if ( $current_time < $rule['time_from'] || $current_time > $rule['time_to'] ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Check if rule applies to current user
	 *
	 * @param array $rule Rule data.
	 * @return bool
	 */
	protected function is_valid_user( $rule ) {
		$current_user_id = get_current_user_id();

		if ( ! empty( $rule['user_ids'] ) ) {
			$user_ids = maybe_unserialize( $rule['user_ids'] );
			if ( is_array( $user_ids ) && ! empty( $user_ids ) ) {
				if ( ! in_array( $current_user_id, array_map( 'intval', $user_ids ), true ) ) {
					return false;
				}
			}
		}

		if ( ! empty( $rule['user_roles'] ) ) {
			$user_roles = maybe_unserialize( $rule['user_roles'] );
			if ( is_array( $user_roles ) && ! empty( $user_roles ) ) {
				$user = wp_get_current_user();
				if ( ! array_intersect( $user_roles, $user->roles ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Evaluate conditions with AND/OR logic
	 *
	 * @param array  $conditions Array of conditions.
	 * @param array  $context Context data for evaluation.
	 * @param string $logic Logic type: 'AND' or 'OR'.
	 * @return bool
	 */
	protected function evaluate_conditions( $conditions, $context = array(), $logic = 'AND' ) {
		if ( empty( $conditions ) ) {
			return true;
		}

		$results = array();

		foreach ( $conditions as $condition ) {
			$result = $this->evaluate_single_condition( $condition, $context );
			$results[] = $result;

			if ( 'AND' === $logic && ! $result ) {
				return false;
			}
			if ( 'OR' === $logic && $result ) {
				return true;
			}
		}

		return 'AND' === $logic ? ! in_array( false, $results, true ) : in_array( true, $results, true );
	}

	/**
	 * Evaluate single condition
	 *
	 * @param array $condition Condition data.
	 * @param array $context Context data.
	 * @return bool
	 */
	protected function evaluate_single_condition( $condition, $context ) {
		$type     = $condition['type'] ?? '';
		$operator = $condition['operator'] ?? '==';
		$value    = $condition['value'] ?? '';

		switch ( $type ) {
			case 'cart_total':
				return $this->compare( $context['cart_total'] ?? 0, $value, $operator );

			case 'cart_quantity':
				return $this->compare( $context['cart_quantity'] ?? 0, $value, $operator );

			case 'product_in_cart':
				$cart_products = $context['cart_products'] ?? array();
				return in_array( $value, $cart_products, true );

			case 'category_in_cart':
				$cart_categories = $context['cart_categories'] ?? array();
				return in_array( $value, $cart_categories, true );

			case 'user_logged_in':
				return is_user_logged_in();

			case 'user_role':
				$user = wp_get_current_user();
				return in_array( $value, $user->roles, true );

			case 'purchase_count':
				$purchase_history = \WDD\Plugin::get_instance()->get_component( 'purchase_history' );
				$count = $purchase_history ? $purchase_history->get_order_count( get_current_user_id() ) : 0;
				return $this->compare( $count, $value, $operator );

			case 'total_spent':
				$purchase_history = \WDD\Plugin::get_instance()->get_component( 'purchase_history' );
				$spent = $purchase_history ? $purchase_history->get_total_spent( get_current_user_id() ) : 0;
				return $this->compare( $spent, $value, $operator );

			default:
				return apply_filters( 'wdd_evaluate_custom_condition', false, $condition, $context );
		}
	}

	/**
	 * Compare values with operator
	 *
	 * @param mixed  $left Left value.
	 * @param mixed  $right Right value.
	 * @param string $operator Comparison operator.
	 * @return bool
	 */
	protected function compare( $left, $right, $operator ) {
		switch ( $operator ) {
			case '==':
			case 'equals':
				return $left == $right; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

			case '!=':
			case 'not_equals':
				return $left != $right; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

			case '>':
			case 'greater_than':
				return $left > $right;

			case '>=':
			case 'greater_than_or_equal':
				return $left >= $right;

			case '<':
			case 'less_than':
				return $left < $right;

			case '<=':
			case 'less_than_or_equal':
				return $left <= $right;

			case 'in':
				return is_array( $right ) && in_array( $left, $right, true );

			case 'not_in':
				return is_array( $right ) && ! in_array( $left, $right, true );

			case 'contains':
				return false !== strpos( (string) $left, (string) $right );

			case 'not_contains':
				return false === strpos( (string) $left, (string) $right );

			default:
				return false;
		}
	}

	/**
	 * Check if rule applies to product
	 *
	 * @param array $rule Rule data.
	 * @param int   $product_id Product ID.
	 * @return bool
	 */
	protected function is_valid_product( $rule, $product_id ) {
		if ( ! empty( $rule['product_ids'] ) ) {
			$product_ids = maybe_unserialize( $rule['product_ids'] );
			if ( is_array( $product_ids ) && ! empty( $product_ids ) ) {
				if ( ! in_array( $product_id, array_map( 'intval', $product_ids ), true ) ) {
					return false;
				}
			}
		}

		if ( ! empty( $rule['category_ids'] ) ) {
			$category_ids = maybe_unserialize( $rule['category_ids'] );
			if ( is_array( $category_ids ) && ! empty( $category_ids ) ) {
				$category_ids = array_map( 'intval', $category_ids );
				$product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
				
				if ( ! array_intersect( $category_ids, $product_categories ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get cart context for condition evaluation
	 *
	 * @return array
	 */
	protected function get_cart_context() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return array();
		}

		$cart = WC()->cart;
		$cart_products = array();
		$cart_categories = array();

		foreach ( $cart->get_cart() as $cart_item ) {
			$cart_products[] = $cart_item['product_id'];
			$categories = wp_get_post_terms( $cart_item['product_id'], 'product_cat', array( 'fields' => 'ids' ) );
			$cart_categories = array_merge( $cart_categories, $categories );
		}

		return array(
			'cart_total'      => $cart->get_cart_contents_total(),
			'cart_quantity'   => $cart->get_cart_contents_count(),
			'cart_products'   => array_unique( $cart_products ),
			'cart_categories' => array_unique( $cart_categories ),
		);
	}

	/**
	 * Log debug information
	 *
	 * @param string $message Message to log.
	 * @param array  $data Additional data.
	 */
	protected function debug_log( $message, $data = array() ) {
		$settings = get_option( 'wdd_settings', array() );
		if ( ! empty( $settings['debug_mode'] ) ) {
		}
	}
}
