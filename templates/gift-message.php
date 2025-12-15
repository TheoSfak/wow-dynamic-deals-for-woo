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
        esc_html__('Free gift: %s × %d', 'wow-dynamic-deals-for-woo'),
        esc_html($gift_name),
        absint($quantity)
    ); 
    ?>
</div>
