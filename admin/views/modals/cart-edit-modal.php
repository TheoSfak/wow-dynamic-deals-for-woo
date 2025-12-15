<?php
/**
 * Cart Discount Edit Modal
 *
 * @package WDD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wdd-cart-modal" class="wdd-modal" style="display:none;">
	<div class="wdd-modal-backdrop"></div>
	<div class="wdd-modal-content">
		<div class="wdd-modal-header">
			<h2 id="wdd-cart-modal-title"><?php esc_html_e( 'Add Cart Discount', 'woo-dynamic-deals' ); ?></h2>
			<button type="button" class="wdd-modal-close">&times;</button>
		</div>
		
		<form id="wdd-cart-form">
			<input type="hidden" id="wdd-cart-id" name="rule_id" value="">
			<input type="hidden" name="rule_type" value="cart">
			
			<div class="wdd-modal-body">
				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-8">
						<label for="wdd-cart-title">
							<?php esc_html_e( 'Rule Title', 'woo-dynamic-deals' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" id="wdd-cart-title" name="title" class="wdd-input-text" required>
					</div>
					
					<div class="wdd-form-group wdd-col-4">
						<label for="wdd-cart-priority">
							<?php esc_html_e( 'Priority', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="number" id="wdd-cart-priority" name="priority" class="wdd-input-text" value="10" min="0" max="9999">
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Discount Settings', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-discount-type">
								<?php esc_html_e( 'Discount Type', 'woo-dynamic-deals' ); ?>
								<span class="required">*</span>
							</label>
							<select id="wdd-cart-discount-type" name="discount_type" class="wdd-select" required>
								<option value=""><?php esc_html_e( 'Select type...', 'woo-dynamic-deals' ); ?></option>
								<option value="percentage"><?php esc_html_e( 'Percentage', 'woo-dynamic-deals' ); ?></option>
								<option value="fixed"><?php esc_html_e( 'Fixed Amount', 'woo-dynamic-deals' ); ?></option>
							</select>
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-discount-value">
								<?php esc_html_e( 'Discount Value', 'woo-dynamic-deals' ); ?>
								<span class="required">*</span>
							</label>
							<input type="number" id="wdd-cart-discount-value" name="discount_value" class="wdd-input-text" step="0.01" min="0" required>
						</div>
					</div>
					
					<div class="wdd-form-group">
						<label>
							<input type="checkbox" name="apply_free_shipping" value="1">
							<?php esc_html_e( 'Apply free shipping when this rule is active', 'woo-dynamic-deals' ); ?>
						</label>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Cart Conditions', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<label>
							<input type="checkbox" name="first_order_only" value="1">
							<?php esc_html_e( 'Apply only to first order (new customers)', 'woo-dynamic-deals' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Discount will only apply if customer has never placed an order before. Works for both logged-in users and guest email addresses.', 'woo-dynamic-deals' ); ?></p>
					</div>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-min-total"><?php esc_html_e( 'Min Cart Total', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-cart-min-total" name="min_cart_total" class="wdd-input-text" step="0.01" min="0" placeholder="0.00">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-max-total"><?php esc_html_e( 'Max Cart Total', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-cart-max-total" name="max_cart_total" class="wdd-input-text" step="0.01" min="0" placeholder="<?php esc_attr_e( 'Unlimited', 'woo-dynamic-deals' ); ?>">
						</div>
					</div>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-min-quantity"><?php esc_html_e( 'Min Cart Quantity', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-cart-min-quantity" name="min_cart_quantity" class="wdd-input-text" min="0" placeholder="0">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-max-quantity"><?php esc_html_e( 'Max Cart Quantity', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-cart-max-quantity" name="max_cart_quantity" class="wdd-input-text" min="0" placeholder="<?php esc_attr_e( 'Unlimited', 'woo-dynamic-deals' ); ?>">
						</div>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Schedule', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-date-from"><?php esc_html_e( 'Start Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-cart-date-from" name="date_from" class="wdd-input-text">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-cart-date-to"><?php esc_html_e( 'End Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-cart-date-to" name="date_to" class="wdd-input-text">
						</div>
					</div>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'Days of Week', 'woo-dynamic-deals' ); ?></label>
						<div class="wdd-checkbox-group">
							<?php
							$days = array(
								'monday'    => __( 'Monday', 'woo-dynamic-deals' ),
								'tuesday'   => __( 'Tuesday', 'woo-dynamic-deals' ),
								'wednesday' => __( 'Wednesday', 'woo-dynamic-deals' ),
								'thursday'  => __( 'Thursday', 'woo-dynamic-deals' ),
								'friday'    => __( 'Friday', 'woo-dynamic-deals' ),
								'saturday'  => __( 'Saturday', 'woo-dynamic-deals' ),
								'sunday'    => __( 'Sunday', 'woo-dynamic-deals' ),
							);
							foreach ( $days as $value => $label ) {
								echo '<label><input type="checkbox" name="days_of_week[]" value="' . esc_attr( $value ) . '"> ' . esc_html( $label ) . '</label>';
							}
							?>
						</div>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'User Restrictions', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'User Roles', 'woo-dynamic-deals' ); ?></label>
						<div class="wdd-checkbox-group">
							<?php
							$roles = wp_roles()->get_names();
							foreach ( $roles as $role_value => $role_name ) {
								echo '<label><input type="checkbox" name="user_roles[]" value="' . esc_attr( $role_value ) . '"> ' . esc_html( $role_name ) . '</label>';
							}
							?>
						</div>
					</div>
					
					<div class="wdd-form-group">
						<label for="wdd-cart-user-ids"><?php esc_html_e( 'Specific Users', 'woo-dynamic-deals' ); ?></label>
						<select id="wdd-cart-user-ids" name="user_ids[]" class="wdd-user-search" multiple="multiple" style="width:100%;"></select>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Additional Options', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<label>
							<input type="checkbox" name="stop_further_rules" value="1">
							<?php esc_html_e( 'Stop processing further rules after this one', 'woo-dynamic-deals' ); ?>
						</label>
					</div>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'Status', 'woo-dynamic-deals' ); ?></label>
						<div class="wdd-radio-group">
							<label>
								<input type="radio" name="status" value="active" checked>
								<?php esc_html_e( 'Active', 'woo-dynamic-deals' ); ?>
							</label>
							<label>
								<input type="radio" name="status" value="inactive">
								<?php esc_html_e( 'Inactive', 'woo-dynamic-deals' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>
			
			<div class="wdd-modal-footer">
				<button type="button" class="button wdd-modal-close"><?php esc_html_e( 'Cancel', 'woo-dynamic-deals' ); ?></button>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Cart Discount', 'woo-dynamic-deals' ); ?></button>
			</div>
		</form>
	</div>
</div>
