<?php
/**
 * Admin Dashboard View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'home';
$settings   = get_option( 'wdd_settings', array(
	'enable_price_rules'     => 1,
	'enable_tiered_pricing'  => 1,
	'enable_cart_discounts'  => 1,
	'enable_gift_rules'      => 1,
) );
?>

<div class="wrap wdd-admin-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Dynamic Deals for WooCommerce', 'wow-dynamic-deals-for-woo' ); ?></h1>
	<hr class="wp-header-end">
	
	<div class="wdd-tab-content">
		<?php
		switch ( $active_tab ) {
			case 'home':
				include __DIR__ . '/home.php';
				break;
			case 'price-rules':
				include __DIR__ . '/price-rules.php';
				break;
			case 'tiered-pricing':
				include __DIR__ . '/tiered-pricing.php';
				break;
			case 'cart-discounts':
				include __DIR__ . '/cart-discounts.php';
				break;
			case 'gifts':
				include __DIR__ . '/gifts.php';
				break;
		case 'settings':
			include __DIR__ . '/settings.php';
			break;
		case 'examples':
			include __DIR__ . '/examples.php';
			break;
		case 'donate':
			include __DIR__ . '/donate.php';
			break;
		default:
			include __DIR__ . '/home.php';
		}
		?>
	</div>
</div>
