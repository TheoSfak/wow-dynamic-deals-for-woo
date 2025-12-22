<?php
/**
 * Price Rules View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'wdd_pricing_rules';
$rules = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY priority ASC, id DESC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
?>

<div class="wdd-page-header wdd-flex-between">
	<div>
		<h2>💰 <?php esc_html_e( 'Price Rules', 'wow-dynamic-deals-for-woo' ); ?></h2>
		<p><?php esc_html_e( 'Create dynamic pricing rules to adjust product prices based on conditions', 'wow-dynamic-deals-for-woo' ); ?></p>
	</div>
	<button class="wdd-btn wdd-btn-primary wdd-add-rule" data-type="price">
		<span style="font-size: 18px;">➕</span> <?php esc_html_e( 'Add Price Rule', 'wow-dynamic-deals-for-woo' ); ?>
	</button>
</div>

<div class="wdd-rules-list">
	<?php if ( empty( $rules ) ) : ?>
		<div class="wdd-empty-state wdd-fade-in">
			<div class="wdd-empty-state-icon">📊</div>
			<h3><?php esc_html_e( 'No Price Rules Yet', 'wow-dynamic-deals-for-woo' ); ?></h3>
			<p><?php esc_html_e( 'Create your first price rule to start offering dynamic pricing to your customers!', 'wow-dynamic-deals-for-woo' ); ?></p>
			<button class="wdd-btn wdd-btn-primary wdd-btn-lg wdd-add-rule" data-type="price">
				<span style="font-size: 20px;">➕</span> <?php esc_html_e( 'Create Your First Rule', 'wow-dynamic-deals-for-woo' ); ?>
			</button>
		</div>
	<?php else : ?>
		<div class="wdd-table-wrapper wdd-fade-in">
			<table class="wdd-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Priority', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Title', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Type', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Value', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Status', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Schedule', 'wow-dynamic-deals-for-woo' ); ?></th>
						<th><?php esc_html_e( 'Actions', 'wow-dynamic-deals-for-woo' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rules as $rule ) : ?>
						<tr data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
							<td><span class="wdd-badge wdd-badge-active"><?php echo esc_html( $rule->priority ); ?></span></td>
							<td><strong><?php echo esc_html( $rule->title ); ?></strong></td>
							<td><?php echo esc_html( ucfirst( str_replace( '_', ' ', $rule->adjustment_type ) ) ); ?></td>
							<td>
								<strong style="color: #667eea; font-size: 15px;">
									<?php
									if ( 'fixed_price' === $rule->adjustment_type ) {
										echo wc_price( $rule->adjustment_value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} elseif ( strpos( $rule->adjustment_type, 'percentage' ) !== false ) {
										echo esc_html( $rule->adjustment_value . '%' );
									} else {
										echo wc_price( $rule->adjustment_value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									?>
								</strong>
							</td>
							<td>
								<?php if ( 'active' === $rule->status ) : ?>
									<span class="wdd-badge wdd-badge-active">✓ <?php esc_html_e( 'Active', 'wow-dynamic-deals-for-woo' ); ?></span>
								<?php else : ?>
									<span class="wdd-badge wdd-badge-inactive">✗ <?php esc_html_e( 'Inactive', 'wow-dynamic-deals-for-woo' ); ?></span>
								<?php endif; ?>
							</td>
							<td>
								<?php
								$has_valid_date_from = ! empty( $rule->date_from ) && '0000-00-00' !== substr( $rule->date_from, 0, 10 ) && 'NULL' !== $rule->date_from;
								$has_valid_date_to = ! empty( $rule->date_to ) && '0000-00-00' !== substr( $rule->date_to, 0, 10 ) && 'NULL' !== $rule->date_to;
								
								if ( $has_valid_date_from || $has_valid_date_to ) {
									echo '<span style="font-size: 12px; color: #6c757d;">';
									echo esc_html( $has_valid_date_from ? date_i18n( 'M j, Y', strtotime( $rule->date_from ) ) : '—' );
									echo ' → ';
									echo esc_html( $has_valid_date_to ? date_i18n( 'M j, Y', strtotime( $rule->date_to ) ) : '—' );
									echo '</span>';
								} else {
									echo '<span style="color: #10b981; font-weight: 600;">♾️ ' . esc_html__( 'Always Active', 'wow-dynamic-deals-for-woo' ) . '</span>';
								}
								?>
							</td>
							<td>
								<div class="wdd-table-actions">
									<button class="wdd-edit-btn wdd-edit-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										✏️ <?php esc_html_e( 'Edit', 'wow-dynamic-deals-for-woo' ); ?>
									</button>
									<button class="wdd-duplicate-btn wdd-duplicate-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										📋 <?php esc_html_e( 'Copy', 'wow-dynamic-deals-for-woo' ); ?>
									</button>
									<button class="wdd-delete-btn wdd-delete-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										🗑️ <?php esc_html_e( 'Delete', 'wow-dynamic-deals-for-woo' ); ?>
									</button>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>

<?php
require_once WDD_PLUGIN_DIR . 'admin/views/modals/rule-edit-modal.php';

