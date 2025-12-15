<?php
/**
 * Main Plugin Class
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin class
 */
class Plugin {

	/**
	 * Single instance
	 *
	 * @var Plugin
	 */
	private static $instance = null;

	/**
	 * Plugin components
	 *
	 * @var array
	 */
	private $components = array();

	/**
	 * Get instance
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->load_textdomain();
		$this->init_hooks();
		$this->init_components();
	}

	/**
	 * Load plugin textdomain
	 */
	private function load_textdomain() {
		load_plugin_textdomain( 'woo-dynamic-deals', false, dirname( WDD_PLUGIN_BASENAME ) . '/languages' );
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'init', array( $this, 'init_engines' ), 20 );

		add_action( 'admin_init', array( $this, 'activation_redirect' ) );
	}

	/**
	 * Initialize plugin components
	 */
	private function init_components() {
		$this->components['security'] = new Security();
		$this->components['hooks']    = new Hooks();

		$this->components['frontend_display']     = new FrontendDisplay();
		$this->components['template_loader']      = new TemplateLoader();

		if ( is_admin() ) {
			$this->components['admin_menu']       = new Admin\AdminMenu();
			$this->components['ajax_handler']     = new Admin\AjaxHandler();
		}

		$this->components['purchase_history']     = new PurchaseHistory();
		$this->components['import_export']        = new ImportExport();
	}

	/**
	 * Initialize engines after WooCommerce is loaded
	 */
	public function init_engines() {
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}


		$this->components['price_engine']         = new Engines\PriceEngine();
		$this->components['tiered_pricing']       = new Engines\TieredPricingEngine();
		$this->components['cart_discount']        = new Engines\CartDiscountEngine();
		$this->components['gift_engine']          = new Engines\GiftEngine();
		
	}

	/**
	 * Init callback
	 */
	public function init() {
		do_action( 'wdd_init' );
	}

	/**
	 * Admin init callback
	 */
	public function admin_init() {
		do_action( 'wdd_admin_init' );
	}

	/**
	 * Enqueue frontend scripts and styles
	 */
	public function enqueue_frontend_scripts() {
		if ( ! is_product() && ! is_cart() && ! is_checkout() ) {
			return;
		}

		wp_enqueue_style(
			'wdd-frontend',
			WDD_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			WDD_VERSION
		);

		wp_enqueue_script(
			'wdd-frontend',
			WDD_PLUGIN_URL . 'assets/js/frontend.js',
			array( 'jquery' ),
			WDD_VERSION,
			true
		);

		wp_localize_script(
			'wdd-frontend',
			'wddFrontend',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wdd_frontend_nonce' ),
			)
		);
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( strpos( $hook, 'woo-dynamic-deals' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
			array(),
			'4.1.0'
		);

		wp_enqueue_script(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
			array( 'jquery' ),
			'4.1.0',
			true
		);

		wp_enqueue_style(
			'wdd-admin',
			WDD_PLUGIN_URL . 'assets/css/admin.css',
			array( 'select2' ),
			WDD_VERSION
		);

		wp_enqueue_style(
			'wdd-admin-enhanced',
			WDD_PLUGIN_URL . 'assets/css/admin/wdd-admin-enhanced.css',
			array( 'wdd-admin' ),
			WDD_VERSION
		);

		wp_enqueue_script(
			'wdd-admin',
			WDD_PLUGIN_URL . 'assets/js/admin/app.js',
			array( 'jquery', 'select2', 'wp-i18n' ),
			WDD_VERSION,
			true
		);

		wp_enqueue_script(
			'wdd-tiered',
			WDD_PLUGIN_URL . 'assets/js/admin/tiered.js',
			array( 'jquery', 'jquery-ui-sortable', 'wdd-admin' ),
			WDD_VERSION,
			true
		);

		wp_enqueue_script(
			'wdd-cart',
			WDD_PLUGIN_URL . 'assets/js/admin/cart.js',
			array( 'jquery', 'wdd-admin' ),
			WDD_VERSION,
			true
		);

		wp_enqueue_script(
			'wdd-gift',
			WDD_PLUGIN_URL . 'assets/js/admin/gift.js',
			array( 'jquery', 'wdd-admin' ),
			WDD_VERSION . '-' . time(), // Add timestamp to bust cache
			true
		);

		wp_localize_script(
			'wdd-admin',
			'wddAdmin',
			array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'wdd_ajax_action' ),
				'restUrl'  => rest_url( 'wdd/v1/' ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
				'i18n'     => array(
					'confirmDelete'      => __( 'Are you sure you want to delete this rule?', 'woo-dynamic-deals' ),
					'deleting'           => __( 'Deleting...', 'woo-dynamic-deals' ),
					'delete'             => __( 'Delete', 'woo-dynamic-deals' ),
					'deleteError'        => __( 'Failed to delete rule.', 'woo-dynamic-deals' ),
					'duplicating'      => __( 'Duplicating...', 'woo-dynamic-deals' ),
					'duplicate'           => __( 'Duplicate', 'woo-dynamic-deals' ),
					'duplicateError'      => __( 'Failed to duplicate rule.', 'woo-dynamic-deals' ),
					'ajaxError'           => __( 'An error occurred. Please try again.', 'woo-dynamic-deals' ),
					'noRules'             => __( 'No rules found.', 'woo-dynamic-deals' ),
					'saving'              => __( 'Saving...', 'woo-dynamic-deals' ),
					'saveRule'            => __( 'Save Rule', 'woo-dynamic-deals' ),
					'saveError'           => __( 'Failed to save rule.', 'woo-dynamic-deals' ),
					'saved'               => __( 'Rule saved successfully', 'woo-dynamic-deals' ),
					'loadError'           => __( 'Failed to load rule.', 'woo-dynamic-deals' ),
					'addRule'             => __( 'Add Price Rule', 'woo-dynamic-deals' ),
					'editRule'            => __( 'Edit Price Rule', 'woo-dynamic-deals' ),
					'addTieredPricing'    => __( 'Add Tiered Pricing', 'woo-dynamic-deals' ),
					'editTieredPricing'   => __( 'Edit Tiered Pricing', 'woo-dynamic-deals' ),
					'saveTieredPricing'   => __( 'Save Tiered Pricing', 'woo-dynamic-deals' ),
					'addCartDiscount'     => __( 'Add Cart Discount', 'woo-dynamic-deals' ),
					'editCartDiscount'    => __( 'Edit Cart Discount', 'woo-dynamic-deals' ),
					'saveCartDiscount'    => __( 'Save Cart Discount', 'woo-dynamic-deals' ),
					'addGiftRule'         => __( 'Add Gift Rule', 'woo-dynamic-deals' ),
					'editGiftRule'        => __( 'Edit Gift Rule', 'woo-dynamic-deals' ),
					'saveGiftRule'        => __( 'Save Gift Rule', 'woo-dynamic-deals' ),
					'searchProducts'      => __( 'Search for products...', 'woo-dynamic-deals' ),
					'searchUsers'         => __( 'Search for users...', 'woo-dynamic-deals' ),
					'selectCategories'    => __( 'Select categories...', 'woo-dynamic-deals' ),
					'error'               => __( 'An error occurred', 'woo-dynamic-deals' ),
				),
			)
		);

		wp_enqueue_media();
	}

	/**
	 * Handle activation redirect
	 */
	public function activation_redirect() {
		if ( get_transient( 'wdd_activation_redirect' ) ) {
			delete_transient( 'wdd_activation_redirect' );
			if ( ! isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_safe_redirect( admin_url( 'admin.php?page=woo-dynamic-deals' ) );
				exit;
			}
		}
	}

	/**
	 * Get component
	 *
	 * @param string $component Component name.
	 * @return mixed|null
	 */
	public function get_component( $component ) {
		return $this->components[ $component ] ?? null;
	}
}
