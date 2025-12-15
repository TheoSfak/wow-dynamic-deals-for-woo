<?php
/**
 * Savings Summary Template
 *
 * @package WDD
 * @var float $total_savings Total amount saved
 * @var array $applied_rules Array of applied rule names
 */

defined('ABSPATH') || exit;
?>

<div class="wdd-savings-summary">
    <strong><?php esc_html_e('You saved:', 'woo-dynamic-deals'); ?></strong>
    <span class="wdd-savings-amount"><?php echo wc_price($total_savings); ?></span>
    
    <?php if (!empty($applied_rules)): ?>
        <div class="wdd-applied-rules">
            <small>
                <?php esc_html_e('Applied discounts:', 'woo-dynamic-deals'); ?>
                <?php echo esc_html(implode(', ', $applied_rules)); ?>
            </small>
        </div>
    <?php endif; ?>
</div>
