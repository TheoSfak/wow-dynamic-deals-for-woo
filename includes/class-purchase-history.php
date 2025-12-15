<?php
/**
 * Purchase History Tracker
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Purchase History class
 */
class PurchaseHistory {

	/**
	 * Cache expiration (24 hours)
	 *
	 * @var int
	 */
	const CACHE_EXPIRATION = DAY_IN_SECONDS;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_loaded', array( $this, 'init_hooks' ) );
	}

	/**
	 * Initialize hooks after WooCommerce is loaded
	 */
	public function init_hooks() {
		add_action( 'woocommerce_order_status_changed', array( $this, 'invalidate_user_cache' ), 10, 3 );
	}

	/**
	 * Get total number of completed orders for user
	 *
	 * @param int $user_id User ID.
	 * @return int
	 */
	public function get_order_count( $user_id ) {
		if ( ! $user_id ) {
			return 0;
		}

		$cache_key = 'wdd_order_count_' . $user_id;
		$count = get_transient( $cache_key );

		if ( false !== $count ) {
			return intval( $count );
		}

		$orders = wc_get_orders( array(
			'customer_id' => $user_id,
			'status'      => array( 'completed', 'processing' ),
			'limit'       => -1,
			'return'      => 'ids',
		));

		$count = count( $orders );
		set_transient( $cache_key, $count, self::CACHE_EXPIRATION );

		return $count;
	}

	/**
	 * Get total amount spent by user
	 *
	 * @param int $user_id User ID.
	 * @return float
	 */
	public function get_total_spent( $user_id ) {
		if ( ! $user_id ) {
			return 0.0;
		}

		$cache_key = 'wdd_total_spent_' . $user_id;
		$total = get_transient( $cache_key );

		if ( false !== $total ) {
			return floatval( $total );
		}

		$customer = new \WC_Customer( $user_id );
		$total = $customer->get_total_spent();

		set_transient( $cache_key, $total, self::CACHE_EXPIRATION );

		return floatval( $total );
	}

	/**
	 * Check if user has purchased a specific product
	 *
	 * @param int $user_id User ID.
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function has_purchased_product( $user_id, $product_id ) {
		if ( ! $user_id || ! $product_id ) {
			return false;
		}

		$cache_key = 'wdd_purchased_' . $user_id . '_' . $product_id;
		$has_purchased = get_transient( $cache_key );

		if ( false !== $has_purchased ) {
			return (bool) $has_purchased;
		}

		$has_purchased = wc_customer_bought_product( '', $user_id, $product_id );

		set_transient( $cache_key, $has_purchased ? 1 : 0, self::CACHE_EXPIRATION );

		return $has_purchased;
	}

	/**
	 * Get products purchased by user
	 *
	 * @param int $user_id User ID.
	 * @return array Product IDs.
	 */
	public function get_purchased_products( $user_id ) {
		if ( ! $user_id ) {
			return array();
		}

		$cache_key = 'wdd_purchased_products_' . $user_id;
		$products = get_transient( $cache_key );

		if ( false !== $products ) {
			return is_array( $products ) ? $products : array();
		}

		$orders = wc_get_orders( array(
			'customer_id' => $user_id,
			'status'      => array( 'completed', 'processing' ),
			'limit'       => -1,
		));

		$products = array();
		foreach ( $orders as $order ) {
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product(); // WC_Order_Item_Product method.
				if ( $product ) {
					$product_id = $product->get_id();
					if ( $product_id && ! in_array( $product_id, $products, true ) ) {
						$products[] = $product_id;
					}
				}
			}
		}

		set_transient( $cache_key, $products, self::CACHE_EXPIRATION );

		return $products;
	}

	/**
	 * Get categories of products purchased by user
	 *
	 * @param int $user_id User ID.
	 * @return array Category IDs.
	 */
	public function get_purchased_categories( $user_id ) {
		if ( ! $user_id ) {
			return array();
		}

		$cache_key = 'wdd_purchased_categories_' . $user_id;
		$categories = get_transient( $cache_key );

		if ( false !== $categories ) {
			return is_array( $categories ) ? $categories : array();
		}

		$products = $this->get_purchased_products( $user_id );
		$categories = array();

		foreach ( $products as $product_id ) {
			$product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
			if ( is_array( $product_categories ) ) {
				$categories = array_merge( $categories, $product_categories );
			}
		}

		$categories = array_unique( $categories );
		set_transient( $cache_key, $categories, self::CACHE_EXPIRATION );

		return $categories;
	}

	/**
	 * Get last order date for user
	 *
	 * @param int $user_id User ID.
	 * @return string|null Date string or null.
	 */
	public function get_last_order_date( $user_id ) {
		if ( ! $user_id ) {
			return null;
		}

		$cache_key = 'wdd_last_order_date_' . $user_id;
		$date = get_transient( $cache_key );

		if ( false !== $date ) {
			return $date ?: null;
		}

		$orders = wc_get_orders( array(
			'customer_id' => $user_id,
			'status'      => array( 'completed', 'processing' ),
			'limit'       => 1,
			'orderby'     => 'date',
			'order'       => 'DESC',
		));

		$date = null;
		if ( ! empty( $orders ) ) {
			$date = $orders[0]->get_date_created()->format( 'Y-m-d H:i:s' );
		}

		set_transient( $cache_key, $date ?: '', self::CACHE_EXPIRATION );

		return $date;
	}

	/**
	 * Invalidate cache for user
	 *
	 * @param int    $order_id Order ID.
	 * @param string $old_status Old status.
	 * @param string $new_status New status.
	 */
	public function invalidate_user_cache( $order_id, $old_status, $new_status ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id = $order->get_customer_id();
		if ( ! $user_id ) {
			return;
		}

		delete_transient( 'wdd_order_count_' . $user_id );
		delete_transient( 'wdd_total_spent_' . $user_id );
		delete_transient( 'wdd_purchased_products_' . $user_id );
		delete_transient( 'wdd_purchased_categories_' . $user_id );
		delete_transient( 'wdd_last_order_date_' . $user_id );

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product(); // WC_Order_Item_Product method.
			if ( $product ) {
				$product_id = $product->get_id();
				if ( $product_id ) {
					delete_transient( 'wdd_purchased_' . $user_id . '_' . $product_id );
				}
			}
		}
	}
}
