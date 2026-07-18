<?php
/**
 * Admin settings page for DisReview.
 *
 * @package DisReview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds the admin menu and renders the settings UI with a live preview.
 */
class DisReview_Admin {

	/**
	 * Page hook suffix.
	 *
	 * @var string|null
	 */
	private $hook = null;

	/**
	 * Constructor. Wires up admin hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'admin_init', array( 'DisReview_Settings', 'register' ) );
	}

	/**
	 * Add the plugin menu under Settings.
	 */
	public function add_menu() {
		$this->hook = add_options_page(
			__( 'DisReview', 'disreview' ),
			__( 'DisReview', 'disreview' ),
			'manage_options',
			'disreview',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets on the plugin page only.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue( $hook_suffix ) {
		if ( $hook_suffix !== $this->hook ) {
			return;
		}

		$settings = wp_parse_args( get_option( DisReview::OPTION_KEY, array() ), DisReview_Settings::defaults() );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style(
			'disreview-admin',
			DisReview::plugin_url() . 'assets/css/admin.css',
			array(),
			DisReview::VERSION
		);

		// Base + active theme for the live preview pane.
		wp_enqueue_style(
			'disreview-base',
			DisReview::plugin_url() . 'assets/css/base.css',
			array(),
			DisReview::VERSION
		);

		$themes = DisReview::instance()->get_themes();
		$theme  = $settings['theme'] ?? 'cards';
		if ( isset( $themes[ $theme ] ) ) {
			wp_enqueue_style(
				'disreview-theme',
				DisReview::plugin_url() . 'assets/css/themes/' . $themes[ $theme ]['file'],
				array( 'disreview-base' ),
				DisReview::VERSION
			);
		}

		wp_enqueue_script(
			'disreview-admin',
			DisReview::plugin_url() . 'assets/js/admin.js',
			array( 'jquery', 'wp-color-picker' ),
			DisReview::VERSION,
			true
		);

		// Pass current theme for live preview highlight.
		wp_localize_script(
			'disreview-admin',
			'DisReviewAdmin',
			array(
				'pluginUrl' => DisReview::plugin_url(),
				'themes'    => array_keys( DisReview::instance()->get_themes() ),
			)
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = wp_parse_args( get_option( DisReview::OPTION_KEY, array() ), DisReview_Settings::defaults() );
		$themes   = DisReview::instance()->get_themes();

		include DisReview::plugin_path() . 'templates/admin-settings.php';
	}
}
