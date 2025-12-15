<?php
/**
 * AJAX Handler
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
 * AJAX Handler class
 */
class AjaxHandler {

	/**
	 * Security instance
	 *
	 * @var \WDD\Security
	 */
	private $security;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Don't access security component in constructor
		// $this->security = \WDD\Plugin::get_instance()->get_component( 'security' );
		$this->init_hooks();
	}

	/**
	 * Get security instance
	 *
	 * @return \WDD\Security|null
	 */
	private function get_security() {
		if ( ! $this->security ) {
			$this->security = \WDD\Plugin::get_instance()->get_component( 'security' );
		}
		return $this->security;
	}

	/**
	 * Initialize AJAX hooks
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_wdd_get_rule', array( $this, 'get_rule' ) );
		add_action( 'wp_ajax_wdd_save_rule', array( $this, 'save_rule' ) );
		add_action( 'wp_ajax_wdd_delete_rule', array( $this, 'delete_rule' ) );
		add_action( 'wp_ajax_wdd_duplicate_rule', array( $this, 'duplicate_rule' ) );
		add_action( 'wp_ajax_wdd_search_products', array( $this, 'search_products' ) );
		add_action( 'wp_ajax_wdd_search_users', array( $this, 'search_users' ) );
	}

	/**
	 * Get rule by ID
	 */
	public function get_rule() {
		// Check nonce directly
		if ( ! isset( $_POST['wdd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wdd_nonce'] ) ), 'wdd_ajax_action' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
			return;
		}

		// Check capability
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
			return;
		}

		$rule_id = isset( $_POST['rule_id'] ) ? intval( $_POST['rule_id'] ) : 0;
		$rule_type = isset( $_POST['rule_type'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_type'] ) ) : '';

		if ( ! $rule_id || ! $rule_type ) {
			wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
			return;
		}

		global $wpdb;
		$table_name = $this->get_table_name( $rule_type );
		
		$rule = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $rule_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( ! $rule ) {
			wp_send_json_error( array( 'message' => 'Rule not found' ) );
			return;
		}

		// Unserialize array fields - convert empty strings to empty arrays
		$array_fields = array( 'product_ids', 'category_ids', 'user_roles', 'user_ids', 'days_of_week', 'tiers', 'conditions', 'trigger_products', 'trigger_categories', 'gift_products', 'purchase_history_conditions' );
		foreach ( $array_fields as $field ) {
			if ( isset( $rule[ $field ] ) ) {
				if ( empty( $rule[ $field ] ) || $rule[ $field ] === '' ) {
					// Empty string or null - convert to empty array
					$rule[ $field ] = array();
				} else {
					// Has data - unserialize it
					$rule[ $field ] = maybe_unserialize( $rule[ $field ] );
					// Ensure it's an array
					if ( ! is_array( $rule[ $field ] ) ) {
						$rule[ $field ] = array();
					}
				}
			} else {
				$rule[ $field ] = array();
			}
		}

		// Add product/category names for Select2 population
		if ( ! empty( $rule['product_ids'] ) && is_array( $rule['product_ids'] ) ) {
			$rule['product_options'] = array();
			foreach ( $rule['product_ids'] as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					$rule['product_options'][] = array(
						'id' => $product_id,
						'text' => $product->get_name()
					);
				}
			}
		}

		if ( ! empty( $rule['category_ids'] ) && is_array( $rule['category_ids'] ) ) {
			$rule['category_options'] = array();
			foreach ( $rule['category_ids'] as $cat_id ) {
				$term = get_term( $cat_id, 'product_cat' );
				if ( $term && ! is_wp_error( $term ) ) {
					$rule['category_options'][] = array(
						'id' => $cat_id,
						'text' => $term->name
					);
				}
			}
		}

		if ( ! empty( $rule['user_ids'] ) && is_array( $rule['user_ids'] ) ) {
			$rule['user_options'] = array();
			foreach ( $rule['user_ids'] as $user_id ) {
				$user = get_userdata( $user_id );
				if ( $user ) {
					$rule['user_options'][] = array(
						'id' => $user_id,
						'text' => $user->display_name . ' (' . $user->user_email . ')'
					);
				}
			}
		}

		// Same for trigger fields
		if ( ! empty( $rule['trigger_products'] ) && is_array( $rule['trigger_products'] ) ) {
			$rule['trigger_product_options'] = array();
			foreach ( $rule['trigger_products'] as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					$rule['trigger_product_options'][] = array(
						'id' => $product_id,
						'text' => $product->get_name()
					);
				}
			}
		}

		if ( ! empty( $rule['trigger_categories'] ) && is_array( $rule['trigger_categories'] ) ) {
			$rule['trigger_category_options'] = array();
			foreach ( $rule['trigger_categories'] as $cat_id ) {
				$term = get_term( $cat_id, 'product_cat' );
				if ( $term && ! is_wp_error( $term ) ) {
					$rule['trigger_category_options'][] = array(
						'id' => $cat_id,
						'text' => $term->name
					);
				}
			}
		}

		if ( ! empty( $rule['gift_products'] ) && is_array( $rule['gift_products'] ) ) {
			$rule['gift_product_options'] = array();
			foreach ( $rule['gift_products'] as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					$rule['gift_product_options'][] = array(
						'id' => $product_id,
						'text' => $product->get_name()
					);
				}
			}
		}

		// Parse and enrich additional triggers with product/category names
		if ( ! empty( $rule['additional_triggers'] ) ) {
			$additional_triggers = json_decode( $rule['additional_triggers'], true );
			if ( is_array( $additional_triggers ) ) {
				foreach ( $additional_triggers as &$trigger ) {
					// Add product names for product triggers
					if ( $trigger['type'] === 'product' && ! empty( $trigger['products'] ) ) {
						$trigger['product_options'] = array();
						foreach ( $trigger['products'] as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( $product ) {
								$trigger['product_options'][] = array(
									'id' => $product_id,
									'text' => $product->get_name()
								);
							}
						}
					}
					
					// Add category names for category triggers
					if ( $trigger['type'] === 'category' && ! empty( $trigger['categories'] ) ) {
						$trigger['category_options'] = array();
						foreach ( $trigger['categories'] as $cat_id ) {
							$term = get_term( $cat_id, 'product_cat' );
							if ( $term && ! is_wp_error( $term ) ) {
								$trigger['category_options'][] = array(
									'id' => $cat_id,
									'text' => $term->name
								);
							}
						}
					}
				}
				// Re-encode the enriched data
				$rule['additional_triggers'] = $additional_triggers;
			}
		}

		wp_send_json_success( $rule );
	}

	/**
	 * Save rule
	 */
	public function save_rule() {
		// Check nonce directly
		if ( ! isset( $_POST['wdd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wdd_nonce'] ) ), 'wdd_ajax_action' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
			return;
		}

		// Check capability
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
			return;
		}

		$rule_data = isset( $_POST['rule_data'] ) ? wp_unslash( $_POST['rule_data'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$rule_type = isset( $_POST['rule_type'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_type'] ) ) : '';

		if ( empty( $rule_data ) || ! $rule_type ) {
			wp_send_json_error( array( 'message' => 'Invalid data' ) );
			return;
		}

		// Sanitize rule data - use security component if available, otherwise basic sanitization
		$security = $this->get_security();
		if ( $security ) {
			$sanitized_data = $security->sanitize_rule_data( $rule_data );
		} else {
			// Basic sanitization if security component not available
			$sanitized_data = array();
			foreach ( $rule_data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_data[ $key ] = $value; // Will be serialized later
				} else {
					// Preserve all values including empty strings
					$sanitized_data[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		global $wpdb;
		$table_name = $this->get_table_name( $rule_type );

		// Check for rule_id BEFORE serialization (must extract it first!)
		$rule_id = 0;
		if ( isset( $sanitized_data['rule_id'] ) && $sanitized_data['rule_id'] !== '' && $sanitized_data['rule_id'] !== null ) {
			$rule_id = intval( $sanitized_data['rule_id'] );
		} elseif ( isset( $sanitized_data['id'] ) && $sanitized_data['id'] !== '' && $sanitized_data['id'] !== null ) {
			$rule_id = intval( $sanitized_data['id'] );
		}
		
		// Remove rule_id and id from data array (don't want them in the database columns)
		unset( $sanitized_data['id'] );
		unset( $sanitized_data['rule_id'] );
		unset( $sanitized_data['rule_type'] ); // Also remove rule_type as it's not a column

		// Serialize array fields.
		$array_fields = array( 'product_ids', 'category_ids', 'user_roles', 'user_ids', 'days_of_week', 'tiers', 'conditions', 'trigger_products', 'trigger_categories', 'gift_products', 'purchase_history_conditions' );
		foreach ( $array_fields as $field ) {
			if ( isset( $sanitized_data[ $field ] ) ) {
				$sanitized_data[ $field ] = maybe_serialize( $sanitized_data[ $field ] );
			}
		}
		
		// Note: additional_triggers is already a JSON string from JavaScript, don't serialize it again

		if ( $rule_id ) {
			// Update existing rule.
			$result = $wpdb->update( $table_name, $sanitized_data, array( 'id' => $rule_id ) );
			do_action( 'wdd_rule_updated', $rule_type );
		} else {
			// Insert new rule.
			$result = $wpdb->insert( $table_name, $sanitized_data );
			$rule_id = $wpdb->insert_id;
			do_action( 'wdd_rule_created', $rule_type );
		}

		if ( false === $result ) {
			error_log( "WDD: Failed to save {$rule_type} rule. Error: " . $wpdb->last_error );
			error_log( "WDD: Table: {$table_name}" );
			error_log( "WDD: Data: " . print_r( $sanitized_data, true ) );
			wp_send_json_error( array( 'message' => __( 'Failed to save rule', 'woo-dynamic-deals' ) . ' - ' . $wpdb->last_error ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Rule saved successfully', 'woo-dynamic-deals' ),
			'rule_id' => $rule_id,
		) );
	}

	/**
	 * Delete rule
	 */
	public function delete_rule() {
		if ( ! $this->get_security() || ! $this->get_security()->verify_ajax_request() ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', 'woo-dynamic-deals' ) ) );
		}

		$rule_id = isset( $_POST['rule_id'] ) ? intval( $_POST['rule_id'] ) : 0;
		$rule_type = isset( $_POST['rule_type'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_type'] ) ) : '';

		if ( ! $rule_id || ! $rule_type ) {
			wp_send_json_error( array( 'message' => __( 'Invalid parameters', 'woo-dynamic-deals' ) ) );
		}

		global $wpdb;
		$table_name = $this->get_table_name( $rule_type );
		
		$result = $wpdb->delete( $table_name, array( 'id' => $rule_id ) );

		if ( false === $result ) {
			wp_send_json_error( array( 'message' => __( 'Failed to delete rule', 'woo-dynamic-deals' ) ) );
		}

		do_action( 'wdd_rule_deleted', $rule_type );

		wp_send_json_success( array( 'message' => __( 'Rule deleted successfully', 'woo-dynamic-deals' ) ) );
	}

	/**
	 * Duplicate rule
	 */
	public function duplicate_rule() {
		if ( ! $this->get_security() || ! $this->get_security()->verify_ajax_request() ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', 'woo-dynamic-deals' ) ) );
		}

		$rule_id = isset( $_POST['rule_id'] ) ? intval( $_POST['rule_id'] ) : 0;
		$rule_type = isset( $_POST['rule_type'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_type'] ) ) : '';

		if ( ! $rule_id || ! $rule_type ) {
			wp_send_json_error( array( 'message' => __( 'Invalid parameters', 'woo-dynamic-deals' ) ) );
		}

		global $wpdb;
		$table_name = $this->get_table_name( $rule_type );
		
		$rule = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $rule_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		if ( ! $rule ) {
			wp_send_json_error( array( 'message' => __( 'Rule not found', 'woo-dynamic-deals' ) ) );
		}

		// Remove ID and update title.
		unset( $rule['id'] );
		$rule['title'] = $rule['title'] . ' (Copy)';
		$rule['status'] = 'inactive';

		$result = $wpdb->insert( $table_name, $rule );

		if ( false === $result ) {
			wp_send_json_error( array( 'message' => __( 'Failed to duplicate rule', 'woo-dynamic-deals' ) ) );
		}

		do_action( 'wdd_rule_created', $rule_type );

		wp_send_json_success( array(
			'message' => __( 'Rule duplicated successfully', 'woo-dynamic-deals' ),
			'rule_id' => $wpdb->insert_id,
		) );
	}

	/**
	 * Search products
	 */
	public function search_products() {
		// Check nonce directly
		$nonce_provided = isset( $_POST['wdd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wdd_nonce'] ) ) : '';
		$nonce_verified = wp_verify_nonce( $nonce_provided, 'wdd_ajax_action' );
		
		if ( ! $nonce_verified ) {
			wp_send_json_error( array( 
				'message' => 'Security check failed',
				'debug' => array(
					'nonce_received' => ! empty( $nonce_provided ),
					'nonce_valid' => false
				)
			) );
			return;
		}

		// Check capability
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
			return;
		}

		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		if ( strlen( $search ) < 2 ) {
			wp_send_json_success( array() );
			return;
		}

		// Simple product search
		$products = array();
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'orderby'        => 'title',
			'order'          => 'ASC',
			's'              => $search,
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$products[] = array(
					'id'   => get_the_ID(),
					'text' => get_the_title() . ' (#' . get_the_ID() . ')',
				);
			}
			wp_reset_postdata();
		}

		wp_send_json_success( $products );
	}

	/**
	 * Normalize Greek characters by removing tonos/accents
	 *
	 * @param string $text Text to normalize.
	 * @return string
	 */
	private function normalize_greek_chars( $text ) {
		$replacements = array(
			'ά' => 'α', 'Ά' => 'Α',
			'έ' => 'ε', 'Έ' => 'Ε',
			'ή' => 'η', 'Ή' => 'Η',
			'ί' => 'ι', 'Ί' => 'Ι', 'ϊ' => 'ι', 'ΐ' => 'ι',
			'ό' => 'ο', 'Ό' => 'Ο',
			'ύ' => 'υ', 'Ύ' => 'Υ', 'ϋ' => 'υ', 'ΰ' => 'υ',
			'ώ' => 'ω', 'Ώ' => 'Ω',
		);
		
		return strtr( $text, $replacements );
	}

	/**
	 * Search users
	 */
	public function search_users() {
		// Check nonce directly
		if ( ! isset( $_POST['wdd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wdd_nonce'] ) ), 'wdd_ajax_action' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed' ) );
			return;
		}

		// Check capability
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
			return;
		}

		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		if ( strlen( $search ) < 2 ) {
			wp_send_json_success( array() );
			return;
		}

		$args = array(
			'search'         => '*' . $search . '*',
			'search_columns' => array( 'user_login', 'user_email', 'display_name' ),
			'number'         => 20,
			'orderby'        => 'display_name',
			'order'          => 'ASC',
		);

		$user_query = new \WP_User_Query( $args );
		$users = array();

		if ( ! empty( $user_query->get_results() ) ) {
			foreach ( $user_query->get_results() as $user ) {
				$users[] = array(
					'id'    => $user->ID,
					'text'  => $user->display_name . ' (' . $user->user_email . ')',
				);
			}
		}

		wp_send_json_success( $users );
	}

	/**
	 * Get table name by rule type
	 *
	 * @param string $rule_type Rule type.
	 * @return string
	 */
	private function get_table_name( $rule_type ) {
		global $wpdb;

		$table_map = array(
			'price'   => $wpdb->prefix . 'wdd_pricing_rules',
			'tiered'  => $wpdb->prefix . 'wdd_tiered_pricing',
			'cart'    => $wpdb->prefix . 'wdd_cart_discount_rules',
			'gift'    => $wpdb->prefix . 'wdd_gift_rules',
		);

		return isset( $table_map[ $rule_type ] ) ? $table_map[ $rule_type ] : $table_map['price'];
	}
}
