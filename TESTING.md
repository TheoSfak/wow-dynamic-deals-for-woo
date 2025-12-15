# Woo Dynamic Deals - Testing Guide

## Pre-Testing Checklist

- [ ] WordPress 5.8+ installed
- [ ] WooCommerce 6.0+ activated
- [ ] PHP 8.0+ verified
- [ ] Plugin activated successfully
- [ ] Test products created (at least 5)
- [ ] Test categories created (at least 3)
- [ ] Multiple user roles configured
- [ ] Browser dev tools ready

## Test Environment Setup

### Required Test Products

Create the following products:

1. **Simple Product A** - $100, category: Electronics
2. **Simple Product B** - $50, category: Electronics
3. **Simple Product C** - $25, category: Accessories
4. **Variable Product** - $75-$150, category: Electronics
5. **Gift Product** - $0 (for gift testing), category: Gifts

### Required User Roles

- Administrator (default)
- Shop Manager
- Customer
- Wholesale Customer (custom role)

## Manual Test Scenarios

### 1. Price Rules Testing

#### Test 1.1: Percentage Discount
**Steps:**
1. Go to WooCommerce > Dynamic Deals > Price Rules
2. Click "Add New Price Rule"
3. Set:
   - Title: "20% Off Electronics"
   - Adjustment Type: Percentage
   - Adjustment Value: 20
   - Categories: Electronics
4. Save rule
5. Visit Product A page

**Expected:**
- Rule saves successfully
- Product A shows discounted price (was $100, now $80)
- Original price shown with strikethrough
- Discount badge appears

#### Test 1.2: Fixed Price Override
**Steps:**
1. Create new price rule
2. Set:
   - Title: "Flash Sale - $39.99"
   - Adjustment Type: Fixed Price
   - Adjustment Value: 39.99
   - Products: Product B
3. Save and visit Product B

**Expected:**
- Product B price changes to $39.99
- Original price ($50) shown as crossed out

#### Test 1.3: Rule Priority
**Steps:**
1. Create Rule A: 10% off, priority 10
2. Create Rule B: 20% off, priority 5
3. Both target Product A
4. Visit Product A

**Expected:**
- Rule B (priority 5) executes first
- Final discount is 20%, not 10%

#### Test 1.4: Stop Further Rules
**Steps:**
1. Edit Rule B, enable "Stop further rules"
2. Visit Product A

**Expected:**
- Only Rule B applies
- No additional discounts stacked

#### Test 1.5: Date Scheduling
**Steps:**
1. Create rule with start date = tomorrow
2. Visit product page today

**Expected:**
- Rule doesn't apply
- Regular price shown

#### Test 1.6: Days of Week
**Steps:**
1. Create rule active only on Monday
2. Test on Monday and Tuesday

**Expected:**
- Monday: Rule applies
- Tuesday: Rule doesn't apply

#### Test 1.7: User Role Restrictions
**Steps:**
1. Create rule restricted to "Wholesale Customer"
2. Test as regular customer
3. Switch to wholesale customer role
4. Test again

**Expected:**
- Regular customer: No discount
- Wholesale customer: Discount applied

### 2. Tiered Pricing Testing

#### Test 2.1: Per Line Item Tiers
**Steps:**
1. Go to Tiered Pricing tab
2. Create new tiered pricing:
   - Title: "Buy More Save More"
   - Mode: Per Line Item
   - Tiers:
     * 1-2: 5% off
     * 3-5: 10% off
     * 6+: 15% off
   - Product: Product A
3. Save rule
4. Visit Product A page

**Expected:**
- Tiered pricing table displays with 3 rows
- Add 1 to cart: 5% off ($95)
- Add 3 to cart: 10% off ($90 each)
- Add 6 to cart: 15% off ($85 each)

#### Test 2.2: Combined Cart Quantity
**Steps:**
1. Create tiered pricing with mode: Combined
2. Tiers:
   - 1-5: 0% (base price)
   - 6-10: 10% off
   - 11+: 20% off
3. Products: Product A + Product B

**Expected:**
- Add 3× Product A + 3× Product B (total 6): 10% off both
- Discount applies to combined quantity

#### Test 2.3: Tier Ordering
**Steps:**
1. Create tiered pricing with 5 tiers
2. Drag tiers to reorder
3. Save rule

**Expected:**
- Tiers maintain custom order
- Database stores correct sequence

#### Test 2.4: Fixed Price Tiers
**Steps:**
1. Create tiered pricing
2. Tier 1: 6+ items at $75 fixed price
3. Add 7× Product A ($100 each)

**Expected:**
- Each item priced at $75
- Total: $525 (not $700)

### 3. Cart Discount Testing

#### Test 3.1: Percentage Cart Discount
**Steps:**
1. Go to Cart Discounts tab
2. Create rule:
   - Title: "10% Cart Discount"
   - Type: Percentage
   - Value: 10
   - Min cart total: $100
3. Add $80 to cart

**Expected:**
- No discount (below minimum)

4. Add more products (total $120)

**Expected:**
- 10% discount applied ($12 off)
- Cart total: $108

#### Test 3.2: Free Shipping
**Steps:**
1. Create cart discount with free shipping enabled
2. Min cart total: $50
3. Add $60 to cart

**Expected:**
- Free shipping option appears
- Discount applies (if set)

#### Test 3.3: Cart Quantity Conditions
**Steps:**
1. Create cart discount
2. Min quantity: 5
3. Max quantity: 10
4. Test with 3, 7, 12 items

**Expected:**
- 3 items: No discount
- 7 items: Discount applies
- 12 items: No discount (exceeds max)

#### Test 3.4: Fixed Amount Discount
**Steps:**
1. Create $15 fixed cart discount
2. Min cart total: $50
3. Add $60 to cart

**Expected:**
- $15 deducted from cart
- Final total: $45

### 4. Gift Rules Testing

#### Test 4.1: Product Trigger
**Steps:**
1. Go to Gifts tab
2. Create rule:
   - Title: "Free Gift with Product A"
   - Trigger: Product
   - Trigger Product: Product A
   - Gift: Gift Product
3. Add Product A to cart

**Expected:**
- Gift Product automatically added
- Gift shows $0 price
- Gift meta indicates it's free

#### Test 4.2: Category Trigger
**Steps:**
1. Create gift rule
2. Trigger: Category = Electronics
3. Gift: Gift Product
4. Add Product A (Electronics) to cart

**Expected:**
- Gift added automatically

#### Test 4.3: Cart Total Trigger
**Steps:**
1. Create gift rule
2. Trigger: Cart Total = $150
3. Gift: Gift Product
4. Add items totaling $100

**Expected:**
- No gift

5. Add more items (total $160)

**Expected:**
- Gift appears in cart

#### Test 4.4: Cart Quantity Trigger
**Steps:**
1. Create gift rule
2. Trigger: Cart Quantity = 5 items
3. Add 4 items

**Expected:**
- No gift

4. Add 1 more item (total 5)

**Expected:**
- Gift added

#### Test 4.5: Max Gifts Limit
**Steps:**
1. Create gift rule with max 2 gifts per order
2. Trigger multiple times (add 3× trigger product)

**Expected:**
- Only 2 gift products added
- Third trigger doesn't add gift

#### Test 4.6: Multiple Triggers
**Steps:**
1. Create 2 gift rules with different triggers
2. Trigger both in same cart

**Expected:**
- Both gifts added
- Each gift respects its max limit

### 5. Admin Interface Testing

#### Test 5.1: Modal Functionality
**Steps:**
1. Click "Add New Price Rule"
2. Fill form partially
3. Click backdrop to close

**Expected:**
- Modal doesn't close (force save or cancel)
- Data retained if reopened

#### Test 5.2: Select2 Product Search
**Steps:**
1. Open price rule modal
2. Click product search field
3. Type "product"

**Expected:**
- Dropdown shows matching products
- Can select multiple products
- Selected products appear as tags

#### Test 5.3: Tier Builder
**Steps:**
1. Open tiered pricing modal
2. Click "Add Tier" 3 times
3. Drag second tier to first position
4. Fill all tier data
5. Save

**Expected:**
- 3 tiers appear
- Drag-drop reorders tiers
- All data saves correctly
- Tier order persists

#### Test 5.4: Gift Trigger Field Toggle
**Steps:**
1. Open gift rule modal
2. Select trigger type: Product
3. Change to Category
4. Change to Cart Total

**Expected:**
- Product: Shows product selector
- Category: Shows category selector
- Cart Total: Shows amount input field
- Previous fields hide on change

#### Test 5.5: Edit Existing Rule
**Steps:**
1. Create price rule
2. Click edit icon
3. Modify title and value
4. Save

**Expected:**
- Modal pre-fills with existing data
- Changes save correctly
- Table updates immediately

#### Test 5.6: Delete Rule
**Steps:**
1. Click delete icon on rule
2. Confirm deletion

**Expected:**
- Confirmation dialog appears
- Rule removed from table
- Database entry deleted
- Cache cleared

#### Test 5.7: Duplicate Rule
**Steps:**
1. Click duplicate icon
2. Verify new rule appears

**Expected:**
- New rule created with "(Copy)" suffix
- All data duplicated except ID
- Both rules visible in table

#### Test 5.8: Active/Inactive Toggle
**Steps:**
1. Click toggle on active rule
2. Visit product page
3. Toggle back on

**Expected:**
- Off: Rule doesn't apply to products
- On: Rule applies again

### 6. Settings Testing

#### Test 6.1: Module Disable
**Steps:**
1. Go to Settings tab
2. Disable "Price Rules" module
3. Save settings
4. Visit product with price rule

**Expected:**
- Price rule doesn't apply
- Regular price shown

#### Test 6.2: Cache Configuration
**Steps:**
1. Enable cache
2. Set expiration to 60 seconds
3. Save settings
4. Create new rule

**Expected:**
- Rule cached
- Cache expires after 60 seconds

#### Test 6.3: Debug Mode
**Steps:**
1. Enable debug mode
2. Save settings
3. Trigger price calculation
4. Check WordPress debug.log

**Expected:**
- Log entries show rule processing
- Applied rules logged
- Calculation steps visible

### 7. Frontend Display Testing

#### Test 7.1: Tiered Pricing Table
**Steps:**
1. Create tiered pricing rule
2. Visit product page
3. Check table rendering

**Expected:**
- Table appears below price
- All tiers displayed correctly
- Note about calculation mode (if combined)

#### Test 7.2: Discount Badge
**Steps:**
1. Create price rule
2. Visit shop page

**Expected:**
- Badge appears on product thumbnail
- Shows discount percentage or "Sale"

#### Test 7.3: Savings Summary
**Steps:**
1. Add discounted products to cart
2. View cart page

**Expected:**
- Savings summary shows total saved
- Lists applied discount names

#### Test 7.4: Gift Message
**Steps:**
1. Trigger gift rule
2. View cart

**Expected:**
- Gift message appears
- Heart icon displayed
- Gift product name shown

### 8. Performance Testing

#### Test 8.1: Many Rules
**Steps:**
1. Create 50 price rules
2. Visit product page
3. Measure load time

**Expected:**
- Page loads in <1 second
- No timeout errors

#### Test 8.2: Cache Effectiveness
**Steps:**
1. Enable cache
2. Visit product page (first load)
3. Visit again (cached)
4. Compare load times

**Expected:**
- Cached load significantly faster
- Database queries reduced

#### Test 8.3: Large Cart
**Steps:**
1. Add 20 different products to cart
2. Apply multiple discount rules
3. View cart page

**Expected:**
- Cart calculates in <2 seconds
- No performance degradation

### 9. Security Testing

#### Test 9.1: Nonce Verification
**Steps:**
1. Open browser dev tools
2. Intercept AJAX save request
3. Remove nonce parameter
4. Send request

**Expected:**
- Request fails
- Error: "Invalid nonce"

#### Test 9.2: Capability Check
**Steps:**
1. Log in as Subscriber (no WooCommerce caps)
2. Try to access Dynamic Deals page

**Expected:**
- Access denied
- Redirect to dashboard or error page

#### Test 9.3: SQL Injection
**Steps:**
1. Open rule modal
2. Enter SQL in title field: `'; DROP TABLE wp_wdd_pricing_rules; --`
3. Save rule

**Expected:**
- Input sanitized
- No SQL execution
- Harmless string saved

### 10. Import/Export Testing

#### Test 10.1: Export All Rules
**Steps:**
1. Create rules of all types
2. Go to Settings > Export
3. Select "All"
4. Click Export

**Expected:**
- JSON file downloads
- Contains all rules
- Valid JSON format

#### Test 10.2: Import Rules
**Steps:**
1. Export rules
2. Delete all rules
3. Import exported JSON
4. Don't check overwrite

**Expected:**
- All rules restored
- IDs may differ
- Functionality identical

#### Test 10.3: Import with Overwrite
**Steps:**
1. Modify existing rule
2. Import old export with overwrite checked

**Expected:**
- Rule reverts to exported version
- Old data restored

#### Test 10.4: Invalid JSON
**Steps:**
1. Create invalid JSON file
2. Try to import

**Expected:**
- Error message displayed
- No database changes

## Edge Cases

### Edge Case 1: Negative Price
**Test:** Rule that would make price negative  
**Expected:** Price floors at $0

### Edge Case 2: Zero Quantity Tier
**Test:** Tier with min_quantity = 0  
**Expected:** Validation error or ignored

### Edge Case 3: Overlapping Schedules
**Test:** Two rules with overlapping date ranges  
**Expected:** Both apply based on priority

### Edge Case 4: Deleted Products
**Test:** Rule targets deleted product  
**Expected:** Rule skips silently, no errors

### Edge Case 5: Empty Cart Gift
**Test:** Gift trigger with empty cart  
**Expected:** No errors, gift rule ignored

## Browser Compatibility

Test in the following browsers:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

## Performance Benchmarks

Document the following:
- [ ] Time to load admin dashboard
- [ ] Time to save rule with 1000 products
- [ ] Time to calculate price with 10 active rules
- [ ] Time to process cart with 20 items
- [ ] Database queries per page load
- [ ] Memory usage with large dataset

## Regression Testing

After each update, re-test:
- [ ] All price rule types
- [ ] Tiered pricing calculation modes
- [ ] Cart discount conditions
- [ ] Gift trigger types
- [ ] Import/export functionality
- [ ] Cache invalidation

## Known Issues

Document any issues found:
1. Issue description
2. Steps to reproduce
3. Expected behavior
4. Actual behavior
5. Severity (critical/major/minor)
6. Workaround (if any)

## Testing Checklist Summary

- [ ] All 10 test categories completed
- [ ] Edge cases verified
- [ ] Browser compatibility confirmed
- [ ] Performance benchmarks documented
- [ ] Security tests passed
- [ ] Known issues logged
- [ ] Regression tests passed
- [ ] Ready for production release

---

## Document Information

**Author:** Theodore Sfakianakis  
**Plugin:** Woo Dynamic Deals  
**Version:** 1.0.0  
**Last Updated:** December 10, 2024

### Support the Developer

If you find this testing guide helpful, consider supporting the project:

**PayPal Donation:** https://www.paypal.com/paypalme/TheodoreSfakianakis
