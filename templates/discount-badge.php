<?php
/**
 * Discount Badge Template
 *
 * @package WDD
 * @var string $discount_text The discount text to display
 * @var string $badge_type Type of badge (sale, discount, etc)
 */

defined('ABSPATH') || exit;
?>

<span class="wdd-discount-badge wdd-badge-<?php echo esc_attr($badge_type); ?>">
    <?php echo esc_html($discount_text); ?>
</span>
