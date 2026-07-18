<?php
/**
 * Plugin Name: DisReview
 * Plugin URI:  https://example.com/disreview
 * Description: استایل‌دهی حرفه‌ای و جذاب به بخش نظرات و کامنت‌ها در وردپرس، ووکامرس و Easy Digital Downloads. شامل ۶ تم آماده و پنل تنظیمات یوزرفرندلی.
 * Version:     1.0.0
 * Author:      DisReview Team
 * Author URI:  https://example.com
 * License:     GPL-2.0-or-later
 * Text Domain: disreview
 * Domain Path: /languages
 *
 * @package DisReview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! class_exists( 'DisReview' ) ) {

	/**
	 * Main DisReview plugin class.
	 *
	 * Handles bootstrap, dependency detection (WP Core / WooCommerce / EDD),
	 * settings registration and conditional asset loading.
	 */
	class DisReview {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Option key used to store all settings.
		 *
		 * @var string
		 */
		const OPTION_KEY = 'disreview_settings';

		/**
		 * Singleton instance.
		 *
		 * @var DisReview|null
		 */
		private static $instance = null;

		/**
		 * Available theme presets.
		 *
		 * @var array
		 */
		private $themes = array();

		/**
		 * Get the singleton instance.
		 *
		 * @return DisReview
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor. Registers hooks.
		 */
		private function __construct() {
			$this->define_themes();

			add_action( 'init', array( $this, 'init' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		/**
		 * Plugin URL helper.
		 *
		 * @return string
		 */
		public static function plugin_url() {
			return plugin_dir_url( __FILE__ );
		}

		/**
		 * Plugin path helper.
		 *
		 * @return string
		 */
		public static function plugin_path() {
			return plugin_dir_path( __FILE__ );
		}

		/**
		 * Define the built-in theme presets.
		 *
		 * Each theme maps to a CSS file inside assets/css/themes/.
		 */
		private function define_themes() {
			$this->themes = array(
				'minimal'      => array(
					'label' => __( 'مینیمال (Minimal)', 'disreview' ),
					'file'  => 'theme-minimal.css',
				),
				'cards'        => array(
					'label' => __( 'کارتی (Cards)', 'disreview' ),
					'file'  => 'theme-cards.css',
				),
				'bubbles'      => array(
					'label' => __( 'حبابی (Bubbles)', 'disreview' ),
					'file'  => 'theme-bubbles.css',
				),
				'glass'        => array(
					'label' => __( 'شیشه‌ای مدرن (Glass)', 'disreview' ),
					'file'  => 'theme-glass.css',
				),
				'dark'         => array(
					'label' => __( 'تیره و لوکس (Dark Elegant)', 'disreview' ),
					'file'  => 'theme-dark.css',
				),
				'gradient'     => array(
					'label' => __( 'گرادیانی (Gradient)', 'disreview' ),
					'file'  => 'theme-gradient.css',
				),
				'neon'         => array(
					'label' => __( 'نئون (Neon)', 'disreview' ),
					'file'  => 'theme-neon.css',
				),
				'outline'      => array(
					'label' => __( 'خطی (Outline)', 'disreview' ),
					'file'  => 'theme-outline.css',
				),
			);
		}

		/**
		 * Get registered themes.
		 *
		 * @return array
		 */
		public function get_themes() {
			return $this->themes;
		}

		/**
		 * Load plugin text domain for translations.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'disreview', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Initialize the plugin: load dependencies and register hooks once WordPress is ready.
		 */
		public function init() {
			// Always load dependencies so settings/helpers are available everywhere.
			require_once self::plugin_path() . 'includes/class-settings.php';
			require_once self::plugin_path() . 'includes/class-frontend.php';
			require_once self::plugin_path() . 'includes/class-admin.php';
			require_once self::plugin_path() . 'includes/class-tools.php';

			// Register settings (safe to call on both admin and frontend).
			DisReview_Settings::register();

			// Admin UI.
			if ( is_admin() ) {
				new DisReview_Admin();
			}

			// Frontend styling (only when enabled somewhere).
			new DisReview_Frontend();

			// Tools: import/export + shortcode + animation.
			new DisReview_Tools();
		}

		/**
		 * Get a single setting value with fallback.
		 *
		 * @param string $key     Setting key.
		 * @param mixed  $default Default value.
		 * @return mixed
		 */
		public static function get_setting( $key, $default = '' ) {
			$settings = get_option( self::OPTION_KEY, array() );
			return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
		}

		/**
		 * Detect whether WooCommerce is active.
		 *
		 * @return bool
		 */
		public static function is_woocommerce_active() {
			return class_exists( 'WooCommerce' ) || function_exists( 'WC' );
		}

		/**
		 * Detect whether Easy Digital Downloads is active.
		 *
		 * @return bool
		 */
		public static function is_edd_active() {
			return class_exists( 'Easy_Digital_Downloads' ) || function_exists( 'edd_get_download' );
		}

		/**
		 * Whether WP Core comments styling is enabled.
		 *
		 * @return bool
		 */
		public static function is_wp_core_enabled() {
			return 'yes' === self::get_setting( 'enable_wp', 'yes' );
		}

		/**
		 * Whether WooCommerce reviews styling is enabled.
		 *
		 * @return bool
		 */
		public static function is_wc_enabled() {
			return self::is_woocommerce_active() && 'yes' === self::get_setting( 'enable_wc', 'no' );
		}

		/**
		 * Whether EDD reviews styling is enabled.
		 *
		 * @return bool
		 */
		public static function is_edd_enabled() {
			return self::is_edd_active() && 'yes' === self::get_setting( 'enable_edd', 'no' );
		}
	}

	// Bootstrap.
	add_action( 'plugins_loaded', array( 'DisReview', 'instance' ), 5 );
}

// Instantiate immediately so the singleton exists for early consumers.
DisReview::instance();
