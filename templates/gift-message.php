<?php
/**
 * Gift Message Template
 *
 * @package WDD
 * @var string $gift_name Product name
 * @var int $quantity Quantity of gifts
 */

defined('ABSPATH') || exit;
?>

<div class="wdd-gift-message">
    <span class="dashicons dashicons-heart"></span>
    <?php
    printf(
        /* translators: 1: Gift product name, 2: Quantity */
        esc_html__('Free gift: %1$s × %2$d', 'wow-dynamic-deals-for-woo'),
        esc_html($gift_name),
        absint($quantity)
    );
    ?>
</div>
