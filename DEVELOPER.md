# Woo Dynamic Deals - Developer Documentation

## Architecture Overview

### Plugin Structure

```
woo-dynamic-deals/
├── woo-dynamic-deals.php       # Bootstrap file
├── includes/
│   ├── class-autoloader.php     # PSR-4 autoloader
│   ├── class-plugin.php         # Main singleton orchestrator
│   ├── class-database.php       # Schema management
│   ├── class-cache-manager.php  # Cache invalidation
│   ├── class-security.php       # Validation/sanitization
│   ├── class-hooks.php          # Hook consolidation
│   ├── class-purchase-history.php # Order tracking
│   ├── class-template-loader.php  # Template system
│   ├── class-import-export.php    # Data portability
│   ├── class-frontend-display.php # Display components
│   ├── engines/
│   │   ├── class-rule-engine.php         # Base evaluation
│   │   ├── class-price-engine.php        # Dynamic pricing
│   │   ├── class-tiered-pricing-engine.php # Quantity tiers
│   │   ├── class-cart-discount-engine.php # Cart discounts
│   │   └── class-gift-engine.php         # Free gifts
│   └── admin/
│       ├── class-admin-menu.php  # Menu registration
│       └── class-ajax-handler.php # AJAX endpoints
├── admin/views/               # Admin interface templates
├── templates/                # Frontend templates
└── assets/                   # CSS/JS files
```

### Namespace

All classes use the `WDD\` namespace with PSR-4 autoloading.

## Database Schema

### wp_wdd_pricing_rules

Stores price adjustment rules.

```sql
CREATE TABLE wp_wdd_pricing_rules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    priority INT DEFAULT 10,
    adjustment_type VARCHAR(50) NOT NULL, -- percentage|fixed_discount|fixed_price|markup_percentage|markup_fixed
    adjustment_value DECIMAL(10,2) NOT NULL,
    apply_to VARCHAR(20) DEFAULT 'regular', -- regular|sale|both
    product_ids TEXT,              -- Serialized array
    category_ids TEXT,             -- Serialized array
    start_date DATETIME,
    end_date DATETIME,
    start_time TIME,
    end_time TIME,
    days_of_week TEXT,            -- Serialized array [0-6]
    user_roles TEXT,              -- Serialized array
    user_ids TEXT,                -- Serialized array
    stop_further_rules TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);
```

### wp_wdd_tiered_pricing

Stores quantity-based tiered pricing.

```sql
CREATE TABLE wp_wdd_tiered_pricing (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    priority INT DEFAULT 10,
    product_ids TEXT,
    category_ids TEXT,
    calculation_mode VARCHAR(20) DEFAULT 'per_line', -- per_line|combined
    tiers TEXT NOT NULL,          -- Serialized array of tier objects
    start_date DATETIME,
    end_date DATETIME,
    start_time TIME,
    end_time TIME,
    days_of_week TEXT,
    user_roles TEXT,
    user_ids TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);
```

**Tier Structure:**
```php
[
    [
        'min_quantity' => 1,
        'max_quantity' => 5,
        'discount_type' => 'percentage', // percentage|fixed|fixed_price
        'discount_value' => 10
    ],
    // ... more tiers
]
```

### wp_wdd_cart_discount_rules

Stores cart-level discount rules.

```sql
CREATE TABLE wp_wdd_cart_discount_rules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    priority INT DEFAULT 10,
    discount_type VARCHAR(20) NOT NULL, -- percentage|fixed
    discount_value DECIMAL(10,2) NOT NULL,
    apply_free_shipping TINYINT(1) DEFAULT 0,
    min_cart_total DECIMAL(10,2),
    max_cart_total DECIMAL(10,2),
    min_cart_quantity INT,
    max_cart_quantity INT,
    start_date DATETIME,
    end_date DATETIME,
    start_time TIME,
    end_time TIME,
    days_of_week TEXT,
    user_roles TEXT,
    user_ids TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);
```

### wp_wdd_gift_rules

Stores free gift rules.

```sql
CREATE TABLE wp_wdd_gift_rules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    priority INT DEFAULT 10,
    trigger_type VARCHAR(50) NOT NULL, -- product|category|cart_total|cart_quantity
    trigger_products TEXT,    -- For product trigger
    trigger_categories TEXT,  -- For category trigger
    trigger_cart_total DECIMAL(10,2), -- For cart_total trigger
    trigger_cart_quantity INT, -- For cart_quantity trigger
    gift_products TEXT NOT NULL, -- Serialized array
    max_gifts_per_order INT DEFAULT 0, -- 0 = unlimited
    start_date DATETIME,
    end_date DATETIME,
    start_time TIME,
    end_time TIME,
    days_of_week TEXT,
    user_roles TEXT,
    user_ids TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);
```

## Hooks Reference

### Actions

#### wdd_init
Fired after plugin initialization, before admin_init.

```php
add_action('wdd_init', function() {
    // Plugin is fully loaded
});
```

#### wdd_admin_init
Fired after admin initialization.

```php
add_action('wdd_admin_init', function() {
    // Admin classes loaded
});
```

#### wdd_rule_created
Fired when a rule is created.

```php
add_action('wdd_rule_created', function($rule_id, $rule_type) {
    // $rule_type: 'price', 'tiered', 'cart', 'gift'
}, 10, 2);
```

#### wdd_rule_updated
Fired when a rule is updated.

```php
add_action('wdd_rule_updated', function($rule_id, $rule_type) {
    // Update related data
}, 10, 2);
```

#### wdd_rule_deleted
Fired when a rule is deleted.

```php
add_action('wdd_rule_deleted', function($rule_id, $rule_type) {
    // Cleanup related data
}, 10, 2);
```

#### wdd_before_price_calculation
Fired before price calculation starts.

```php
add_action('wdd_before_price_calculation', function($product) {
    // Pre-processing
}, 10, 1);
```

#### wdd_after_price_calculation
Fired after price calculation completes.

```php
add_action('wdd_after_price_calculation', function($product, $original_price, $final_price) {
    // Post-processing
}, 10, 3);
```

### Filters

#### wdd_before_price_calculation (filter)
Modify price before applying rules.

```php
add_filter('wdd_before_price_calculation', function($price, $product) {
    // Modify initial price
    return $price;
}, 10, 2);
```

#### wdd_final_price
Modify final calculated price.

```php
add_filter('wdd_final_price', function($price, $product, $applied_rules) {
    // $applied_rules is array of rule IDs that were applied
    return $price;
}, 10, 3);
```

#### wdd_tiered_tiers
Modify tiered pricing tiers before display.

```php
add_filter('wdd_tiered_tiers', function($tiers, $product) {
    // Modify tier structure
    return $tiers;
}, 10, 2);
```

#### wdd_cart_discount_amount
Modify cart discount amount.

```php
add_filter('wdd_cart_discount_amount', function($discount, $cart) {
    // Adjust discount calculation
    return $discount;
}, 10, 2);
```

#### wdd_gift_products
Modify gift products before adding to cart.

```php
add_filter('wdd_gift_products', function($products, $trigger_type, $rule_id) {
    // Modify gift product list
    return $products;
}, 10, 3);
```

#### wdd_cache_key
Modify cache key generation.

```php
add_filter('wdd_cache_key', function($key, $context) {
    // Customize cache key
    return $key;
}, 10, 2);
```

## Extension Examples

### Adding Custom Adjustment Type

```php
// Add new adjustment type to price engine
add_filter('wdd_adjustment_types', function($types) {
    $types['buy_one_get_one'] = __('Buy One Get One', 'your-plugin');
    return $types;
});

// Process custom adjustment
add_filter('wdd_calculate_price_adjustment', function($price, $adjustment_type, $adjustment_value, $product) {
    if ($adjustment_type === 'buy_one_get_one') {
        // Custom calculation logic
        $quantity = WC()->cart->get_cart_item_quantities()[$product->get_id()] ?? 1;
        if ($quantity >= 2) {
            $price = $price * 0.5; // 50% off for BOGO
        }
    }
    return $price;
}, 10, 4);
```

### Adding Custom Condition Type

```php
// Extend RuleEngine to add custom condition
add_filter('wdd_check_custom_conditions', function($passes, $rule, $context) {
    // Example: Check if user has purchased specific category before
    if (isset($rule->requires_previous_purchase)) {
        $user_id = get_current_user_id();
        $has_purchased = check_user_purchased_category($user_id, $rule->requires_previous_purchase);
        $passes = $passes && $has_purchased;
    }
    return $passes;
}, 10, 3);
```

### Creating Custom Display Component

```php
// Add custom savings display
add_action('woocommerce_after_add_to_cart_button', function() {
    global $product;
    
    // Get applicable rules
    $price_engine = \WDD\PriceEngine::get_instance();
    $rules = $price_engine->get_applicable_rules($product->get_id());
    
    if (!empty($rules)) {
        $original_price = $product->get_regular_price();
        $discounted_price = $product->get_price();
        $savings = $original_price - $discounted_price;
        
        if ($savings > 0) {
            echo '<div class="custom-savings">';
            echo '<strong>' . __('You save:', 'your-plugin') . '</strong> ';
            echo wc_price($savings);
            echo '</div>';
        }
    }
});
```

### Programmatically Creating Rules

```php
// Create price rule programmatically
function create_custom_price_rule() {
    global $wpdb;
    
    $rule_data = array(
        'title' => 'Black Friday Sale',
        'priority' => 5,
        'adjustment_type' => 'percentage',
        'adjustment_value' => 25,
        'apply_to' => 'both',
        'category_ids' => maybe_serialize([10, 15, 20]),
        'start_date' => '2024-11-29 00:00:00',
        'end_date' => '2024-11-29 23:59:59',
        'is_active' => 1,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql')
    );
    
    $table = $wpdb->prefix . 'wdd_pricing_rules';
    $wpdb->insert($table, $rule_data);
    
    $rule_id = $wpdb->insert_id;
    
    // Fire action
    do_action('wdd_rule_created', $rule_id, 'price');
    
    // Clear cache
    \WDD\CacheManager::clear_all_cache();
    
    return $rule_id;
}
```

### Accessing Rule Data

```php
// Get all active price rules
function get_active_price_rules() {
    global $wpdb;
    $table = $wpdb->prefix . 'wdd_pricing_rules';
    
    return $wpdb->get_results(
        "SELECT * FROM {$table} 
         WHERE is_active = 1 
         ORDER BY priority ASC"
    );
}

// Get rules for specific product
function get_product_rules($product_id) {
    $price_engine = \WDD\PriceEngine::get_instance();
    return $price_engine->get_applicable_rules($product_id);
}
```

## Cache System

### Cache Keys

```php
// Price rules cache
wdd_price_rules_{version}

// Tiered pricing cache
wdd_tiered_pricing_{version}

// Cart discount cache
wdd_cart_discounts_{version}

// Gift rules cache
wdd_gift_rules_{version}

// Product-specific cache
wdd_product_{product_id}_price_{version}
```

### Manual Cache Control

```php
// Clear all WDD cache
\WDD\CacheManager::clear_all_cache();

// Clear specific cache
\WDD\CacheManager::clear_cache('price_rules');

// Get cache version
$version = \WDD\CacheManager::get_cache_version();

// Increment version (invalidates all cache)
\WDD\CacheManager::increment_version();
```

## Security

### Nonce Verification

All AJAX endpoints use nonce verification:

```php
check_ajax_referer('wdd_admin_nonce', 'nonce');
```

### Capability Checks

```php
if (!current_user_can('manage_woocommerce')) {
    wp_send_json_error(__('Permission denied', 'woo-dynamic-deals'));
}
```

### Input Sanitization

Use the Security class for sanitization:

```php
$title = \WDD\Security::sanitize_text_field($_POST['title']);
$priority = \WDD\Security::sanitize_integer($_POST['priority']);
$price = \WDD\Security::sanitize_price($_POST['price']);
$ids = \WDD\Security::sanitize_array($_POST['ids']);
```

## Testing

### Unit Testing Rules

```php
// Test price calculation
$product = wc_get_product(123);
$original_price = $product->get_regular_price();

// Apply price engine
$price_engine = \WDD\PriceEngine::get_instance();
$final_price = $price_engine->calculate_price($product, $original_price);

// Assert expected discount
assert($final_price < $original_price);
```

### Performance Testing

```php
// Test with large number of rules
function test_performance_with_many_rules() {
    $start = microtime(true);
    
    // Create 100 rules
    for ($i = 0; $i < 100; $i++) {
        create_test_rule($i);
    }
    
    // Calculate price
    $product = wc_get_product(123);
    $price = \WDD\PriceEngine::get_instance()->calculate_price($product, 100);
    
    $end = microtime(true);
    $execution_time = $end - $start;
    
    echo "Execution time: {$execution_time} seconds\n";
}
```

## Best Practices

1. **Always clear cache after rule modifications**
2. **Use transients for expensive calculations**
3. **Target products/categories to limit rule evaluation**
4. **Set appropriate priorities to control execution order**
5. **Use stop_further_rules to prevent unnecessary processing**
6. **Enable caching in production environments**
7. **Use prepared statements for custom queries**
8. **Validate and sanitize all user input**
9. **Follow WordPress coding standards**
10. **Document custom extensions with PHPDoc**

## Support

For developer support:
- GitHub: https://github.com/TheoSfak/woo-dynamic-deals
- Developer: Theodore Sfakianakis
- Email: dev@example.com

## Support the Developer

If you find this plugin helpful for your projects, consider supporting future development:

**PayPal Donation:** https://www.paypal.com/paypalme/TheodoreSfakianakis

Your support helps maintain and improve this plugin!

---

**Author:** Theodore Sfakianakis  
**License:** GPL v2 or later
