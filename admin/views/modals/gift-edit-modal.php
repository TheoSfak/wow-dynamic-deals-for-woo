<?php
/**
 * Gift Rule Edit Modal
 *
 * @package WDD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wdd-gift-modal" class="wdd-modal" style="display:none;">
	<div class="wdd-modal-backdrop"></div>
	<div class="wdd-modal-content">
		<div class="wdd-modal-header">
			<h2 id="wdd-gift-modal-title"><?php esc_html_e( 'Add Gift Rule', 'woo-dynamic-deals' ); ?></h2>
			<button type="button" class="wdd-modal-close">&times;</button>
		</div>
		
		<form id="wdd-gift-form" novalidate>
			<input type="hidden" id="wdd-gift-id" name="rule_id" value="">
			<input type="hidden" name="rule_type" value="gift">
			
			<div class="wdd-modal-body">
				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-8">
						<label for="wdd-gift-title">
							<?php esc_html_e( 'Rule Title', 'woo-dynamic-deals' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" id="wdd-gift-title" name="title" class="wdd-input-text" required>
					</div>
					
					<div class="wdd-form-group wdd-col-4">
						<label for="wdd-gift-priority">
							<?php esc_html_e( 'Priority', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="number" id="wdd-gift-priority" name="priority" class="wdd-input-text" value="10" min="0" max="9999">
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Trigger Conditions', 'woo-dynamic-deals' ); ?></h3>
					
					<!-- Main Trigger Section -->
					<div id="wdd-main-trigger-section" style="padding: 15px; background: #f0f8ff; border: 2px solid #4a90e2; border-radius: 8px; margin-bottom: 20px;">
						<h4 style="margin: 0 0 15px 0; color: #4a90e2; font-size: 14px;">
							🎯 <?php esc_html_e( 'Main Trigger Condition', 'woo-dynamic-deals' ); ?>
						</h4>
						
						<div class="wdd-form-group" style="margin-bottom: 15px;">
							<label for="wdd-gift-trigger-type">
								<?php esc_html_e( 'Trigger Type', 'woo-dynamic-deals' ); ?>
								<span class="required">*</span>
							</label>
							<select id="wdd-gift-trigger-type" name="trigger_type" class="wdd-select" required>
								<option value=""><?php esc_html_e( 'Select trigger...', 'woo-dynamic-deals' ); ?></option>
								<option value="product"><?php esc_html_e( 'Specific Products in Cart', 'woo-dynamic-deals' ); ?></option>
								<option value="category"><?php esc_html_e( 'Products from Categories in Cart', 'woo-dynamic-deals' ); ?></option>
								<option value="cart_total"><?php esc_html_e( 'Minimum Cart Total', 'woo-dynamic-deals' ); ?></option>
								<option value="cart_quantity"><?php esc_html_e( 'Minimum Cart Quantity', 'woo-dynamic-deals' ); ?></option>
							</select>
						</div>
						
						<!-- Main Trigger: Product -->
						<div class="wdd-form-group wdd-trigger-field" data-trigger="product" style="display:none;">
							<label for="wdd-gift-trigger-products"><?php esc_html_e( 'Trigger Products', 'woo-dynamic-deals' ); ?></label>
							<select id="wdd-gift-trigger-products" name="trigger_products[]" class="wdd-product-search" multiple="multiple" style="width:100%;"></select>
							<p class="description"><?php esc_html_e( 'Gift will be added when any of these products are in cart', 'woo-dynamic-deals' ); ?></p>
						</div>
						
						<!-- Main Trigger: Category -->
						<div class="wdd-form-group wdd-trigger-field" data-trigger="category" style="display:none;">
							<label for="wdd-gift-trigger-categories"><?php esc_html_e( 'Trigger Categories', 'woo-dynamic-deals' ); ?></label>
							<select id="wdd-gift-trigger-categories" name="trigger_categories[]" class="wdd-category-select" multiple="multiple" style="width:100%;">
								<?php
								$categories = get_terms( array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => false,
								) );
								foreach ( $categories as $category ) {
									echo '<option value="' . esc_attr( $category->term_id ) . '">' . esc_html( $category->name ) . '</option>';
								}
								?>
							</select>
							<p class="description"><?php esc_html_e( 'Gift will be added when products from these categories are in cart', 'woo-dynamic-deals' ); ?></p>
						</div>
						
						<!-- Main Trigger: Cart Total -->
						<div class="wdd-form-group wdd-trigger-field" data-trigger="cart_total" style="display:none;">
							<label for="wdd-gift-trigger-cart-total"><?php esc_html_e( 'Minimum Cart Total', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-gift-trigger-cart-total" name="trigger_amount" class="wdd-input-text" step="0.01" min="0" placeholder="100.00">
							<p class="description"><?php esc_html_e( 'Gift will be added when cart total reaches this amount', 'woo-dynamic-deals' ); ?></p>
						</div>
						
						<!-- Main Trigger: Cart Quantity -->
						<div class="wdd-form-group wdd-trigger-field" data-trigger="cart_quantity" style="display:none;">
							<label for="wdd-gift-trigger-cart-quantity"><?php esc_html_e( 'Minimum Cart Quantity', 'woo-dynamic-deals' ); ?></label>
							<input type="number" id="wdd-gift-trigger-cart-quantity" name="trigger_quantity" class="wdd-input-text" min="1" placeholder="5">
							<p class="description"><?php esc_html_e( 'Gift will be added when cart has this many items', 'woo-dynamic-deals' ); ?></p>
						</div>
					</div>
					
					<!-- Add More Triggers Button -->
					<div class="wdd-form-group" style="margin-bottom: 20px;">
						<button type="button" class="wdd-btn wdd-btn-secondary" id="wdd-add-trigger" style="width: 100%;">
							<span style="font-size: 18px;">+</span> <?php esc_html_e( 'Add Additional Trigger Condition', 'woo-dynamic-deals' ); ?>
						</button>
						<p class="description" style="margin-top: 8px; text-align: center;">
							<em><?php esc_html_e( 'Add multiple conditions and choose AND (all must match) or OR (any can match)', 'woo-dynamic-deals' ); ?></em>
						</p>
					</div>
					
					<!-- Trigger Logic (AND/OR) -->
					<div id="wdd-trigger-logic-group" style="display: none; margin-bottom: 20px; padding: 10px; background: #fff9e6; border: 2px solid #f39c12; border-radius: 8px;">
						<label style="font-weight: bold; color: #f39c12;"><?php esc_html_e( '⚡ Trigger Logic', 'woo-dynamic-deals' ); ?></label>
						<div style="display: flex; gap: 15px; margin-top: 10px;">
							<label style="display: flex; align-items: center; cursor: pointer; padding: 8px 12px; background: white; border: 2px solid #ddd; border-radius: 4px;">
								<input type="radio" name="trigger_logic" value="and" checked style="margin-right: 8px;">
								<strong>AND</strong> <span style="margin-left: 8px; color: #666; font-size: 12px;">(All conditions must match)</span>
							</label>
							<label style="display: flex; align-items: center; cursor: pointer; padding: 8px 12px; background: white; border: 2px solid #ddd; border-radius: 4px;">
								<input type="radio" name="trigger_logic" value="or" style="margin-right: 8px;">
								<strong>OR</strong> <span style="margin-left: 8px; color: #666; font-size: 12px;">(Any condition can match)</span>
							</label>
						</div>
					</div>
					
					<!-- Additional Triggers Container -->
					<div id="wdd-triggers-container">
						<!-- Additional trigger conditions will be added here dynamically by JavaScript -->
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Gift Products', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<label for="wdd-gift-products">
							<?php esc_html_e( 'Products to Give as Gifts', 'woo-dynamic-deals' ); ?>
							<span class="required">*</span>
						</label>
						<select id="wdd-gift-products" name="gift_products[]" class="wdd-product-search" multiple="multiple" style="width:100%;" required></select>
						<p class="description"><?php esc_html_e( 'These products will be added to cart automatically at $0 price', 'woo-dynamic-deals' ); ?></p>
					</div>
					
					<div class="wdd-form-group">
						<label for="wdd-gift-max-gifts"><?php esc_html_e( 'Maximum Gifts per Order', 'woo-dynamic-deals' ); ?></label>
					<input type="number" id="wdd-gift-max-gifts" name="max_gifts_per_order" class="wdd-input-text" min="1" value="1" placeholder="1">
					<p class="description">
						<?php esc_html_e( 'Limit the total number of gift items per order. For example: Set to 1 to give only one free gift even if customer qualifies multiple times. Set to 2 to allow maximum 2 free gifts per order. Leave at 1 for standard promotions.', 'woo-dynamic-deals' ); ?>
					</p>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Schedule', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-gift-date-from"><?php esc_html_e( 'Start Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-gift-date-from" name="date_from" class="wdd-input-text">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-gift-date-to"><?php esc_html_e( 'End Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-gift-date-to" name="date_to" class="wdd-input-text">
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
						<label for="wdd-gift-user-ids"><?php esc_html_e( 'Specific Users', 'woo-dynamic-deals' ); ?></label>
						<select id="wdd-gift-user-ids" name="user_ids[]" class="wdd-user-search" multiple="multiple" style="width:100%;"></select>
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
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Gift Rule', 'woo-dynamic-deals' ); ?></button>
			</div>
		</form>
	</div>
</div>
