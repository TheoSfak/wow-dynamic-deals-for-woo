# Woo Dynamic Deals

[![WordPress Plugin](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-6.0%2B-purple.svg)](https://woocommerce.com/)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/version-1.0.0-orange.svg)](https://github.com/TheoSfak/woo-dynamic-deals/releases)
[![Donate](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/paypalme/TheodoreSfakianakis)

> Advanced dynamic pricing, tiered discounts, cart-level promotions, and free gift management for WooCommerce.

**Author:** Theodore Sfakianakis  
**Version:** 1.0.0  
**License:** GPL v2 or later

## Features

### 🎯 Price Rules
- **5 Adjustment Types:**
  - Percentage discount
  - Fixed amount discount
  - Fixed price override
  - Markup percentage
  - Markup fixed amount
- Apply to regular prices, sale prices, or both
- Product/category targeting with AJAX search
- User role and specific user restrictions
- Date/time scheduling with days of week
- Priority system with rule cascade control

### 📊 Tiered Quantity Pricing
- Unlimited pricing tiers
- Drag-and-drop tier ordering
- **Calculation Modes:**
  - Per line item (progressive tiers)
  - Combined cart quantity (bulk pricing)
- 3 discount types per tier: percentage, fixed, or fixed price
- Automatic frontend pricing table display

### 🛒 Cart Discounts
- Percentage or fixed amount discounts
- Free shipping option
- **Cart Conditions:**
  - Minimum/maximum cart total
  - Minimum/maximum cart quantity
- User role targeting
- Schedule support

### 🎁 Free Gifts
- **4 Trigger Types:**
  - Specific product purchase
  - Category purchase
  - Cart total threshold
  - Cart quantity threshold
- Multiple gift products
- Max gifts per order limit
- Automatic $0 pricing with gift meta

## Installation

1. **Upload Plugin:**
   - Upload `woo-dynamic-deals` folder to `/wp-content/plugins/`
   - Or install via WordPress admin: Plugins > Add New > Upload Plugin

2. **Activate:**
   - Go to Plugins menu
   - Find "Woo Dynamic Deals"
   - Click "Activate"

3. **Configure:**
   - Navigate to WooCommerce > Dynamic Deals
   - Enable desired modules in Settings tab
   - Create your first rules

## Usage Guide

### Creating Price Rules

1. Go to **WooCommerce > Dynamic Deals > Price Rules**
2. Click **"Add New Price Rule"**
3. Fill in the form:
   - **Title:** Internal name for the rule
   - **Priority:** Lower numbers execute first (default: 10)
   - **Adjustment Type:** Select discount/markup type
   - **Adjustment Value:** Enter amount
   - **Apply To:** Choose price type (regular/sale/both)
   - **Products:** Select specific products (optional)
   - **Categories:** Select categories (optional)
   - **Schedule:** Set active dates/times/days (optional)
   - **Users:** Restrict to roles or specific users (optional)
4. Click **"Save Rule"**

### Creating Tiered Pricing

1. Go to **Tiered Pricing** tab
2. Click **"Add New Tiered Pricing"**
3. Set calculation mode:
   - **Per Line:** Tiers apply to individual products
   - **Combined:** Tiers apply to total cart quantity
4. Add tiers:
   - Click **"Add Tier"**
   - Set min/max quantity range
   - Choose discount type and value
   - Add more tiers as needed
5. Drag tiers to reorder
6. Click **"Save Tiered Pricing"**

### Creating Cart Discounts

1. Go to **Cart Discounts** tab
2. Click **"Add New Cart Discount"**
3. Configure discount:
   - Set discount type (percentage/fixed)
   - Enter discount value
   - Enable free shipping (optional)
4. Set cart conditions:
   - Minimum/maximum cart total
   - Minimum/maximum cart quantity
5. Add scheduling/user restrictions
6. Click **"Save Cart Discount"**

### Creating Gift Rules

1. Go to **Gifts** tab
2. Click **"Add New Gift Rule"**
3. Select trigger type:
   - **Product:** Triggered by specific product purchase
   - **Category:** Triggered by category purchase
   - **Cart Total:** Triggered at cart total threshold
   - **Cart Quantity:** Triggered at quantity threshold
4. Set trigger value (product/category/amount/quantity)
5. Select gift products
6. Set max gifts per order (0 = unlimited)
7. Click **"Save Gift Rule"**

## Settings

### Module Control
- Enable/disable each rule type independently
- Price Rules
- Tiered Pricing
- Cart Discounts
- Gifts

### Cache Configuration
- Enable/disable caching system
- Set cache expiration time (seconds)
- Default: 3600 seconds (1 hour)

### Debug Mode
- Enable to log rule processing
- View logs in WordPress debug.log

## Template Overrides

Copy templates to your theme for customization:

```
your-theme/
└── woo-dynamic-deals/
    ├── tiered-pricing-table.php
    ├── discount-badge.php
    ├── gift-message.php
    └── savings-summary.php
```

## Developer Hooks

### Actions

```php
// Fired after plugin initialization
do_action('wdd_init');

// Fired after admin initialization
do_action('wdd_admin_init');

// Fired when a rule is created
do_action('wdd_rule_created', $rule_id, $rule_type);

// Fired when a rule is updated
do_action('wdd_rule_updated', $rule_id, $rule_type);

// Fired when a rule is deleted
do_action('wdd_rule_deleted', $rule_id, $rule_type);
```

### Filters

```php
// Modify price before applying rules
apply_filters('wdd_before_price_calculation', $price, $product);

// Modify final calculated price
apply_filters('wdd_final_price', $price, $product, $applied_rules);

// Modify tiered pricing tiers
apply_filters('wdd_tiered_tiers', $tiers, $product);

// Modify cart discount amount
apply_filters('wdd_cart_discount_amount', $discount, $cart);
```

## FAQ

**Q: Can I combine multiple rules?**  
A: Yes, rules with higher priority execute first. Use "Stop further rules" to prevent cascade.

**Q: Do price rules work with sale prices?**  
A: Yes, choose "Apply To" option: regular, sale, or both prices.

**Q: Can I schedule rules for specific days?**  
A: Yes, use the scheduling section to set dates, times, and days of week.

**Q: How do I target wholesale customers?**  
A: Use user role restrictions to target specific roles like "Wholesale Customer".

**Q: Can gifts be limited per customer?**  
A: Currently limited per order. Per-customer limits coming in future version.

**Q: Is HPOS compatible?**  
A: Yes, fully compatible with High-Performance Order Storage.

## Troubleshooting

### Rules not applying

1. Check rule is **Active** (toggle in rules list)
2. Verify schedule dates/times are current
3. Check user role restrictions match current user
4. Review priority - higher priority rules may stop cascade
5. Enable Debug Mode in Settings to see processing logs

### Tiered pricing table not showing

1. Verify Tiered Pricing module is enabled in Settings
2. Check tiered rule is active and scheduled
3. Ensure product/category targeting matches
4. Clear WordPress cache

### Gifts not adding to cart

1. Verify gift products exist and are in stock
2. Check trigger conditions are met
3. Review max gifts limit
4. Enable Debug Mode to check processing

### Performance issues with many rules

1. Enable cache in Settings
2. Use product/category targeting to limit rule evaluation
3. Optimize database with rule priorities
4. Consider increasing cache expiration time

## Import/Export

Export rules for backup or migration:

1. Go to **Settings** tab
2. Click **"Export Rules"**
3. Choose rule type (or "All")
4. Save JSON file

Import rules:

1. Go to **Settings** tab
2. Click **"Import Rules"**
3. Upload JSON file
4. Choose overwrite option
5. Click **"Import"**

## Support

- **Documentation:** [GitHub Wiki](https://github.com/yourusername/woo-dynamic-deals/wiki)
- **Issues:** [GitHub Issues](https://github.com/yourusername/woo-dynamic-deals/issues)
- **Email:** support@example.com

## Changelog

### 1.0.0 - December 15, 2025

**Initial Release**

- ✅ Price Rules engine with 5 adjustment types
- ✅ Tiered Quantity Pricing with unlimited tiers
- ✅ Cart Discount Rules with free shipping
- ✅ Free Gift Rules with 4 trigger types
- ✅ Modern admin dashboard with modals
- ✅ AJAX product/category/user search
- ✅ Advanced scheduling (dates, times, weekdays)
- ✅ User role and individual user targeting
- ✅ Customizable frontend display with color/font options
- ✅ Import/Export functionality
- ✅ Bulk actions support
- ✅ HPOS compatibility
- ✅ Translation ready
- ✅ Comprehensive settings page

## 👤 Author

**Theodore Sfakianakis**

- GitHub: [@TheoSfak](https://github.com/TheoSfak)
- PayPal: [TheodoreSfakianakis](https://www.paypal.com/paypalme/TheodoreSfakianakis)

## 💝 Support Development

If you find this plugin helpful, consider supporting future development:

[![Donate with PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/paypalme/TheodoreSfakianakis)

**PayPal:** https://www.paypal.com/paypalme/TheodoreSfakianakis

Your support helps maintain and improve this plugin! ❤️

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2025 Theodore Sfakianakis

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

---

Made with ❤️ by Theodore Sfakianakis

## License

GPL v2 or later. See LICENSE file for details.
