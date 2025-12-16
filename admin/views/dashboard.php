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
	<h1><?php echo esc_html__( 'Dynamic Deals', 'wow-dynamic-deals-for-woo' ); ?></h1>

	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=home' ) ); ?>" class="nav-tab <?php echo 'home' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '🏠 Home', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<?php if ( ! empty( $settings['enable_price_rules'] ) ) : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=price-rules' ) ); ?>" class="nav-tab <?php echo 'price-rules' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '💰 Price Rules', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<?php endif; ?>
		<?php if ( ! empty( $settings['enable_tiered_pricing'] ) ) : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=tiered-pricing' ) ); ?>" class="nav-tab <?php echo 'tiered-pricing' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '📊 Tiered Pricing', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<?php endif; ?>
		<?php if ( ! empty( $settings['enable_cart_discounts'] ) ) : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=cart-discounts' ) ); ?>" class="nav-tab <?php echo 'cart-discounts' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '🛒 Cart Discounts', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<?php endif; ?>
		<?php if ( ! empty( $settings['enable_gift_rules'] ) ) : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=gifts' ) ); ?>" class="nav-tab <?php echo 'gifts' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '🎁 Free Gifts', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<?php endif; ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=settings' ) ); ?>" class="nav-tab <?php echo 'settings' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '⚙️ Settings', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=examples' ) ); ?>" class="nav-tab <?php echo 'examples' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '📚 Rules Examples', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=woo-dynamic-deals&tab=donate' ) ); ?>" class="nav-tab <?php echo 'donate' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( '❤️ Please Donate', 'wow-dynamic-deals-for-woo' ); ?>
		</a>
	</nav>	<div class="wdd-tab-content">
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
