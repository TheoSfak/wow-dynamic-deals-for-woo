<?php
/**
 * Price Rule Edit Modal
 *
 * @package WDD
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wdd-rule-modal" class="wdd-modal" style="display:none;">
	<div class="wdd-modal-backdrop"></div>
	<div class="wdd-modal-content">
		<div class="wdd-modal-header">
			<h2 id="wdd-modal-title"><?php esc_html_e( 'Add Price Rule', 'wow-dynamic-deals-for-woo' ); ?></h2>
			<button type="button" class="wdd-modal-close">&times;</button>
		</div>
		
		<form id="wdd-rule-form">
			<input type="hidden" id="wdd-rule-id" name="rule_id" value="">
			<input type="hidden" id="wdd-rule-type" name="rule_type" value="price">
			
			<div class="wdd-modal-body">
				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-8">
						<label for="wdd-rule-title">
							<?php esc_html_e( 'Rule Title', 'wow-dynamic-deals-for-woo' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" id="wdd-rule-title" name="title" class="wdd-input-text" required>
					</div>
					
					<div class="wdd-form-group wdd-col-4">
						<label for="wdd-rule-priority">
							<?php esc_html_e( 'Priority', 'wow-dynamic-deals-for-woo' ); ?>
							<span class="wdd-tooltip" title="<?php esc_attr_e( 'Higher numbers have higher priority', 'wow-dynamic-deals-for-woo' ); ?>">?</span>
						</label>
						<input type="number" id="wdd-rule-priority" name="priority" class="wdd-input-text" value="10" min="0" max="9999">
					</div>
				</div>

				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-6">
						<label for="wdd-adjustment-type">
							<?php esc_html_e( 'Adjustment Type', 'wow-dynamic-deals-for-woo' ); ?>
							<span class="required">*</span>
						</label>
						<select id="wdd-adjustment-type" name="adjustment_type" class="wdd-select" required>
							<option value=""><?php esc_html_e( 'Select type...', 'wow-dynamic-deals-for-woo' ); ?></option>
							<option value="fixed_price"><?php esc_html_e( 'Fixed Price', 'wow-dynamic-deals-for-woo' ); ?></option>
							<option value="percentage_discount"><?php esc_html_e( 'Percentage Discount', 'wow-dynamic-deals-for-woo' ); ?></option>
							<option value="fixed_discount"><?php esc_html_e( 'Fixed Discount', 'wow-dynamic-deals-for-woo' ); ?></option>
							<option value="percentage_increase"><?php esc_html_e( 'Percentage Increase', 'wow-dynamic-deals-for-woo' ); ?></option>
							<option value="fixed_increase"><?php esc_html_e( 'Fixed Increase', 'wow-dynamic-deals-for-woo' ); ?></option>
						</select>
					</div>
					
					<div class="wdd-form-group wdd-col-6">
						<label for="wdd-adjustment-value">
							<?php esc_html_e( 'Value', 'wow-dynamic-deals-for-woo' ); ?>
							<span class="required">*</span>
						</label>
						<input type="number" id="wdd-adjustment-value" name="adjustment_value" class="wdd-input-text" step="0.01" min="0" required>
					</div>
				</div>

				<div class="wdd-form-row">
					<div class="wdd-form-group wdd-col-12">
						<label><?php esc_html_e( 'Apply To', 'wow-dynamic-deals-for-woo' ); ?></label>
						<div class="wdd-radio-group">
							<label>
								<input type="radio" name="apply_to" value="regular_price" checked>
								<?php esc_html_e( 'Regular Price', 'wow-dynamic-deals-for-woo' ); ?>
							</label>
							<label>
								<input type="radio" name="apply_to" value="sale_price">
								<?php esc_html_e( 'Sale Price', 'wow-dynamic-deals-for-woo' ); ?>
							</label>
							<label>
								<input type="radio" name="apply_to" value="both">
								<?php esc_html_e( 'Both', 'wow-dynamic-deals-for-woo' ); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Product Selection', 'wow-dynamic-deals-for-woo' ); ?></h3>
					
					<div class="wdd-form-group">
						<label for="wdd-product-ids"><?php esc_html_e( 'Specific Products', 'wow-dynamic-deals-for-woo' ); ?></label>
						<select id="wdd-product-ids" name="product_ids[]" class="wdd-product-search" multiple="multiple" style="width:100%;"></select>
						<p class="description"><?php esc_html_e( 'Leave empty to apply to all products', 'wow-dynamic-deals-for-woo' ); ?></p>
					</div>
					
					<div class="wdd-form-group">
						<label for="wdd-category-ids"><?php esc_html_e( 'Product Categories', 'wow-dynamic-deals-for-woo' ); ?></label>
						<select id="wdd-category-ids" name="category_ids[]" class="wdd-category-select" multiple="multiple" style="width:100%;">
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
					<h3><?php esc_html_e( 'Schedule', 'wow-dynamic-deals-for-woo' ); ?></h3>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-date-from"><?php esc_html_e( 'Start Date', 'wow-dynamic-deals-for-woo' ); ?></label>
							<input type="date" id="wdd-date-from" name="date_from" class="wdd-input-text">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-date-to"><?php esc_html_e( 'End Date', 'wow-dynamic-deals-for-woo' ); ?></label>
							<input type="date" id="wdd-date-to" name="date_to" class="wdd-input-text">
						</div>
					</div>
					
					<div class="wdd-form-row">
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-time-from"><?php esc_html_e( 'Start Time', 'wow-dynamic-deals-for-woo' ); ?></label>
							<input type="time" id="wdd-time-from" name="time_from" class="wdd-input-text">
						</div>
						
						<div class="wdd-form-group wdd-col-6">
							<label for="wdd-time-to"><?php esc_html_e( 'End Time', 'wow-dynamic-deals-for-woo' ); ?></label>
							<input type="time" id="wdd-time-to" name="time_to" class="wdd-input-text">
						</div>
					</div>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'Days of Week', 'wow-dynamic-deals-for-woo' ); ?></label>
						<div class="wdd-checkbox-group">
							<?php
							$days = array(
								'monday'    => __( 'Monday', 'wow-dynamic-deals-for-woo' ),
								'tuesday'   => __( 'Tuesday', 'wow-dynamic-deals-for-woo' ),
								'wednesday' => __( 'Wednesday', 'wow-dynamic-deals-for-woo' ),
								'thursday'  => __( 'Thursday', 'wow-dynamic-deals-for-woo' ),
								'friday'    => __( 'Friday', 'wow-dynamic-deals-for-woo' ),
								'saturday'  => __( 'Saturday', 'wow-dynamic-deals-for-woo' ),
								'sunday'    => __( 'Sunday', 'wow-dynamic-deals-for-woo' ),
							);
							foreach ( $days as $value => $label ) {
								echo '<label><input type="checkbox" name="days_of_week[]" value="' . esc_attr( $value ) . '"> ' . esc_html( $label ) . '</label>';
							}
							?>
						</div>
						<p class="description"><?php esc_html_e( 'Leave unchecked for all days', 'wow-dynamic-deals-for-woo' ); ?></p>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'User Restrictions', 'wow-dynamic-deals-for-woo' ); ?></h3>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'User Roles', 'wow-dynamic-deals-for-woo' ); ?></label>
						<div class="wdd-checkbox-group">
							<?php
							$roles = wp_roles()->get_names();
							foreach ( $roles as $role_value => $role_name ) {
								echo '<label><input type="checkbox" name="user_roles[]" value="' . esc_attr( $role_value ) . '"> ' . esc_html( $role_name ) . '</label>';
							}
							?>
						</div>
						<p class="description"><?php esc_html_e( 'Leave unchecked for all users', 'wow-dynamic-deals-for-woo' ); ?></p>
					</div>
					
					<div class="wdd-form-group">
						<label for="wdd-user-ids"><?php esc_html_e( 'Specific Users', 'wow-dynamic-deals-for-woo' ); ?></label>
						<select id="wdd-user-ids" name="user_ids[]" class="wdd-user-search" multiple="multiple" style="width:100%;"></select>
					</div>
				</div>

				<div class="wdd-form-section">
					<h3><?php esc_html_e( 'Additional Options', 'wow-dynamic-deals-for-woo' ); ?></h3>
					
					<div class="wdd-form-group">
						<label>
							<input type="checkbox" name="stop_further_rules" value="1">
							<?php esc_html_e( 'Stop processing further rules after this one', 'wow-dynamic-deals-for-woo' ); ?>
						</label>
					</div>
					
					<div class="wdd-form-group">
						<label><?php esc_html_e( 'Status', 'wow-dynamic-deals-for-woo' ); ?></label>
						<div class="wdd-radio-group">
							<label>
								<input type="radio" name="status" value="active" checked>
								<?php esc_html_e( 'Active', 'wow-dynamic-deals-for-woo' ); ?>
							</label>
							<label>
								<input type="radio" name="status" value="inactive">
								<?php esc_html_e( 'Inactive', 'wow-dynamic-deals-for-woo' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>
			
			<div class="wdd-modal-footer">
				<button type="button" class="button wdd-modal-close"><?php esc_html_e( 'Cancel', 'wow-dynamic-deals-for-woo' ); ?></button>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Rule', 'wow-dynamic-deals-for-woo' ); ?></button>
			</div>
		</form>
	</div>
</div>
