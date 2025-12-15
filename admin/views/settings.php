<?php
/**
 * Settings View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = get_option( 'wdd_settings', array() );

if ( isset( $_POST['wdd_save_settings'] ) && check_admin_referer( 'wdd_save_settings', 'wdd_settings_nonce' ) ) {
	$new_settings = array(
			'enable_price_rules'      => isset( $_POST['enable_price_rules'] ) ? 1 : 0,
			'enable_tiered_pricing'   => isset( $_POST['enable_tiered_pricing'] ) ? 1 : 0,
			'enable_cart_discounts'   => isset( $_POST['enable_cart_discounts'] ) ? 1 : 0,
			'enable_gift_rules'       => isset( $_POST['enable_gift_rules'] ) ? 1 : 0,
			
			'show_sale_badge'         => isset( $_POST['show_sale_badge'] ) ? 1 : 0,
			'sale_badge_text'         => isset( $_POST['sale_badge_text'] ) ? sanitize_text_field( $_POST['sale_badge_text'] ) : 'SALE!',
			'pricing_display_format'  => isset( $_POST['pricing_display_format'] ) ? sanitize_text_field( $_POST['pricing_display_format'] ) : 'both',
			'show_cart_savings'       => isset( $_POST['show_cart_savings'] ) ? 1 : 0,
		
		'sale_price_color'        => isset( $_POST['sale_price_color'] ) ? sanitize_hex_color( $_POST['sale_price_color'] ) : '#d32f2f',
		'original_price_color'    => isset( $_POST['original_price_color'] ) ? sanitize_hex_color( $_POST['original_price_color'] ) : '#999999',
		'savings_text'            => isset( $_POST['savings_text'] ) ? sanitize_text_field( $_POST['savings_text'] ) : 'You save:',
		'savings_text_color'      => isset( $_POST['savings_text_color'] ) ? sanitize_hex_color( $_POST['savings_text_color'] ) : '#4caf50',
			
			'show_quantity_table'     => isset( $_POST['show_quantity_table'] ) ? 1 : 0,
			'cart_discount_label'     => isset( $_POST['cart_discount_label'] ) ? sanitize_text_field( $_POST['cart_discount_label'] ) : 'Discount',
		'cart_discount_label_color' => isset( $_POST['cart_discount_label_color'] ) ? sanitize_hex_color( $_POST['cart_discount_label_color'] ) : '#333333',
		'cart_discount_font_size' => isset( $_POST['cart_discount_font_size'] ) ? absint( $_POST['cart_discount_font_size'] ) : 14,
		'cart_discount_font_weight' => isset( $_POST['cart_discount_font_weight'] ) ? sanitize_text_field( $_POST['cart_discount_font_weight'] ) : 'normal',
		'cart_savings_text'       => isset( $_POST['cart_savings_text'] ) ? sanitize_text_field( $_POST['cart_savings_text'] ) : 'You save',
		'cart_savings_color'      => isset( $_POST['cart_savings_color'] ) ? sanitize_hex_color( $_POST['cart_savings_color'] ) : '#4caf50',
		'show_percentage_in_cart' => isset( $_POST['show_percentage_in_cart'] ) ? 1 : 0,
			'free_shipping_text'      => isset( $_POST['free_shipping_text'] ) ? sanitize_text_field( $_POST['free_shipping_text'] ) : 'Free Shipping',
			'free_shipping_color'     => isset( $_POST['free_shipping_color'] ) ? sanitize_hex_color( $_POST['free_shipping_color'] ) : '#4caf50',
			
	);
	
	update_option( 'wdd_settings', $new_settings );
	$settings = $new_settings;
	
	echo '<div class="wdd-info-box wdd-info-box-success wdd-fade-in"><strong>✓ Success!</strong> ' . esc_html__( 'Settings saved successfully!', 'woo-dynamic-deals' ) . '</div>';
}
?>

<div class="wdd-page-header">
	<h2>⚙️ <?php esc_html_e( 'Plugin Settings', 'woo-dynamic-deals' ); ?></h2>
	<p><?php esc_html_e( 'Configure plugin behavior and enable/disable modules', 'woo-dynamic-deals' ); ?></p>
</div>

<form method="post" action="" class="wdd-settings-form">
	<?php wp_nonce_field( 'wdd_save_settings', 'wdd_settings_nonce' ); ?>
	
	<!-- Module Controls -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🎯 <?php esc_html_e( 'Module Controls', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<p style="color: #6c757d; margin-bottom: 20px;">
				<?php esc_html_e( 'Enable or disable specific modules. Disabled modules will be hidden from navigation and will not process on the frontend, improving performance.', 'woo-dynamic-deals' ); ?>
			</p>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
				<div class="wdd-checkbox-wrapper" style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea;">
					<input type="checkbox" name="enable_price_rules" value="1" class="wdd-checkbox" id="enable_price_rules" <?php checked( ! empty( $settings['enable_price_rules'] ) ); ?>>
					<label for="enable_price_rules" style="font-weight: 600; cursor: pointer;">
						💰 <?php esc_html_e( 'Price Rules', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<div class="wdd-checkbox-wrapper" style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #10b981;">
					<input type="checkbox" name="enable_tiered_pricing" value="1" class="wdd-checkbox" id="enable_tiered_pricing" <?php checked( ! empty( $settings['enable_tiered_pricing'] ) ); ?>>
					<label for="enable_tiered_pricing" style="font-weight: 600; cursor: pointer;">
						📊 <?php esc_html_e( 'Tiered Pricing', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<div class="wdd-checkbox-wrapper" style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3b82f6;">
					<input type="checkbox" name="enable_cart_discounts" value="1" class="wdd-checkbox" id="enable_cart_discounts" <?php checked( ! empty( $settings['enable_cart_discounts'] ) ); ?>>
					<label for="enable_cart_discounts" style="font-weight: 600; cursor: pointer;">
						🛒 <?php esc_html_e( 'Cart Discounts', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<div class="wdd-checkbox-wrapper" style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #f59e0b;">
					<input type="checkbox" name="enable_gift_rules" value="1" class="wdd-checkbox" id="enable_gift_rules" <?php checked( ! empty( $settings['enable_gift_rules'] ) ); ?>>
					<label for="enable_gift_rules" style="font-weight: 600; cursor: pointer;">
						🎁 <?php esc_html_e( 'Free Gifts', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<!-- Display & Frontend Settings -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🎨 <?php esc_html_e( 'Display & Frontend Settings', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<p style="color: #6c757d; margin-bottom: 20px;">
				<?php esc_html_e( 'Customize how discounts and promotions are displayed to customers on the frontend.', 'woo-dynamic-deals' ); ?>
			</p>
			
			<!-- Show Sale Badge -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="show_sale_badge" value="1" class="wdd-checkbox" id="show_sale_badge" <?php checked( ! empty( $settings['show_sale_badge'] ) ); ?>>
					<label for="show_sale_badge" style="font-weight: 600; cursor: pointer;">
						🏷️ <?php esc_html_e( 'Show Sale Badge on Discounted Products', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Display a customizable badge on products with active discount rules.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Badge Text -->
			<div class="wdd-form-group" style="margin-left: 30px;">
				<label for="sale_badge_text" style="font-weight: 600; display: block; margin-bottom: 8px;">
					<?php esc_html_e( 'Badge Text', 'woo-dynamic-deals' ); ?>
				</label>
				<input type="text" name="sale_badge_text" id="sale_badge_text" value="<?php echo esc_attr( ! empty( $settings['sale_badge_text'] ) ? $settings['sale_badge_text'] : 'SALE!' ); ?>" class="regular-text" placeholder="SALE!">
				<p class="wdd-form-help">
					<?php esc_html_e( 'Customize the text displayed on the sale badge (e.g., "SALE!", "DEAL", "Limited Time", "🔥 Hot").', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Pricing Display Format -->
			<div class="wdd-form-group">
				<label for="pricing_display_format" style="font-weight: 600; display: block; margin-bottom: 8px;">
					💲 <?php esc_html_e( 'Pricing Display Format', 'woo-dynamic-deals' ); ?>
				</label>
				<select name="pricing_display_format" id="pricing_display_format" class="regular-text">
					<option value="both" <?php selected( ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : 'both', 'both' ); ?>>
						<?php esc_html_e( 'Show Both (Strikethrough + Sale Price)', 'woo-dynamic-deals' ); ?>
					</option>
					<option value="sale_only" <?php selected( ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : '', 'sale_only' ); ?>>
						<?php esc_html_e( 'Sale Price Only', 'woo-dynamic-deals' ); ?>
					</option>
					<option value="strikethrough" <?php selected( ! empty( $settings['pricing_display_format'] ) ? $settings['pricing_display_format'] : '', 'strikethrough' ); ?>>
						<?php esc_html_e( 'Strikethrough Original Price', 'woo-dynamic-deals' ); ?>
					</option>
				</select>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Choose how to display original vs discounted prices on product pages.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Show Cart Savings -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="show_cart_savings" value="1" class="wdd-checkbox" id="show_cart_savings" <?php checked( ! empty( $settings['show_cart_savings'] ) ); ?>>
					<label for="show_cart_savings" style="font-weight: 600; cursor: pointer;">
						💰 <?php esc_html_e( 'Show Total Savings in Cart', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Display "You saved $X!" message in cart and checkout to highlight customer savings.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Price Display Colors & Text -->
			<div class="wdd-form-group">
				<h4 style="margin-top: 20px; margin-bottom: 15px; color: #333;">
					🎨 <?php esc_html_e( 'Price Display Customization', 'woo-dynamic-deals' ); ?>
				</h4>
				
				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
					<!-- Sale Price Color -->
					<div>
						<label for="sale_price_color" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Sale Price Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="sale_price_color" id="sale_price_color" value="<?php echo esc_attr( ! empty( $settings['sale_price_color'] ) ? $settings['sale_price_color'] : '#d32f2f' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for discounted price', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Original Price Color -->
					<div>
						<label for="original_price_color" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Original Price Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="original_price_color" id="original_price_color" value="<?php echo esc_attr( ! empty( $settings['original_price_color'] ) ? $settings['original_price_color'] : '#999999' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for strikethrough price', 'woo-dynamic-deals' ); ?></small>
					</div>
				</div>
				
				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
					<!-- Savings Text -->
					<div>
						<label for="savings_text" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Savings Text', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="text" name="savings_text" id="savings_text" value="<?php echo esc_attr( ! empty( $settings['savings_text'] ) ? $settings['savings_text'] : 'You save:' ); ?>" class="regular-text">
						<small style="color: #666;"><?php esc_html_e( 'Text before savings amount (e.g., "You save:", "Save", "Discount")', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Savings Text Color -->
					<div>
						<label for="savings_text_color" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Savings Text Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="savings_text_color" id="savings_text_color" value="<?php echo esc_attr( ! empty( $settings['savings_text_color'] ) ? $settings['savings_text_color'] : '#4caf50' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for savings message', 'woo-dynamic-deals' ); ?></small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Cart Discount Display Customization -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🛒 <?php esc_html_e( 'Cart Discount Display Customization', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<p style="color: #6c757d; margin-bottom: 20px;">
				<?php esc_html_e( 'Customize how cart discount fees and free shipping appear in the cart totals section.', 'woo-dynamic-deals' ); ?>
			</p>
			
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
				<div style="display: grid; gap: 20px;">
					<!-- Cart Discount Color -->
					<div>
						<label for="cart_discount_color" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Cart Discount Fee Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="cart_discount_color" id="cart_discount_color" value="<?php echo esc_attr( ! empty( $settings['cart_discount_color'] ) ? $settings['cart_discount_color'] : '#10b981' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for cart discount fee labels in cart totals', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Free Shipping Text -->
					<div>
						<label for="free_shipping_text" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Free Shipping Label', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="text" name="free_shipping_text" id="free_shipping_text" value="<?php echo esc_attr( ! empty( $settings['free_shipping_text'] ) ? $settings['free_shipping_text'] : 'Free Shipping' ); ?>" class="regular-text">
						<small style="color: #666;"><?php esc_html_e( 'Text for free shipping discount in cart totals', 'woo-dynamic-deals' ); ?></small>
					</div>
				</div>
				
				<div style="display: grid; gap: 20px;">
					<!-- Free Shipping Color -->
					<div>
						<label for="free_shipping_color" style="font-weight: 600; display: block; margin-bottom: 5px;">
							<?php esc_html_e( 'Free Shipping Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="free_shipping_color" id="free_shipping_color" value="<?php echo esc_attr( ! empty( $settings['free_shipping_color'] ) ? $settings['free_shipping_color'] : '#4caf50' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for free shipping fee label', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Preview -->
					<div style="background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
						<strong style="display: block; margin-bottom: 10px;"><?php esc_html_e( 'Preview:', 'woo-dynamic-deals' ); ?></strong>
						<div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
							<span id="preview_cart_discount" style="color: <?php echo esc_attr( ! empty( $settings['cart_discount_color'] ) ? $settings['cart_discount_color'] : '#10b981' ); ?>;">30% Cart Discount</span>
							<span style="color: <?php echo esc_attr( ! empty( $settings['cart_discount_color'] ) ? $settings['cart_discount_color'] : '#10b981' ); ?>;">-20€</span>
						</div>
						<div style="display: flex; justify-content: space-between;">
							<span id="preview_free_shipping" style="color: <?php echo esc_attr( ! empty( $settings['free_shipping_color'] ) ? $settings['free_shipping_color'] : '#4caf50' ); ?>;"><?php echo esc_html( ! empty( $settings['free_shipping_text'] ) ? $settings['free_shipping_text'] : 'Free Shipping' ); ?></span>
							<span style="color: <?php echo esc_attr( ! empty( $settings['free_shipping_color'] ) ? $settings['free_shipping_color'] : '#4caf50' ); ?>;">-5€</span>
						</div>
					</div>
				</div>
			</div>
			
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				const cartDiscountColor = document.getElementById('cart_discount_color');
				const freeShippingText = document.getElementById('free_shipping_text');
				const freeShippingColor = document.getElementById('free_shipping_color');
				
				if (cartDiscountColor) {
					cartDiscountColor.addEventListener('input', function() {
						const preview = document.getElementById('preview_cart_discount');
						const previewValue = preview.nextElementSibling;
						if (preview) preview.style.color = this.value;
						if (previewValue) previewValue.style.color = this.value;
					});
				}
				
				if (freeShippingText) {
					freeShippingText.addEventListener('input', function() {
						const preview = document.getElementById('preview_free_shipping');
						if (preview) preview.textContent = this.value;
					});
				}
				
				if (freeShippingColor) {
					freeShippingColor.addEventListener('input', function() {
						const preview = document.getElementById('preview_free_shipping');
						const previewValue = preview.nextElementSibling;
						if (preview) preview.style.color = this.value;
						if (previewValue) previewValue.style.color = this.value;
					});
				}
			});
			</script>
		</div>
	</div>

	<!-- Discount Stacking & Priority -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🔄 <?php esc_html_e( 'Discount Stacking & Priority', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<p style="color: #6c757d; margin-bottom: 20px;">
				<?php esc_html_e( 'Control how multiple discounts interact and set safety limits.', 'woo-dynamic-deals' ); ?>
			</p>
			
			<!-- Allow Stacking -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="allow_discount_stacking" value="1" class="wdd-checkbox" id="allow_discount_stacking" <?php checked( ! empty( $settings['allow_discount_stacking'] ) ); ?>>
					<label for="allow_discount_stacking" style="font-weight: 600; cursor: pointer;">
						<?php esc_html_e( 'Allow Multiple Discounts to Stack', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'When enabled, multiple discount rules can apply to the same product/cart. When disabled, only the best discount applies.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Max Discount Limit -->
			<div class="wdd-form-group">
				<label for="max_discount_percent" style="font-weight: 600; display: block; margin-bottom: 8px;">
					🛡️ <?php esc_html_e( 'Maximum Total Discount (%)', 'woo-dynamic-deals' ); ?>
				</label>
				<input type="number" name="max_discount_percent" id="max_discount_percent" value="<?php echo esc_attr( ! empty( $settings['max_discount_percent'] ) ? $settings['max_discount_percent'] : 0 ); ?>" class="small-text" min="0" max="100" step="1">
				<span style="margin-left: 5px;">%</span>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Set a safety cap on the maximum discount percentage. Enter 0 for no limit. Example: 50 = no product can be discounted more than 50%.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Stack with Coupons -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="stack_with_coupons" value="1" class="wdd-checkbox" id="stack_with_coupons" <?php checked( ! empty( $settings['stack_with_coupons'] ) ); ?>>
					<label for="stack_with_coupons" style="font-weight: 600; cursor: pointer;">
						🎟️ <?php esc_html_e( 'Allow Stacking with WooCommerce Coupons', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'When enabled, customers can use coupon codes in addition to automatic dynamic discounts.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
		</div>
	</div>

	<!-- Cart & Checkout Display -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🛒 <?php esc_html_e( 'Cart & Checkout Display', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<p style="color: #6c757d; margin-bottom: 20px;">
				<?php esc_html_e( 'Customize how discounts appear in the shopping cart and checkout.', 'woo-dynamic-deals' ); ?>
			</p>
			
			<!-- Show Quantity Table -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="show_quantity_table" value="1" class="wdd-checkbox" id="show_quantity_table" <?php checked( ! empty( $settings['show_quantity_table'] ) ); ?>>
					<label for="show_quantity_table" style="font-weight: 600; cursor: pointer;">
						📊 <?php esc_html_e( 'Show Quantity Pricing Table on Products', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Display a table showing tiered pricing breaks on product pages (e.g., "Buy 5-9: $10 each, Buy 10+: $8 each").', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<!-- Show Percentage in Cart -->
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="show_percentage_in_cart" value="1" class="wdd-checkbox" id="show_percentage_in_cart" <?php checked( ! empty( $settings['show_percentage_in_cart'] ) ); ?>>
					<label for="show_percentage_in_cart" style="font-weight: 600; cursor: pointer;">
						📈 <?php esc_html_e( 'Show Discount Percentage in Cart/Checkout', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Display the discount percentage alongside savings (e.g., "You save: 20€ (30%)").', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
			
			<h3 style="margin-bottom: 20px; font-size: 16px; color: #1f2937;">🎨 <?php esc_html_e( 'Text & Font Customization', 'woo-dynamic-deals' ); ?></h3>
			
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
				<!-- Left Column -->
				<div style="display: grid; gap: 20px;">
					<!-- Cart Discount Label -->
					<div>
						<label for="cart_discount_label" style="font-weight: 600; display: block; margin-bottom: 8px;">
							🏷️ <?php esc_html_e( 'Cart Discount Label', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="text" name="cart_discount_label" id="cart_discount_label" value="<?php echo esc_attr( ! empty( $settings['cart_discount_label'] ) ? $settings['cart_discount_label'] : 'Discount' ); ?>" class="regular-text" placeholder="Discount">
						<small style="color: #666;"><?php esc_html_e( 'Label for discounts in cart totals', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Cart Discount Label Color -->
					<div>
						<label for="cart_discount_label_color" style="font-weight: 600; display: block; margin-bottom: 8px;">
							<?php esc_html_e( 'Discount Label Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="cart_discount_label_color" id="cart_discount_label_color" value="<?php echo esc_attr( ! empty( $settings['cart_discount_label_color'] ) ? $settings['cart_discount_label_color'] : '#333333' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Text color for discount labels', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Font Size -->
					<div>
						<label for="cart_discount_font_size" style="font-weight: 600; display: block; margin-bottom: 8px;">
							📏 <?php esc_html_e( 'Font Size (px)', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="number" name="cart_discount_font_size" id="cart_discount_font_size" value="<?php echo esc_attr( ! empty( $settings['cart_discount_font_size'] ) ? $settings['cart_discount_font_size'] : 14 ); ?>" min="10" max="24" step="1" class="small-text">
						<span style="margin-left: 5px;">px</span>
						<small style="color: #666; display: block; margin-top: 5px;"><?php esc_html_e( 'Font size for discount text (10-24px)', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Font Weight -->
					<div>
						<label for="cart_discount_font_weight" style="font-weight: 600; display: block; margin-bottom: 8px;">
							💪 <?php esc_html_e( 'Font Weight', 'woo-dynamic-deals' ); ?>
						</label>
						<select name="cart_discount_font_weight" id="cart_discount_font_weight" class="regular-text">
							<option value="normal" <?php selected( ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal', 'normal' ); ?>><?php esc_html_e( 'Normal', 'woo-dynamic-deals' ); ?></option>
							<option value="500" <?php selected( ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal', '500' ); ?>><?php esc_html_e( 'Medium', 'woo-dynamic-deals' ); ?></option>
							<option value="600" <?php selected( ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal', '600' ); ?>><?php esc_html_e( 'Semi-Bold', 'woo-dynamic-deals' ); ?></option>
							<option value="bold" <?php selected( ! empty( $settings['cart_discount_font_weight'] ) ? $settings['cart_discount_font_weight'] : 'normal', 'bold' ); ?>><?php esc_html_e( 'Bold', 'woo-dynamic-deals' ); ?></option>
						</select>
						<small style="color: #666;"><?php esc_html_e( 'Font weight for discount text', 'woo-dynamic-deals' ); ?></small>
					</div>
				</div>
				
				<!-- Right Column -->
				<div style="display: grid; gap: 20px;">
					<!-- Savings Text -->
					<div>
						<label for="cart_savings_text" style="font-weight: 600; display: block; margin-bottom: 8px;">
							💰 <?php esc_html_e( 'Savings Text', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="text" name="cart_savings_text" id="cart_savings_text" value="<?php echo esc_attr( ! empty( $settings['cart_savings_text'] ) ? $settings['cart_savings_text'] : 'You save' ); ?>" class="regular-text" placeholder="You save">
						<small style="color: #666;"><?php esc_html_e( 'Text before savings amount in cart/checkout', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Savings Color -->
					<div>
						<label for="cart_savings_color" style="font-weight: 600; display: block; margin-bottom: 8px;">
							<?php esc_html_e( 'Savings Text Color', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="color" name="cart_savings_color" id="cart_savings_color" value="<?php echo esc_attr( ! empty( $settings['cart_savings_color'] ) ? $settings['cart_savings_color'] : '#4caf50' ); ?>" style="width: 100%; height: 40px; cursor: pointer;">
						<small style="color: #666;"><?php esc_html_e( 'Color for savings message', 'woo-dynamic-deals' ); ?></small>
					</div>
					
					<!-- Preview -->
					<div style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 20px;">
						<strong style="display: block; margin-bottom: 15px; color: #1f2937;"><?php esc_html_e( 'Preview:', 'woo-dynamic-deals' ); ?></strong>
						
						<!-- Original Price -->
						<div style="margin-bottom: 10px;">
							<span style="text-decoration: line-through; color: #999;">129€</span>
							<span style="color: #d32f2f; font-weight: 600; margin-left: 8px;">64.5€</span>
						</div>
						
						<!-- Discount Label -->
						<div style="margin-bottom: 8px;">
							<span id="preview_cart_discount_label" style="font-size: 14px; font-weight: normal; color: #333;">Discount:</span>
							<span style="margin-left: 5px;">-30€</span>
						</div>
						
						<!-- Savings -->
						<div>
							<span id="preview_cart_savings" style="color: #4caf50; font-size: 14px;">You save: 64.5€</span>
						</div>
					</div>
				</div>
			</div>
			
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				const labelInput = document.getElementById('cart_discount_label');
				const labelColorInput = document.getElementById('cart_discount_label_color');
				const fontSizeInput = document.getElementById('cart_discount_font_size');
				const fontWeightInput = document.getElementById('cart_discount_font_weight');
				const savingsTextInput = document.getElementById('cart_savings_text');
				const savingsColorInput = document.getElementById('cart_savings_color');
				
				const previewLabel = document.getElementById('preview_cart_discount_label');
				const previewSavings = document.getElementById('preview_cart_savings');
				
				if (labelInput && previewLabel) {
					labelInput.addEventListener('input', function() {
						previewLabel.textContent = this.value + ':';
					});
				}
				
				if (labelColorInput && previewLabel) {
					labelColorInput.addEventListener('input', function() {
						previewLabel.style.color = this.value;
					});
				}
				
				if (fontSizeInput && previewLabel && previewSavings) {
					fontSizeInput.addEventListener('input', function() {
						const size = this.value + 'px';
						previewLabel.style.fontSize = size;
						previewSavings.style.fontSize = size;
					});
				}
				
				if (fontWeightInput && previewLabel) {
					fontWeightInput.addEventListener('change', function() {
						previewLabel.style.fontWeight = this.value;
					});
				}
				
				if (savingsTextInput && previewSavings) {
					savingsTextInput.addEventListener('input', function() {
						const currentText = previewSavings.textContent;
						const amountMatch = currentText.match(/[0-9.,]+€/);
						if (amountMatch) {
							previewSavings.textContent = this.value + ': ' + amountMatch[0];
						}
					});
				}
				
				if (savingsColorInput && previewSavings) {
					savingsColorInput.addEventListener('input', function() {
						previewSavings.style.color = this.value;
					});
				}
			});
			</script>
		</div>
	</div>

	<!-- Debug & Developer Options -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🐛 <?php esc_html_e( 'Debug & Developer Options', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="debug_mode" value="1" class="wdd-checkbox" id="debug_mode" <?php checked( ! empty( $settings['debug_mode'] ) ); ?>>
					<label for="debug_mode" style="font-weight: 600; cursor: pointer;">
						<?php esc_html_e( 'Enable Debug Mode', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'Logs detailed rule evaluation information to wp-content/debug.log (requires WP_DEBUG_LOG to be enabled).', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
		</div>
	</div>

	<!-- Data Management -->
	<div class="wdd-card wdd-fade-in">
		<div class="wdd-card-header">
			🗄️ <?php esc_html_e( 'Data Management', 'woo-dynamic-deals' ); ?>
		</div>
		<div class="wdd-card-body">
			<div class="wdd-form-group">
				<div class="wdd-checkbox-wrapper">
					<input type="checkbox" name="keep_data_on_uninstall" value="1" class="wdd-checkbox" id="keep_data_on_uninstall" <?php checked( ! empty( $settings['keep_data_on_uninstall'] ) ); ?>>
					<label for="keep_data_on_uninstall" style="font-weight: 600; cursor: pointer;">
						<?php esc_html_e( 'Keep Data on Uninstall', 'woo-dynamic-deals' ); ?>
					</label>
				</div>
				<p class="wdd-form-help">
					<?php esc_html_e( 'If unchecked, all rules, settings, and database tables will be permanently deleted when you uninstall the plugin.', 'woo-dynamic-deals' ); ?>
				</p>
			</div>
			
			<div class="wdd-info-box wdd-info-box-warning">
				<strong>⚠️ <?php esc_html_e( 'Important:', 'woo-dynamic-deals' ); ?></strong>
				<?php esc_html_e( 'Uninstalling with data deletion is permanent and cannot be undone. Make sure to backup your rules before uninstalling if you plan to reinstall later.', 'woo-dynamic-deals' ); ?>
			</div>
		</div>
	</div>

	<div style="text-align: right; margin-top: 30px;">
		<button type="submit" name="wdd_save_settings" class="wdd-btn wdd-btn-primary wdd-btn-lg">
			💾 <?php esc_html_e( 'Save Settings', 'woo-dynamic-deals' ); ?>
		</button>
	</div>
</form>

