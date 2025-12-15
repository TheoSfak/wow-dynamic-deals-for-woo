<?php
/**
 * Tiered Pricing Table Template
 *
 * @package WDD
 * @var array $tiers Array of tier data
 * @var string $mode Calculation mode (per_line or combined)
 */

defined('ABSPATH') || exit;
?>

<div class="wdd-tiered-pricing-table">
    <h4><?php esc_html_e('Quantity Discounts Available', 'woo-dynamic-deals'); ?></h4>
    <table>
        <thead>
            <tr>
                <th><?php esc_html_e('Quantity', 'woo-dynamic-deals'); ?></th>
                <th><?php esc_html_e('Discount', 'woo-dynamic-deals'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tiers as $tier): ?>
                <tr>
                    <td>
                        <?php 
                        if (empty($tier['max_quantity'])) {
                            echo esc_html($tier['min_quantity']) . '+';
                        } else {
                            echo esc_html($tier['min_quantity']) . ' - ' . esc_html($tier['max_quantity']);
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if ($tier['discount_type'] === 'percentage') {
                            echo esc_html($tier['discount_value']) . '%';
                        } elseif ($tier['discount_type'] === 'fixed') {
                            echo wc_price($tier['discount_value']) . ' ' . esc_html__('off', 'woo-dynamic-deals');
                        } else {
                            echo wc_price($tier['discount_value']) . ' ' . esc_html__('each', 'woo-dynamic-deals');
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($mode === 'combined'): ?>
        <p class="wdd-tiered-note">
            <?php esc_html_e('*Discount applies to entire cart quantity', 'woo-dynamic-deals'); ?>
        </p>
    <?php endif; ?>
</div>
