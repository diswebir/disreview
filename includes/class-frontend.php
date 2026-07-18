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

		// Optional average rating badge above WooCommerce / EDD reviews.
		if ( 'yes' === DisReview::get_setting( 'enable_avg', 'yes' ) ) {
			add_action( 'woocommerce_before_single_product_summary', array( $this, 'wc_average_badge' ), 15 );
		}
	}

	/**
	 * Render an average rating badge above WooCommerce product reviews.
	 */
	public function wc_average_badge() {
		if ( ! DisReview::is_wc_enabled() || ! function_exists( 'wc_get_product' ) ) {
			return;
		}
		$product = wc_get_product( get_the_ID() );
		if ( ! $product ) {
			return;
		}
		$avg = $product->get_average_rating();
		$count = $product->get_rating_count();
		if ( ! $avg ) {
			return;
		}
		printf(
			'<div class="dr-avg-badge" aria-label="%1$s">★ %2$s <span class="dr-avg-count">(%3$s)</span></div>',
			esc_attr__( 'امتیاز میانگین', 'disreview' ),
			esc_html( $avg ),
			esc_html( $count )
		);
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

		// On mobile, allow admin to skip styling if disabled.
		if ( wp_is_mobile() && 'no' === DisReview::get_setting( 'load_mobile', 'yes' ) ) {
			return;
		}

		$theme  = DisReview::get_setting( 'theme', 'cards' );
		$themes = DisReview::instance()->get_themes();

		// Base first (defines CSS variables), then the theme preset.
		wp_enqueue_style(
			'disreview-base',
			DisReview::plugin_url() . 'assets/css/base.css',
			array(),
			DisReview::VERSION
		);

		if ( isset( $themes[ $theme ] ) ) {
			wp_enqueue_style(
				'disreview-theme',
				DisReview::plugin_url() . 'assets/css/themes/' . $themes[ $theme ]['file'],
				array( 'disreview-base' ),
				DisReview::VERSION
			);
		}

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
