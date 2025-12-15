<?php
/**
 * Rules Examples View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wdd-examples-page">
	<div class="wdd-page-header">
		<div>
			<h2>📚 <?php esc_html_e( 'Rules Examples & Use Cases', 'wow-dynamic-deals-for-woo' ); ?></h2>
			<p><?php esc_html_e( 'Click any example below to see detailed setup instructions for common promotional scenarios', 'wow-dynamic-deals-for-woo' ); ?></p>
		</div>
	</div>

	<div class="wdd-examples-grid">
		
		<!-- Example 1: Flash Sale -->
		<div class="wdd-example-card wdd-example-card-purple">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">⚡</span>
				<h3><?php esc_html_e( 'Flash Sale - 24 Hour Deal', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> 30% off all products for 24 hours only
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "24 Hour Flash Sale"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 30</li>
						<li><strong>Apply To:</strong> All Products</li>
						<li><strong>Date From:</strong> Today's date</li>
						<li><strong>Date To:</strong> Tomorrow's date</li>
						<li><strong>Priority:</strong> 1</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> All products automatically get 30% discount for 24 hours
				</div>
			</div>
		</div>

		<!-- Example 2: Buy 2 Get 1 Free -->
		<div class="wdd-example-card wdd-example-card-green">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎯</span>
				<h3><?php esc_html_e( 'Buy 2 Get 1 Free', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Customer buys 2 items, gets the cheapest one free
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Tiered Pricing</strong> → Click <strong>Add Tiered Rule</strong></li>
						<li><strong>Title:</strong> "Buy 2 Get 1 Free"</li>
						<li><strong>Apply To:</strong> Select specific category (e.g., "T-Shirts")</li>
						<li><strong>Tier 1:</strong> Min Quantity: 3, Discount: 33.33%</li>
						<li><strong>Status:</strong> Active</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> When customer adds 3 items, they get ~33% off (equivalent to 1 free)
				</div>
			</div>
		</div>

		<!-- Example 3: VIP Customer Discount -->
		<div class="wdd-example-card wdd-example-card-gold">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">👑</span>
				<h3><?php esc_html_e( 'VIP Customer 20% Discount', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Give 20% discount to all VIP/Wholesale customers
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "VIP Customer Discount"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 20</li>
						<li><strong>Apply To:</strong> All Products</li>
						<li><strong>User Roles:</strong> Check "Wholesale" or custom VIP role</li>
						<li><strong>Priority:</strong> 1</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Only logged-in VIP customers see 20% reduced prices
				</div>
			</div>
		</div>

		<!-- Example 4: Weekend Sale -->
		<div class="wdd-example-card wdd-example-card-blue">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">📅</span>
				<h3><?php esc_html_e( 'Weekend Sale - Saturday & Sunday', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> 15% off every Saturday and Sunday, ongoing
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Weekend Sale"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 15</li>
						<li><strong>Apply To:</strong> All Products or specific category</li>
						<li><strong>Days of Week:</strong> Check "Saturday" and "Sunday"</li>
						<li><strong>Date From/To:</strong> Leave empty (always active on weekends)</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Discount automatically applies every weekend forever
				</div>
			</div>
		</div>

		<!-- Example 5: Free Shipping over $100 -->
		<div class="wdd-example-card wdd-example-card-teal">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🚚</span>
				<h3><?php esc_html_e( 'Free Shipping Over $100', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Offer free shipping when cart total exceeds $100
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Cart Discounts</strong> → Click <strong>Add Cart Rule</strong></li>
						<li><strong>Title:</strong> "Free Shipping $100+"</li>
						<li><strong>Condition Type:</strong> Cart Subtotal</li>
						<li><strong>Min Value:</strong> 100</li>
						<li><strong>Discount Type:</strong> Fixed Amount (or use WooCommerce native free shipping method)</li>
						<li><strong>Apply Free Shipping:</strong> Yes (if available in settings)</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Shipping becomes free automatically at checkout when cart ≥ $100
				</div>
			</div>
		</div>

		<!-- Example 6: Bulk Discount - Buy More Save More -->
		<div class="wdd-example-card wdd-example-card-orange">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">📦</span>
				<h3><?php esc_html_e( 'Bulk Discount - Buy More, Save More', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> 5-9 items: 10% off | 10-19 items: 15% off | 20+ items: 20% off
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Tiered Pricing</strong> → Click <strong>Add Tiered Rule</strong></li>
						<li><strong>Title:</strong> "Bulk Discount"</li>
						<li><strong>Apply To:</strong> All Products or specific category</li>
						<li><strong>Tier 1:</strong> Min Qty: 5, Max Qty: 9, Discount: 10%</li>
						<li><strong>Tier 2:</strong> Min Qty: 10, Max Qty: 19, Discount: 15%</li>
						<li><strong>Tier 3:</strong> Min Qty: 20, Max Qty: (leave empty), Discount: 20%</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Customers automatically get higher discounts as they buy more
				</div>
			</div>
		</div>

		<!-- Example 7: Birthday Month Gift -->
		<div class="wdd-example-card wdd-example-card-pink">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎂</span>
				<h3><?php esc_html_e( 'Birthday Month Special Gift', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Give free birthday gift to any customer who shops this month
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Free Gifts</strong> → Click <strong>Add Gift Rule</strong></li>
						<li><strong>Title:</strong> "December Birthday Gift"</li>
						<li><strong>Condition:</strong> Cart Subtotal, Min Value: 1</li>
						<li><strong>Gift Products:</strong> Select a small gift item (e.g., keychain, sample)</li>
						<li><strong>Max Gifts per Order:</strong> 1</li>
						<li><strong>Date From:</strong> Dec 1</li>
						<li><strong>Date To:</strong> Dec 31</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Free gift automatically added to cart for all December orders
				</div>
			</div>
		</div>

		<!-- Example 8: Clearance Sale -->
		<div class="wdd-example-card wdd-example-card-red">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🏷️</span>
				<h3><?php esc_html_e( 'Clearance - Fixed Price $9.99', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> All clearance items priced at exactly $9.99
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Clearance Fixed Price"</li>
						<li><strong>Adjustment Type:</strong> Fixed Price</li>
						<li><strong>Value:</strong> 9.99</li>
						<li><strong>Apply To:</strong> Select "Clearance" category</li>
						<li><strong>Priority:</strong> 1 (highest to override other rules)</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> All clearance products show $9.99 regardless of original price
				</div>
			</div>
		</div>

		<!-- Example 9: Student Discount -->
		<div class="wdd-example-card wdd-example-card-indigo">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎓</span>
				<h3><?php esc_html_e( 'Student Discount - 10% Off', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Students get 10% off all purchases
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>First, create a "Student" user role in WordPress</li>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Student Discount"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 10</li>
						<li><strong>Apply To:</strong> All Products</li>
						<li><strong>User Roles:</strong> Check "Student"</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Users with student role automatically see 10% lower prices
				</div>
			</div>
		</div>

		<!-- Example 10: Happy Hour -->
		<div class="wdd-example-card wdd-example-card-yellow">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🕐</span>
				<h3><?php esc_html_e( 'Happy Hour - 3PM to 6PM Daily', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> 25% off every day between 3PM and 6PM
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Happy Hour"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 25</li>
						<li><strong>Apply To:</strong> All Products or specific category</li>
						<li><strong>Time From:</strong> 15:00 (3 PM)</li>
						<li><strong>Time To:</strong> 18:00 (6 PM)</li>
						<li><strong>Date From/To:</strong> Leave empty for always active</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Discount automatically appears every day during happy hour
				</div>
			</div>
		</div>

		<!-- Example 11: First Order Discount -->
		<div class="wdd-example-card wdd-example-card-cyan">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎊</span>
				<h3><?php esc_html_e( 'First Order - 15% Welcome Discount', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> New customers get 15% off their first purchase
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Cart Discounts</strong> → Click <strong>Add Cart Rule</strong></li>
						<li><strong>Title:</strong> "First Order Welcome"</li>
						<li><strong>Discount Type:</strong> Percentage</li>
						<li><strong>Value:</strong> 15</li>
						<li><strong>Cart Conditions:</strong> Check "Apply only to first order (new customers)"</li>
						<li><strong>Status:</strong> Active</li>
						<li>Click <strong>Save Cart Discount</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Both logged-in users and guests automatically get 15% off their first order. System checks order history by user ID (for logged-in) or billing email (for guests).
				</div>
			</div>
		</div>

		<!-- Example 12: Seasonal Category Sale -->
		<div class="wdd-example-card wdd-example-card-green">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">❄️</span>
				<h3><?php esc_html_e( 'Winter Collection - 40% Off', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> End of season sale - 40% off all winter items
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Winter Sale"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 40</li>
						<li><strong>Apply To:</strong> Select "Winter Collection" category</li>
						<li><strong>Date From:</strong> Feb 1</li>
						<li><strong>Date To:</strong> Feb 28</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> All winter products automatically discounted 40% in February
				</div>
			</div>
		</div>

		<!-- Example 13: Buy X Get Y Free -->
		<div class="wdd-example-card wdd-example-card-purple">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎁</span>
				<h3><?php esc_html_e( 'Buy Laptop, Get Free Mouse', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Purchase any laptop and receive a free wireless mouse
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Free Gifts</strong> → Click <strong>Add Gift Rule</strong></li>
						<li><strong>Title:</strong> "Free Mouse with Laptop"</li>
						<li><strong>Condition Type:</strong> Product in Cart</li>
						<li><strong>Products:</strong> Select all laptop products</li>
						<li><strong>Gift Products:</strong> Select wireless mouse product</li>
						<li><strong>Max Gifts per Order:</strong> 1</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Free mouse automatically added when customer adds any laptop
				</div>
			</div>
		</div>

		<!-- Example 14: Spend $200 Get $20 Off -->
		<div class="wdd-example-card wdd-example-card-blue">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">💵</span>
				<h3><?php esc_html_e( 'Spend $200, Save $20', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Get $20 discount when cart total reaches $200
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Cart Discounts</strong> → Click <strong>Add Cart Rule</strong></li>
						<li><strong>Title:</strong> "Spend $200 Save $20"</li>
						<li><strong>Condition Type:</strong> Cart Subtotal</li>
						<li><strong>Min Value:</strong> 200</li>
						<li><strong>Discount Type:</strong> Fixed Amount</li>
						<li><strong>Discount Value:</strong> 20</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> $20 discount automatically applied when cart ≥ $200
				</div>
			</div>
		</div>

		<!-- Example 15: Category Bundle Deal -->
		<div class="wdd-example-card wdd-example-card-orange">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">📚</span>
				<h3><?php esc_html_e( 'Buy 3 Books, Get 50% Off', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Purchase 3 or more books and save 50%
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Tiered Pricing</strong> → Click <strong>Add Tiered Rule</strong></li>
						<li><strong>Title:</strong> "Books Bundle"</li>
						<li><strong>Apply To:</strong> Select "Books" category</li>
						<li><strong>Tier 1:</strong> Min Qty: 3, Discount: 50%</li>
						<li><strong>Status:</strong> Active</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> When customer adds 3+ books, prices drop by 50%
				</div>
			</div>
		</div>

		<!-- Example 16: BOGO (Buy One Get One) -->
		<div class="wdd-example-card wdd-example-card-teal">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🔥</span>
				<h3><?php esc_html_e( 'BOGO - Buy One Get One 50% Off', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Buy 2 items, get second item at 50% off
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Tiered Pricing</strong> → Click <strong>Add Tiered Rule</strong></li>
						<li><strong>Title:</strong> "BOGO 50% Off"</li>
						<li><strong>Apply To:</strong> Select specific products or category</li>
						<li><strong>Tier 1:</strong> Min Qty: 2, Max Qty: 2, Discount: 25%</li>
						<li><em>Note: 25% off 2 items = 50% off the cheaper one</em></li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Second item automatically discounted when buying pairs
				</div>
			</div>
		</div>

		<!-- Example 17: Member Exclusive -->
		<div class="wdd-example-card wdd-example-card-gold">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">⭐</span>
				<h3><?php esc_html_e( 'Members Only - Special Pricing', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Premium members see special prices on exclusive products
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Premium Member Pricing"</li>
						<li><strong>Adjustment Type:</strong> Fixed Price or Percentage</li>
						<li><strong>Value:</strong> (your special price or discount %)</li>
						<li><strong>Apply To:</strong> Select "Premium Products" category</li>
						<li><strong>User Roles:</strong> Check "Subscriber" or custom member role</li>
						<li><strong>Priority:</strong> 1</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Only premium members see and can purchase at special prices
				</div>
			</div>
		</div>

		<!-- Example 18: Early Bird Discount -->
		<div class="wdd-example-card wdd-example-card-pink">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🐦</span>
				<h3><?php esc_html_e( 'Early Bird - 6AM to 10AM', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Reward early shoppers with 12% discount
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Early Bird Special"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 12</li>
						<li><strong>Apply To:</strong> All Products</li>
						<li><strong>Time From:</strong> 06:00 (6 AM)</li>
						<li><strong>Time To:</strong> 10:00 (10 AM)</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Morning shoppers automatically save 12% between 6-10 AM
				</div>
			</div>
		</div>

		<!-- Example 19: Holiday Sale -->
		<div class="wdd-example-card wdd-example-card-red">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎄</span>
				<h3><?php esc_html_e( 'Christmas Sale - December Special', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> 35% off sitewide during entire December
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Price Rules</strong> → Click <strong>Add Price Rule</strong></li>
						<li><strong>Title:</strong> "Christmas Sale"</li>
						<li><strong>Adjustment Type:</strong> Percentage Discount</li>
						<li><strong>Value:</strong> 35</li>
						<li><strong>Apply To:</strong> All Products</li>
						<li><strong>Date From:</strong> Dec 1</li>
						<li><strong>Date To:</strong> Dec 31</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> All products discounted 35% throughout December
				</div>
			</div>
		</div>

		<!-- Example 20: Free Sample -->
		<div class="wdd-example-card wdd-example-card-indigo">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🎁</span>
				<h3><?php esc_html_e( 'Free Sample with Every Order', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Add free product sample to all orders over $50
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Free Gifts</strong> → Click <strong>Add Gift Rule</strong></li>
						<li><strong>Title:</strong> "Free Sample"</li>
						<li><strong>Condition Type:</strong> Cart Subtotal</li>
						<li><strong>Min Value:</strong> 50</li>
						<li><strong>Gift Products:</strong> Select your sample product</li>
						<li><strong>Max Gifts per Order:</strong> 1</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Free sample automatically added to carts over $50
				</div>
			</div>
		</div>

		<!-- Example 21: Loyalty Reward Tiers -->
		<div class="wdd-example-card wdd-example-card-purple">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">💎</span>
				<h3><?php esc_html_e( 'Loyalty Tiers - Spend More, Save More', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> $100-199: 5% | $200-299: 10% | $300+: 15% off
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Cart Discounts</strong> → Create 3 separate rules:</li>
						<li><strong>Rule 1:</strong> Title "Bronze Tier", Min: 100, Max: 199, Discount: 5%</li>
						<li><strong>Rule 2:</strong> Title "Silver Tier", Min: 200, Max: 299, Discount: 10%</li>
						<li><strong>Rule 3:</strong> Title "Gold Tier", Min: 300, Discount: 15%</li>
						<li>Set priorities: Bronze=3, Silver=2, Gold=1</li>
						<li>Click <strong>Save Rule</strong> for each</li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> Discount automatically increases as cart value grows
				</div>
			</div>
		</div>

		<!-- Example 22: Combo Deal -->
		<div class="wdd-example-card wdd-example-card-cyan">
			<div class="wdd-example-header">
				<span class="wdd-example-icon">🍔</span>
				<h3><?php esc_html_e( 'Combo Deal - Phone + Case = 20% Off', 'wow-dynamic-deals-for-woo' ); ?></h3>
				<span class="wdd-example-toggle">+</span>
			</div>
			<div class="wdd-example-content">
				<div class="wdd-example-scenario">
					<strong>📋 Scenario:</strong> Buy phone and case together, save 20%
				</div>
				<div class="wdd-example-steps">
					<strong>🔧 Setup:</strong>
					<ol>
						<li>Go to <strong>Cart Discounts</strong> → Click <strong>Add Cart Rule</strong></li>
						<li><strong>Title:</strong> "Phone + Case Combo"</li>
						<li><strong>Condition Type:</strong> Products in Cart</li>
						<li><strong>Required Products:</strong> Select phone category AND case category</li>
						<li><strong>Discount Type:</strong> Percentage</li>
						<li><strong>Value:</strong> 20</li>
						<li>Click <strong>Save Rule</strong></li>
					</ol>
				</div>
				<div class="wdd-example-result">
					<strong>✨ Result:</strong> 20% discount when both phone and case are in cart
				</div>
			</div>
		</div>

	</div>
</div>

<style>
.wdd-examples-page {
	padding: 20px;
}

.wdd-examples-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
	gap: 20px;
	margin-top: 30px;
}

.wdd-example-card {
	background: white;
	border-radius: 12px;
	box-shadow: 0 2px 10px rgba(0,0,0,0.08);
	overflow: hidden;
	transition: all 0.3s ease;
	border-left: 5px solid;
}

.wdd-example-card:hover {
	box-shadow: 0 4px 20px rgba(0,0,0,0.15);
	transform: translateY(-2px);
}

.wdd-example-card-purple { border-left-color: #9333ea; }
.wdd-example-card-green { border-left-color: #10b981; }
.wdd-example-card-gold { border-left-color: #f59e0b; }
.wdd-example-card-blue { border-left-color: #3b82f6; }
.wdd-example-card-teal { border-left-color: #14b8a6; }
.wdd-example-card-orange { border-left-color: #f97316; }
.wdd-example-card-pink { border-left-color: #ec4899; }
.wdd-example-card-red { border-left-color: #ef4444; }
.wdd-example-card-indigo { border-left-color: #6366f1; }
.wdd-example-card-yellow { border-left-color: #eab308; }
.wdd-example-card-cyan { border-left-color: #06b6d4; }

.wdd-example-header {
	padding: 20px;
	background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 12px;
	transition: background 0.2s ease;
}

.wdd-example-header:hover {
	background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.wdd-example-icon {
	font-size: 28px;
	flex-shrink: 0;
}

.wdd-example-header h3 {
	margin: 0;
	font-size: 16px;
	font-weight: 600;
	color: #1e293b;
	flex: 1;
}

.wdd-example-toggle {
	font-size: 24px;
	font-weight: bold;
	color: #64748b;
	transition: transform 0.3s ease;
	flex-shrink: 0;
}

.wdd-example-card.active .wdd-example-toggle {
	transform: rotate(45deg);
}

.wdd-example-content {
	display: none;
	padding: 25px;
	background: white;
}

.wdd-example-card.active .wdd-example-content {
	display: block;
	animation: slideDown 0.3s ease;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.wdd-example-scenario {
	padding: 15px;
	background: #f0f9ff;
	border-left: 4px solid #3b82f6;
	border-radius: 6px;
	margin-bottom: 20px;
	font-size: 14px;
	line-height: 1.6;
}

.wdd-example-steps {
	background: #fefce8;
	padding: 15px;
	border-radius: 6px;
	border-left: 4px solid #eab308;
	margin-bottom: 20px;
}

.wdd-example-steps strong {
	display: block;
	margin-bottom: 10px;
	color: #1e293b;
}

.wdd-example-steps ol {
	margin: 10px 0 0 0;
	padding-left: 20px;
}

.wdd-example-steps li {
	margin-bottom: 8px;
	font-size: 14px;
	line-height: 1.6;
	color: #475569;
}

.wdd-example-steps li strong {
	display: inline;
	color: #1e293b;
	font-weight: 600;
}

.wdd-example-result {
	padding: 15px;
	background: #f0fdf4;
	border-left: 4px solid #10b981;
	border-radius: 6px;
	font-size: 14px;
	line-height: 1.6;
}

.wdd-example-result strong {
	color: #1e293b;
}

@media (max-width: 1024px) {
	.wdd-examples-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	$('.wdd-example-header').on('click', function() {
		const $card = $(this).closest('.wdd-example-card');
		const isActive = $card.hasClass('active');
		
		$('.wdd-example-card').removeClass('active');
		
		if (!isActive) {
			$card.addClass('active');
		}
	});
});
</script>
