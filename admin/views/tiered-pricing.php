<?php
/**
 * Tiered Pricing View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'wdd_tiered_pricing';
$rules = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY priority ASC, id DESC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
?>

<div class="wdd-page-header wdd-flex-between">
	<div>
		<h2>📊 <?php esc_html_e( 'Tiered Pricing Rules', 'woo-dynamic-deals' ); ?></h2>
		<p><?php esc_html_e( 'Offer quantity-based discounts to encourage bulk purchases', 'woo-dynamic-deals' ); ?></p>
	</div>
	<button class="wdd-btn wdd-btn-primary wdd-add-rule" data-type="tiered">
		<span style="font-size: 18px;">➕</span> <?php esc_html_e( 'Add Tiered Pricing', 'woo-dynamic-deals' ); ?>
	</button>
</div>

<div class="wdd-rules-list">
	<?php if ( empty( $rules ) ) : ?>
		<div class="wdd-empty-state wdd-fade-in">
			<div class="wdd-empty-state-icon">📊</div>
			<h3><?php esc_html_e( 'No Tiered Pricing Rules Yet', 'woo-dynamic-deals' ); ?></h3>
			<p><?php esc_html_e( 'Create tiered pricing to reward customers who buy in bulk!', 'woo-dynamic-deals' ); ?></p>
			<button class="wdd-btn wdd-btn-primary wdd-btn-lg wdd-add-rule" data-type="tiered">
				<span style="font-size: 20px;">➕</span> <?php esc_html_e( 'Create Your First Tier', 'woo-dynamic-deals' ); ?>
			</button>
		</div>
	<?php else : ?>
		<div class="wdd-table-wrapper wdd-fade-in">
			<table class="wdd-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Priority', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Title', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Mode', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Tiers', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Status', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Schedule', 'woo-dynamic-deals' ); ?></th>
						<th><?php esc_html_e( 'Actions', 'woo-dynamic-deals' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rules as $rule ) : ?>
						<tr>
							<td><span class="wdd-badge wdd-badge-active"><?php echo esc_html( $rule->priority ); ?></span></td>
							<td><strong><?php echo esc_html( $rule->title ); ?></strong></td>
							<td><?php echo esc_html( ucwords( str_replace( '_', ' ', $rule->calculation_mode ) ) ); ?></td>
							<td>
								<strong style="color: #667eea;">
									<?php
									$tiers = maybe_unserialize( $rule->tiers );
									echo esc_html( is_array( $tiers ) ? count( $tiers ) . ' tier(s)' : '0 tiers' );
									?>
								</strong>
							</td>
							<td>
								<?php if ( 'active' === $rule->status ) : ?>
									<span class="wdd-badge wdd-badge-active">✓ <?php esc_html_e( 'Active', 'woo-dynamic-deals' ); ?></span>
								<?php else : ?>
									<span class="wdd-badge wdd-badge-inactive">✗ <?php esc_html_e( 'Inactive', 'woo-dynamic-deals' ); ?></span>
								<?php endif; ?>
							</td>
							<td>
								<?php
								// Check if dates are valid (not empty, not null, not 0000-00-00)
								$has_valid_date_from = ! empty( $rule->date_from ) && '0000-00-00' !== substr( $rule->date_from, 0, 10 ) && 'NULL' !== $rule->date_from;
								$has_valid_date_to = ! empty( $rule->date_to ) && '0000-00-00' !== substr( $rule->date_to, 0, 10 ) && 'NULL' !== $rule->date_to;
								
								if ( $has_valid_date_from || $has_valid_date_to ) {
									echo '<span style="font-size: 12px; color: #6c757d;">';
									echo esc_html( $has_valid_date_from ? date_i18n( 'M j, Y', strtotime( $rule->date_from ) ) : '—' );
									echo ' → ';
									echo esc_html( $has_valid_date_to ? date_i18n( 'M j, Y', strtotime( $rule->date_to ) ) : '—' );
									echo '</span>';
								} else {
									echo '<span style="color: #10b981; font-weight: 600;">♾️ ' . esc_html__( 'Always Active', 'woo-dynamic-deals' ) . '</span>';
								}
								?>
							</td>
							<td>
								<div class="wdd-table-actions">
									<button class="wdd-edit-btn wdd-edit-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										✏️ <?php esc_html_e( 'Edit', 'woo-dynamic-deals' ); ?>
									</button>
									<button class="wdd-duplicate-btn wdd-duplicate-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										📋 <?php esc_html_e( 'Copy', 'woo-dynamic-deals' ); ?>
									</button>
									<button class="wdd-delete-btn wdd-delete-rule" data-rule-id="<?php echo esc_attr( $rule->id ); ?>">
										🗑️ <?php esc_html_e( 'Delete', 'woo-dynamic-deals' ); ?>
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
// Include the modal.
require_once WDD_PLUGIN_DIR . 'admin/views/modals/tiered-edit-modal.php';

