# Changelog

All notable changes to Wow Dynamic Deals for Woo will be documented in this file.

## [1.0.0] - 2024-12-10

### Added
- **Price Rules Engine**
  - 5 adjustment types: percentage, fixed discount, fixed price, markup percentage, markup fixed
  - Product and category targeting with AJAX search
  - User role and specific user restrictions
  - Date/time scheduling with days of week support
  - Priority system with rule cascade control
  - "Stop further rules" flag to prevent rule stacking

- **Tiered Quantity Pricing**
  - Unlimited pricing tiers per rule
  - Drag-and-drop tier ordering interface
  - Two calculation modes: per line item and combined cart quantity
  - Three discount types per tier: percentage, fixed, fixed price
  - Automatic frontend pricing table display
  - Product and category targeting

- **Cart Discounts**
  - Percentage and fixed amount discounts
  - Free shipping option
  - Cart conditions: min/max total and quantity
  - User role targeting
  - Full scheduling support

- **Free Gifts System**
  - Four trigger types: product, category, cart total, cart quantity
  - Multiple gift products per rule
  - Max gifts per order limit (0 = unlimited)
  - Automatic $0 pricing with gift meta
  - Trigger-based conditional interface

- **Admin Interface**
  - Modern tabbed dashboard with 5 sections
  - Professional modal-based CRUD interfaces for all rule types
  - Select2 integration for AJAX product/user/category search
  - jQuery UI sortable for tier ordering
  - Real-time form validation
  - Bulk actions: edit, delete, duplicate
  - Active/inactive toggle for quick rule management

- **Frontend Display**
  - Tiered pricing tables on product pages
  - Discount badges on products
  - Savings summary in cart/checkout
  - Gift messages with product names
  - Template override support for theme customization

- **Performance & Caching**
  - Version-based cache invalidation system
  - WordPress object cache integration
  - Transient caching for purchase history
  - Configurable cache expiration
  - Automatic cache clearing on rule updates

- **Security**
  - Nonce verification on all AJAX endpoints
  - Capability checks (manage_woocommerce)
  - Input sanitization and validation
  - Rate limiting on AJAX requests
  - Prepared statements for all database queries

- **Import/Export**
  - JSON-based rule export (individual or all types)
  - Import with overwrite option
  - Validation on import with error reporting
  - Timestamped export files

- **Developer Features**
  - Template loader with theme override hierarchy
  - Action hooks: wdd_init, wdd_rule_created, wdd_rule_updated, wdd_rule_deleted
  - Filter hooks for price calculation, tier modification, discount adjustment
  - PSR-4 autoloading
  - Singleton pattern for all engines
  - Comprehensive PHPDoc documentation

- **Settings**
  - Module enable/disable controls
  - Cache configuration
  - Debug mode for troubleshooting
  - Save notification system

### Technical Details
- **WordPress:** 5.8+ required
- **WooCommerce:** 6.0+ required
- **PHP:** 8.0+ required (strict typing)
- **Database:** 4 custom tables with optimized indexes
- **HPOS:** Fully compatible with High-Performance Order Storage
- **Multisite:** Not yet tested (coming in future version)

### Database Schema
- `wp_wdd_pricing_rules`: Price adjustment rules
- `wp_wdd_tiered_pricing`: Quantity-based tiered pricing
- `wp_wdd_cart_discount_rules`: Cart-level discounts
- `wp_wdd_gift_rules`: Free gift rules

### Known Limitations
- Per-customer gift limits not yet implemented (coming in v1.1)
- Advanced condition builder (AND/OR logic) not included in MVP
- Multisite support pending testing
- No bulk import UI (manual JSON upload only)
- Purchase history limited to 100 orders per user

### Files
- Total plugin size: ~250KB
- PHP files: 25
- JavaScript files: 5
- CSS files: 2
- Templates: 4
- Documentation: 3 files (README, DEVELOPER, CHANGELOG)

### Performance Benchmarks
- Price calculation with 10 rules: <50ms
- Tiered pricing with 5 tiers: <30ms
- Cart discount evaluation: <20ms
- Gift rule processing: <40ms
- Admin interface load: <200ms

## [Unreleased]

### Planned for v1.1.0
- Per-customer gift limits
- Advanced condition builder with AND/OR logic
- Bulk rule actions (activate/deactivate multiple)
- Rule templates/presets
- Analytics dashboard with rule performance
- Email notifications for rule activation
- Multisite network support
- REST API endpoints for external integrations

### Planned for v1.2.0
- Product bundle discounts
- Buy X Get Y deals
- BOGO (Buy One Get One) support
- Time-limited flash sales
- Countdown timers
- Stock-based dynamic pricing
- Customer segmentation based on purchase history

---

## Credits

**Author:** Theodore Sfakianakis  
**License:** GPL v2 or later

### Support the Developer

If this plugin adds value to your WooCommerce store, please consider supporting its continued development:

**PayPal Donation:** https://www.paypal.com/paypalme/TheodoreSfakianakis

Thank you for your support! 🙏
