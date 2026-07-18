<?php
/**
 * Frontend asset loader for DisReview.
 *
 * Loads the selected theme CSS only on pages that actually contain
 * comments / reviews, and only for the enabled platforms.
 *
 * @package DisReview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues the chosen theme stylesheet and shared overrides.
 */
class DisReview_Frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * Add the `disreview-active` class to <body> so scoped CSS applies.
	 *
	 * @param array $classes Existing body classes.
	 * @return array
	 */
	public function add_body_class( $classes ) {
		if ( $this->should_load() ) {
			$classes[] = 'disreview-active';
		}
		return $classes;
	}

	/**
	 * Determine if any review/comment styling is active on this request.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( DisReview::is_wp_core_enabled() && ( is_singular() && ( comments_open() || have_comments() || get_comments_number() ) ) ) {
			return true;
		}
		if ( DisReview::is_wc_enabled() && function_exists( 'is_product' ) && is_product() ) {
			return true;
		}
		if ( DisReview::is_edd_enabled() && function_exists( 'is_singular' ) && is_singular( array( 'download' ) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Enqueue the theme stylesheet + dynamic overrides.
	 */
	public function enqueue() {
		if ( ! $this->should_load() ) {
			return;
		}

		$theme = DisReview::get_setting( 'theme', 'cards' );
		$themes = DisReview::instance()->get_themes();

		if ( isset( $themes[ $theme ] ) ) {
			wp_enqueue_style(
				'disreview-theme',
				DisReview::plugin_url() . 'assets/css/themes/' . $themes[ $theme ]['file'],
				array(),
				DisReview::VERSION
			);
		}

		// Shared overrides / variables.
		wp_enqueue_style(
			'disreview-base',
			DisReview::plugin_url() . 'assets/css/base.css',
			array(),
			DisReview::VERSION
		);

		$this->add_dynamic_css();
	}

	/**
	 * Inject CSS custom properties based on admin settings.
	 */
	private function add_dynamic_css() {
		$primary = DisReview::get_setting( 'primary_color', '#4f46e5' );
		$radius  = DisReview::get_setting( 'border_radius', 14 );
		$font    = DisReview::get_setting( 'font_family', '' );
		$dark    = DisReview::get_setting( 'enable_dark', 'no' );

		$css  = ':root{';
		$css .= '--dr-primary:' . esc_attr( $primary ) . ';';
		$css .= '--dr-primary-rgb:' . $this->hex_to_rgb( $primary ) . ';';
		$css .= '--dr-radius:' . absint( $radius ) . 'px;';
		if ( $font ) {
			$css .= '--dr-font:' . esc_attr( $font ) . ';';
		}
		$css .= '}';

		if ( 'yes' === $dark ) {
			$css .= '@media (prefers-color-scheme: dark){:root{--dr-bg:#0f1115;--dr-surface:#1a1d23;--dr-text:#e6e6e6;--dr-muted:#9aa0a6;--dr-border:rgba(255,255,255,.1);}}';
		}

		wp_add_inline_style( 'disreview-base', $css );
	}

	/**
	 * Convert hex color to "r, g, b" string.
	 *
	 * @param string $hex Color hex.
	 * @return string
	 */
	private function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );
		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		return "{$r}, {$g}, {$b}";
	}
}
