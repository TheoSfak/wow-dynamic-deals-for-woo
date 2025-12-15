<?php
/**
 * Tiered Pricing Edit Modal
 *
 * @package WDD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wdd-tiered-modal" class="wdd-modal" style="display:none;">
	<div class="wdd-modal-backdrop"></div>
	<div class="wdd-modal-content">
		<div class="wdd-modal-header">
			<h2 id="wdd-tiered-modal-title"><?php esc_html_e( 'Add Tiered Pricing', 'woo-dynamic-deals' ); ?></h2>
			<button type="button" class="wdd-modal-close">&times;</button>
		</div>
		
		<form id="wdd-tiered-form">
			<input type="hidden" id="wdd-tiered-id" name="rule_id" value="">
			<input type="hidden" name="rule_type" value="tiered">
			
			<div class="wdd-modal-body">
				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-8">
						<label for="wdd-tiered-title">
							<?php esc_html_e( 'Rule Title', 'woo-dynamic-deals' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" id="wdd-tiered-title" name="title" class="wdd-input-text" required>
					</div>
					
					<div class="wdd-form-group wdd-col-4">
						<label for="wdd-tiered-priority">
							<?php esc_html_e( 'Priority', 'woo-dynamic-deals' ); ?>
						</label>
						<input type="number" id="wdd-tiered-priority" name="priority" class="wdd-input-text" value="10" min="0" max="9999">
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Product Selection', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<label for="wdd-tiered-product-ids"><?php esc_html_e( 'Specific Products', 'woo-dynamic-deals' ); ?></label>
						<select id="wdd-tiered-product-ids" name="product_ids[]" class="wdd-product-search" multiple="multiple" style="width:100%;"></select>
						<p class="description"><?php esc_html_e( 'Leave empty to apply to all products', 'woo-dynamic-deals' ); ?></p>
					</div>
					
					<div class="wdd-form-group">
						<label for="wdd-tiered-category-ids"><?php esc_html_e( 'Product Categories', 'woo-dynamic-deals' ); ?></label>
						<select id="wdd-tiered-category-ids" name="category_ids[]" class="wdd-category-select" multiple="multiple" style="width:100%;">
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
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Calculation Mode', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-group">
						<div class="wdd-radio-group">
							<label>
								<input type="radio" name="calculation_mode" value="per_line" checked>
								<?php esc_html_e( 'Per Line Item', 'woo-dynamic-deals' ); ?>
								<span class="description"><?php esc_html_e( 'Calculate tiers for each cart line separately', 'woo-dynamic-deals' ); ?></span>
							</label>
							<label>
								<input type="radio" name="calculation_mode" value="combined">
								<?php esc_html_e( 'Combined Quantity', 'woo-dynamic-deals' ); ?>
								<span class="description"><?php esc_html_e( 'Sum quantities of all matching products', 'woo-dynamic-deals' ); ?></span>
							</label>
						</div>
					</div>
				</div>

				<div class="wdd-form-section">
					<div class="wdd-section-header">
						<h3><?php esc_html_e( 'Pricing Tiers', 'woo-dynamic-deals' ); ?></h3>
						<button type="button" class="button button-secondary wdd-add-tier">
							<span class="dashicons dashicons-plus-alt"></span>
							<?php esc_html_e( 'Add Tier', 'woo-dynamic-deals' ); ?>
						</button>
					</div>
					
					<div id="wdd-tiers-container">
						<!-- Tiers will be added here dynamically -->
					</div>
					
					<template id="wdd-tier-template">
						<div class="wdd-tier-row">
							<div class="wdd-tier-handle">
								<span class="dashicons dashicons-menu"></span>
							</div>
							<div class="wdd-tier-fields">
								<div class="wdd-form-row">
									<div class="wdd-form-group wdd-col-3">
										<label><?php esc_html_e( 'Min Qty', 'woo-dynamic-deals' ); ?></label>
										<input type="number" name="tiers[][min_quantity]" class="wdd-input-text" min="1" required>
									</div>
									<div class="wdd-form-group wdd-col-3">
										<label><?php esc_html_e( 'Max Qty', 'woo-dynamic-deals' ); ?></label>
										<input type="number" name="tiers[][max_quantity]" class="wdd-input-text" min="1" placeholder="<?php esc_attr_e( 'Unlimited', 'woo-dynamic-deals' ); ?>">
									</div>
									<div class="wdd-form-group wdd-col-3">
										<label><?php esc_html_e( 'Discount Type', 'woo-dynamic-deals' ); ?></label>
										<select name="tiers[][discount_type]" class="wdd-select" required>
											<option value="percentage"><?php esc_html_e( 'Percentage', 'woo-dynamic-deals' ); ?></option>
											<option value="fixed"><?php esc_html_e( 'Fixed Amount', 'woo-dynamic-deals' ); ?></option>
											<option value="fixed_price"><?php esc_html_e( 'Fixed Price', 'woo-dynamic-deals' ); ?></option>
										</select>
									</div>
									<div class="wdd-form-group wdd-col-3">
										<label><?php esc_html_e( 'Value', 'woo-dynamic-deals' ); ?></label>
										<input type="number" name="tiers[][discount_value]" class="wdd-input-text" step="0.01" min="0" required>
									</div>
								</div>
							</div>
							<button type="button" class="button button-link-delete wdd-remove-tier">
								<span class="dashicons dashicons-trash"></span>
							</button>
						</div>
					</template>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Schedule', 'woo-dynamic-deals' ); ?></h3>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-tiered-date-from"><?php esc_html_e( 'Start Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-tiered-date-from" name="date_from" class="wdd-input-text">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-tiered-date-to"><?php esc_html_e( 'End Date', 'woo-dynamic-deals' ); ?></label>
							<input type="date" id="wdd-tiered-date-to" name="date_to" class="wdd-input-text">
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
						<label for="wdd-tiered-user-ids"><?php esc_html_e( 'Specific Users', 'woo-dynamic-deals' ); ?></label>
						<select id="wdd-tiered-user-ids" name="user_ids[]" class="wdd-user-search" multiple="multiple" style="width:100%;"></select>
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
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Tiered Pricing', 'woo-dynamic-deals' ); ?></button>
			</div>
		</form>
	</div>
</div>
