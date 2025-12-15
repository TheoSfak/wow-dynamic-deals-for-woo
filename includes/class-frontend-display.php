<?php
/**
 * Frontend Display
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Display class
 */
class FrontendDisplay {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Display tiered pricing table for product
	 *
	 * @param int $product_id Product ID.
	 */
	public function display_tiered_pricing( $product_id ) {
		$settings = get_option( 'wdd_settings', array() );
		
		if ( empty( $settings['show_quantity_table'] ) ) {
			return;
		}
		
		
		$tiered_engine = Plugin::get_instance()->get_component( 'tiered_pricing' );
		if ( ! $tiered_engine ) {
			return;
		}

		if ( ! $tiered_engine->has_tiered_pricing( $product_id ) ) {
			return;
		}

		$tiers = $tiered_engine->get_product_tiers( $product_id );
		if ( empty( $tiers ) ) {
			return;
		}


		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return;
		}

		$original_price = floatval( $product->get_regular_price() );
		if ( empty( $original_price ) ) {
			$original_price = floatval( $product->get_price() );
		}

		echo '<div class="wdd-tiered-pricing-table">';
		echo '<h4>' . esc_html__( 'Quantity Discounts', 'wow-dynamic-deals-for-woo' ) . '</h4>';
		echo '<table>';
		echo '<thead>';
		echo '<tr>';
		echo '<th>' . esc_html__( 'Quantity', 'wow-dynamic-deals-for-woo' ) . '</th>';
		echo '<th>' . esc_html__( 'Price Per Unit', 'wow-dynamic-deals-for-woo' ) . '</th>';
		echo '<th>' . esc_html__( 'You Save', 'wow-dynamic-deals-for-woo' ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		foreach ( $tiers as $tier ) {
			$min_qty = intval( $tier['min_quantity'] ?? 0 );
			$max_qty = isset( $tier['max_quantity'] ) && $tier['max_quantity'] > 0 ? intval( $tier['max_quantity'] ) : null;
			
			$discount_type = $tier['discount_type'] ?? 'percentage';
			$discount_value = floatval( $tier['discount_value'] ?? 0 );

			$tier_price = $original_price;
			switch ( $discount_type ) {
				case 'fixed_price':
					$tier_price = max( 0, $discount_value );
					break;
				case 'percentage':
					$tier_price = $original_price - ( $original_price * $discount_value / 100 );
					break;
				case 'fixed':
					$tier_price = $original_price - $discount_value;
					break;
			}
			$tier_price = max( 0, $tier_price );

			$savings = $original_price - $tier_price;
			$savings_percent = $original_price > 0 ? ( $savings / $original_price ) * 100 : 0;

			$quantity_text = $max_qty ? sprintf( '%d - %d', $min_qty, $max_qty ) : sprintf( '%d+', $min_qty );

			echo '<tr>';
			echo '<td>' . esc_html( $quantity_text ) . '</td>';
			echo '<td>' . wc_price( $tier_price ) . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			/* translators: 1: Price amount, 2: Percentage */
			echo '<td>' . sprintf( esc_html__( '%1$s (%2$s%%)', 'wow-dynamic-deals-for-woo' ), wc_price( $savings ), number_format( $savings_percent, 1 ) ) . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '</div>';
	}

	/**
	 * Display discount badge for product
	 *
	 * @param int $product_id Product ID.
	 */
	public function display_discount_badge( $product_id ) {
		$settings = get_option( 'wdd_settings', array() );
		
		if ( empty( $settings['show_sale_badge'] ) ) {
			return;
		}
		
		$price_engine = Plugin::get_instance()->get_component( 'price_engine' );
		if ( ! $price_engine ) {
			return;
		}

		if ( ! $price_engine->has_active_rule( $product_id ) ) {
			return;
		}

		$rule = $price_engine->get_active_rule_for_product( $product_id );
		if ( empty( $rule ) ) {
			return;
		}

		$badge_text = ! empty( $settings['sale_badge_text'] ) ? $settings['sale_badge_text'] : __( 'SALE!', 'wow-dynamic-deals-for-woo' );

		echo '<div class="wdd-discount-badge">';
		echo esc_html( $badge_text );
		echo '</div>';
	}

	/**
	 * Display cart savings summary
	 */
	public function display_savings_summary() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}

		$settings = get_option( 'wdd_settings', array() );
		$total_savings = 0;
		$tiered_breakdown = array();
		$cart_discount_total = 0;

		$cart_discount_engine = Plugin::get_instance()->get_component( 'cart_discount' );
		if ( $cart_discount_engine ) {
			$active_discounts = $cart_discount_engine->get_active_discounts();
			foreach ( $active_discounts as $discount ) {
				$discount_amount = floatval( $discount['discount_amount'] ?? 0 );
				$total_savings += $discount_amount;
				$cart_discount_total += $discount_amount;
			}
		}

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'];
			
			
			if ( isset( $cart_item['wdd_original_price'] ) ) {
			}
			
			if ( isset( $cart_item['wdd_original_price'] ) ) {
				$original_price = floatval( $cart_item['wdd_original_price'] );
				$sale_price = floatval( $product->get_price() );
				$quantity = intval( $cart_item['quantity'] );
				
				if ( $original_price > $sale_price ) {
					$item_savings = ( $original_price - $sale_price ) * $quantity;
					$savings_percent = $original_price > 0 ? ( ( $original_price - $sale_price ) / $original_price ) * 100 : 0;
					
					$total_savings += $item_savings;
					
					
					$tiered_breakdown[] = array(
						'product_name' => $product->get_name(),
						'quantity' => $quantity,
						'original_price' => $original_price,
						'discounted_price' => $sale_price,
						'savings' => $item_savings,
						'percent' => $savings_percent,
					);
				}
			}
		}
		

		if ( ! empty( $tiered_breakdown ) ) {
			echo '<tr class="wdd-tiered-breakdown-header">';
			echo '<th colspan="2" style="background: #f8f9fa; padding: 12px 10px; font-size: 14px; color: #667eea; border-top: 2px solid #667eea;">';
			echo '<strong>📊 ' . esc_html__( 'Quantity Discount Breakdown', 'wow-dynamic-deals-for-woo' ) . '</strong>';
			echo '</th>';
			echo '</tr>';

			foreach ( $tiered_breakdown as $item ) {
				echo '<tr class="wdd-tiered-item" style="background: #f8fafb;">';
				echo '<td style="padding: 10px; font-size: 13px; color: #495057;">';
				echo '<div style="font-weight: 600; margin-bottom: 3px;">' . esc_html( $item['product_name'] ) . '</div>';
				echo '<div style="font-size: 12px; color: #6c757d;">';
				echo sprintf( 
					/* translators: 1: Quantity, 2: Original price, 3: Discounted price */
					esc_html__( 'Qty: %1$d × %2$s → %3$s', 'wow-dynamic-deals-for-woo' ),
					absint( $item['quantity'] ),
					wc_price( $item['original_price'] ),
					wc_price( $item['discounted_price'] )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				echo '</td>';
				echo '<td style="padding: 10px; text-align: right;">';
				echo '<div style="color: #10b981; font-weight: 600; font-size: 14px;">-' . wc_price( $item['savings'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<div style="font-size: 11px; color: #6c757d;">(' . number_format( $item['percent'], 1 ) . '% ' . esc_html__( 'off', 'wow-dynamic-deals-for-woo' ) . ')</div>';
				echo '</td>';
				echo '</tr>';
			}
		}

		if ( $cart_discount_total > 0 ) {
			echo '<tr class="wdd-cart-discount-row" style="background: #fff3cd;">';
			echo '<th style="padding: 10px; font-size: 13px; color: #856404;">';
			echo '🛒 ' . esc_html__( 'Cart Discount', 'wow-dynamic-deals-for-woo' );
			echo '</th>';
			echo '<td style="padding: 10px; text-align: right; color: #856404; font-weight: 600;">-' . wc_price( $cart_discount_total ) . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</tr>';
		}

		if ( ! empty( $settings['show_cart_savings'] ) && $total_savings > 0 ) {
			$label = ! empty( $settings['cart_discount_label'] ) ? $settings['cart_discount_label'] : __( 'Discount', 'wow-dynamic-deals-for-woo' );

			echo '<tr class="wdd-savings-summary" style="color: #10b981; font-weight: 600; background: #d1fae5; border-top: 2px solid #10b981;">';
			/* translators: %s: Discount label */
			echo '<th style="padding: 12px 10px; font-size: 15px;">' . sprintf( esc_html__( '💰 Total Saved (%s)!', 'wow-dynamic-deals-for-woo' ), esc_html( $label ) ) . '</th>';
			echo '<td style="padding: 12px 10px; text-align: right;"><strong style="font-size: 16px;">-' . wc_price( $total_savings ) . '</strong></td>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</tr>';
		}
	}

	/**
	 * Display gift messages
	 */
	public function display_gift_messages() {
		$gift_engine = Plugin::get_instance()->get_component( 'gift_engine' );
		if ( ! $gift_engine ) {
			return;
		}

		$active_gifts = $gift_engine->get_active_gifts();
		if ( empty( $active_gifts ) ) {
			return;
		}

		echo '<div class="wdd-gift-message">';
		echo '<strong class="wdd-gift-header"><span class="wdd-gift-icon">🎁</span> <span class="wdd-glow-text">Free Gifts</span> in Your Cart!</strong>';
		echo '<ul>';

		foreach ( $active_gifts as $gift_item ) {
			$product = $gift_item['data'];
			$rule_title = $gift_item['wdd_gift_rule_title'] ?? '';
			$rule_reason = $gift_item['wdd_gift_rule_reason'] ?? '';
			
			echo '<li>';
			echo '<span class="wdd-gift-product-name">' . esc_html( $product->get_name() ) . '</span>';
			if ( $rule_reason ) {
				echo '<br><small class="wdd-gift-reason">' . esc_html( $rule_reason ) . '</small>';
			}
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Filter product price HTML to apply custom formatting
	 *
	 * @param string     $price Product price HTML.
	 * @param \WC_Product $product Product object.
	 * @return string Modified price HTML.
	 */
	public function filter_price_html( $price, $product ) {
		$settings = get_option( 'wdd_settings', array() );
		$format = ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : 'strikethrough';


		$price_engine = Plugin::get_instance()->get_component( 'price_engine' );
		if ( ! $price_engine ) {
			return $price;
		}

		$original_price = floatval( get_post_meta( $product->get_id(), '_regular_price', true ) );
		$current_price = floatval( $product->get_price() );
		
		
		if ( $original_price > $current_price && $current_price > 0 ) {
			$savings = $original_price - $current_price;
			$savings_percent = round( ( $savings / $original_price ) * 100 );
			
			
			$sale_color = ! empty( $settings['sale_price_color'] ) ? $settings['sale_price_color'] : '#d32f2f';
			$original_color = ! empty( $settings['original_price_color'] ) ? $settings['original_price_color'] : '#999999';
			$savings_text = ! empty( $settings['savings_text'] ) ? $settings['savings_text'] : 'You save:';
			$savings_color = ! empty( $settings['savings_text_color'] ) ? $settings['savings_text_color'] : '#4caf50';
			
			switch ( $format ) {
				case 'sale_only':
					$price = '<span style="color: ' . esc_attr( $sale_color ) . '; font-weight: bold;">' . wc_price( $current_price ) . '</span>';
					break;

				case 'strikethrough':
				case 'both':
					$price = '<del aria-label="Original price" style="color: ' . esc_attr( $original_color ) . ';">' . wc_price( $original_price ) . '</del> ';
					$price .= '<ins aria-label="Discounted price" style="color: ' . esc_attr( $sale_color ) . '; font-weight: bold; text-decoration: none;">' . wc_price( $current_price ) . '</ins>';
					
					$price .= ' <span class="wdd-you-save" style="color: ' . esc_attr( $savings_color ) . '; font-weight: bold;">' . esc_html( $savings_text ) . ' ' . wc_price( $savings ) . ' (' . $savings_percent . '%)</span>';
					break;
			}
		}

		return $price;
	}

	/**
	 * Filter cart item price display
	 *
	 * @param string     $price Price HTML.
	 * @param \WC_Product $product Product object.
	 * @return string Modified price HTML.
	 */
	public function filter_cart_item_price( $price, $product ) {
		$settings = get_option( 'wdd_settings', array() );
		$format = ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : 'strikethrough';

		$original_price = floatval( get_post_meta( $product->get_id(), '_regular_price', true ) );
		$current_price = floatval( $product->get_price() );
		
		if ( $original_price > $current_price && $current_price > 0 ) {
			$savings = $original_price - $current_price;
			$savings_percent = round( ( $savings / $original_price ) * 100 );
			
			$sale_color = ! empty( $settings['sale_price_color'] ) ? $settings['sale_price_color'] : '#d32f2f';
			$original_color = ! empty( $settings['original_price_color'] ) ? $settings['original_price_color'] : '#999999';
			$cart_label_color = ! empty( $settings['cart_discount_label_color'] ) ? $settings['cart_discount_label_color'] : '#333333';
			$cart_savings_color = ! empty( $settings['cart_savings_color'] ) ? $settings['cart_savings_color'] : '#4caf50';
			$cart_font_size = ! empty( $settings['cart_discount_font_size'] ) ? $settings['cart_discount_font_size'] : 14;
			$cart_font_weight = ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal';
			$show_percentage = ! empty( $settings['show_percentage_in_cart'] );
			
			switch ( $format ) {
				case 'sale_only':
					$price = '<span style="color: ' . esc_attr( $sale_color ) . '; font-weight: bold; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $current_price ) . '</span>';
					break;

				case 'strikethrough':
				case 'both':
					$price = '<del class="wdd-cart-original" style="color: ' . esc_attr( $original_color ) . '; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $original_price ) . '</del><br>';
					$price .= '<ins class="wdd-cart-discounted" style="color: ' . esc_attr( $sale_color ) . '; font-weight: ' . esc_attr( $cart_font_weight ) . '; text-decoration: none; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $current_price ) . '</ins><br>';
					$price .= '<small class="wdd-cart-save" style="color: ' . esc_attr( $cart_savings_color ) . '; font-size: ' . esc_attr( $cart_font_size - 2 ) . 'px;">-' . $savings_percent . '%</small>';
					break;
			}
		}

		return $price;
	}

	/**
	 * Filter cart item subtotal display
	 *
	 * @param string     $subtotal Subtotal HTML.
	 * @param \WC_Product $product Product object.
	 * @param int        $quantity Item quantity.
	 * @return string Modified subtotal HTML.
	 */
	public function filter_cart_item_subtotal( $subtotal, $product, $quantity ) {
		$settings = get_option( 'wdd_settings', array() );
		$format = ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : 'strikethrough';

		$original_price = floatval( get_post_meta( $product->get_id(), '_regular_price', true ) );
		$current_price = floatval( $product->get_price() );
		
		if ( $original_price > $current_price && $current_price > 0 ) {
			$original_subtotal = $original_price * $quantity;
			$current_subtotal = $current_price * $quantity;
			$savings = $original_subtotal - $current_subtotal;
			$savings_percent = round( ( $savings / $original_subtotal ) * 100 );
			
			$sale_color = ! empty( $settings['sale_price_color'] ) ? $settings['sale_price_color'] : '#d32f2f';
			$original_color = ! empty( $settings['original_price_color'] ) ? $settings['original_price_color'] : '#999999';
			$cart_savings_text = ! empty( $settings['cart_savings_text'] ) ? $settings['cart_savings_text'] : 'You save';
			$cart_savings_color = ! empty( $settings['cart_savings_color'] ) ? $settings['cart_savings_color'] : '#4caf50';
			$cart_font_size = ! empty( $settings['cart_discount_font_size'] ) ? $settings['cart_discount_font_size'] : 14;
			$cart_font_weight = ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal';
			$show_percentage = ! empty( $settings['show_percentage_in_cart'] );
			
			switch ( $format ) {
				case 'sale_only':
					$subtotal = '<span style="color: ' . esc_attr( $sale_color ) . '; font-weight: ' . esc_attr( $cart_font_weight ) . '; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $current_subtotal ) . '</span>';
					break;

				case 'strikethrough':
				case 'both':
					$subtotal = '<del class="wdd-cart-original" style="color: ' . esc_attr( $original_color ) . '; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $original_subtotal ) . '</del><br>';
					$subtotal .= '<ins class="wdd-cart-discounted" style="color: ' . esc_attr( $sale_color ) . '; font-weight: ' . esc_attr( $cart_font_weight ) . '; text-decoration: none; font-size: ' . esc_attr( $cart_font_size ) . 'px;">' . wc_price( $current_subtotal ) . '</ins><br>';
					
					$savings_display = '<small class="wdd-cart-save" style="color: ' . esc_attr( $cart_savings_color ) . '; font-size: ' . esc_attr( $cart_font_size - 2 ) . 'px;">';
					$savings_display .= esc_html( $cart_savings_text ) . ': ' . wc_price( $savings );
					if ( $show_percentage ) {
						$savings_display .= ' (' . $savings_percent . '%)';
					}
					$savings_display .= '</small>';
					
					$subtotal .= $savings_display;
					break;
			}
		}

		return $subtotal;
	}
}
