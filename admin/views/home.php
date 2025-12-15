<?php
/**
 * Home Tab View
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wdd-home-container" style="max-width: 95%; margin: 40px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;">
	
	<!-- Welcome Section -->
	<div class="wdd-welcome-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 50px 40px; border-radius: 12px; text-align: center; margin-bottom: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
		<h1 style="font-size: 42px; margin: 0 0 15px 0; font-weight: 700;">🎁 Wow Dynamic Deals for Woo</h1>
		<p style="font-size: 20px; margin: 0; opacity: 0.95;">Powerful pricing engine for WooCommerce</p>
	</div>

	<!-- About Plugin Section -->
	<div class="wdd-about-section" style="background: white; padding: 35px 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 5px solid #667eea;">
		<h2 style="font-size: 28px; color: #2c3e50; margin: 0 0 25px 0; font-weight: 600;">📖 How It Works</h2>
		
		<div style="line-height: 1.8; color: #34495e; font-size: 16px;">
			<p style="margin-bottom: 15px;"><strong style="color: #667eea;">Wow Dynamic Deals for Woo (WDD)</strong> gives you complete control over your WooCommerce store pricing with four powerful features:</p>
			
			<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 25px 0;">
				<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #3498db;">
					<h3 style="margin: 0 0 10px 0; color: #3498db; font-size: 18px;">💰 Price Rules</h3>
					<p style="margin: 0; font-size: 14px; color: #5a6c7d;">Apply discounts based on products, categories, user roles, and conditions.</p>
				</div>
				
				<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #e74c3c;">
					<h3 style="margin: 0 0 10px 0; color: #e74c3c; font-size: 18px;">📊 Tiered Pricing</h3>
					<p style="margin: 0; font-size: 14px; color: #5a6c7d;">Offer bulk discounts with quantity-based pricing tiers.</p>
				</div>
				
				<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #2ecc71;">
					<h3 style="margin: 0 0 10px 0; color: #2ecc71; font-size: 18px;">🛒 Cart Discounts</h3>
					<p style="margin: 0; font-size: 14px; color: #5a6c7d;">Create cart-level promotions with flexible conditions.</p>
				</div>
				
				<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #9b59b6;">
					<h3 style="margin: 0 0 10px 0; color: #9b59b6; font-size: 18px;">🎁 Free Gifts</h3>
					<p style="margin: 0; font-size: 14px; color: #5a6c7d;">Reward customers with free products based on triggers.</p>
				</div>
			</div>

			<p style="margin-top: 25px; padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px; font-size: 15px;">
				<strong style="color: #856404;">💡 Quick Start:</strong> Navigate to any tab above to create your first deal. Each rule type has its own configuration options and can be activated/deactivated independently.
			</p>
		</div>
	</div>

	<!-- Important Disclaimers -->
	<div class="wdd-disclaimer-section" style="background: #fff; padding: 30px 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 2px solid #e74c3c;">
		<h2 style="font-size: 24px; color: #c0392b; margin: 0 0 20px 0; font-weight: 600;">⚠️ Important Information</h2>
		
		<div style="line-height: 1.9; color: #34495e; font-size: 15px;">
			<p style="margin-bottom: 12px;">
				<strong style="color: #e74c3c; font-size: 16px;">NO SUPPORT:</strong> This plugin is provided as-is without any official support channels. Community help may be available through WordPress forums.
			</p>
			
			<p style="margin-bottom: 12px;">
				<strong style="color: #e74c3c; font-size: 16px;">NO RESPONSIBILITY:</strong> The author and contributors assume <strong>NO responsibility</strong> for any issues, data loss, conflicts, or problems that may arise from using this plugin.
			</p>
			
			<p style="margin-bottom: 12px;">
				<strong style="color: #e74c3c; font-size: 16px;">FREE TO USE:</strong> This plugin is free and open-source. You may use, modify, and distribute it under the terms of the GPL license.
			</p>
			
			<p style="margin: 15px 0 0 0; padding: 15px; background: #f8d7da; border-radius: 6px; color: #721c24; font-weight: 500;">
				⚠️ <strong>USE AT YOUR OWN RISK:</strong> Always test on a staging environment before using in production. Backup your database regularly. The author is not liable for any consequences of using this plugin.
			</p>
		</div>
	</div>

	<!-- Donation Section -->
	<div class="wdd-donation-section" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 50px 40px; border-radius: 12px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
		<div style="font-size: 60px; margin-bottom: 20px;">❤️</div>
		
		<h2 style="font-size: 36px; margin: 0 0 15px 0; font-weight: 700;">Like this plugin?</h2>
		
		<p style="font-size: 20px; margin: 0 0 30px 0; opacity: 0.95; line-height: 1.6;">
			This plugin is <strong>100% free</strong> and took countless hours to develop.<br>
			If it helps your business, please consider buying me a coffee! ☕
	</p>

	<div style="margin: 35px 0;">
		<a href="https://www.paypal.com/paypalme/TheodoreSfakianakis" target="_blank" style="display: inline-block; background: white; color: #f5576c; padding: 20px 50px; border-radius: 50px; text-decoration: none; font-size: 22px; font-weight: 700; box-shadow: 0 5px 20px rgba(0,0,0,0.2); transition: transform 0.2s;">
			💝 Donate via PayPal
		</a>
	</div>		<p style="font-size: 18px; margin: 25px 0 0 0; font-weight: 500;">
			🙏 Your support keeps this plugin alive and motivates future updates!
		</p>
	</div>

	<!-- Author Section -->
	<div class="wdd-author-section" style="background: #2c3e50; color: white; padding: 40px; border-radius: 12px; text-align: center; margin-top: 30px;">
		<div style="font-size: 48px; margin-bottom: 15px;">👨‍💻</div>
		
		<h3 style="font-size: 28px; margin: 0 0 10px 0; font-weight: 600;">Created by</h3>
		<p style="font-size: 38px; margin: 0 0 20px 0; font-weight: 700; background: linear-gradient(90deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
			Theodore Sfakianakis
		</p>
		
		<p style="font-size: 18px; margin: 0; opacity: 0.9;">
			Passionate about creating powerful WooCommerce solutions
		</p>

		<div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.2);">
			<p style="font-size: 42px; margin: 0 0 15px 0; font-weight: 700;">THANK YOU!</p>
			<p style="font-size: 18px; margin: 0; opacity: 0.9;">
				For using Wow Dynamic Deals for Woo. Your success is my success! 🚀
			</p>
		</div>
	</div>

	<!-- Quick Links -->
	<div style="text-align: center; margin-top: 40px; padding: 30px; background: #ecf0f1; border-radius: 12px;">
		<p style="color: #7f8c8d; font-size: 14px; margin: 0;">
			Version 1.0.0 | Free & Open Source | Made with ❤️ by Theodore Sfakianakis for the WordPress Community
		</p>
	</div>

</div>

<style>
.wdd-donation-section a:hover {
	transform: scale(1.05);
}

@media (max-width: 768px) {
	.wdd-about-section div[style*="grid"] {
		grid-template-columns: 1fr !important;
	}
}
</style>
