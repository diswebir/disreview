<?php
/**
 * Settings registration for DisReview.
 *
 * @package DisReview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles settings registration, sanitization and defaults.
 */
class DisReview_Settings {

	/**
	 * Register settings with the Settings API.
	 */
	public static function register() {
		register_setting(
			'disreview_settings_group',
			DisReview::OPTION_KEY,
			array( 'sanitize_callback' => array( __CLASS__, 'sanitize' ) )
		);
	}

	/**
	 * Default settings.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'theme'         => 'cards',
			'enable_wp'     => 'yes',
			'enable_wc'     => 'no',
			'enable_edd'    => 'no',
			'primary_color' => '#4f46e5',
			'font_family'   => '',
			'border_radius' => '14',
			'enable_dark'   => 'no',
			'enable_avg'    => 'yes',
			'load_mobile'   => 'yes',
			'custom_css'    => '',
			'show_count'    => 'yes',
			'child_override' => 'no',
		);
	}

	/**
	 * Sanitize submitted settings.
	 *
	 * @param array $input Raw input.
	 * @return array
	 */
		public static function sanitize( $input ) {
			$input    = is_array( $input ) ? $input : array();
			$defaults = self::defaults();
			$themes   = array_keys( DisReview::instance()->get_themes() );

			$clean = array();

			$theme = isset( $input['theme'] ) ? sanitize_key( $input['theme'] ) : $defaults['theme'];
			$clean['theme'] = in_array( $theme, $themes, true ) ? $theme : $defaults['theme'];

			$clean['enable_wp']   = isset( $input['enable_wp'] ) ? 'yes' : 'no';
			$clean['enable_wc']    = isset( $input['enable_wc'] ) ? 'yes' : 'no';
			$clean['enable_edd']   = isset( $input['enable_edd'] ) ? 'yes' : 'no';
			$clean['enable_dark']  = isset( $input['enable_dark'] ) ? 'yes' : 'no';
		$clean['enable_avg']   = isset( $input['enable_avg'] ) ? 'yes' : 'no';
		$clean['load_mobile']  = isset( $input['load_mobile'] ) ? 'yes' : 'no';
		$clean['show_count']   = isset( $input['show_count'] ) ? 'yes' : 'no';
		$clean['child_override'] = isset( $input['child_override'] ) ? 'yes' : 'no';

		$clean['primary_color'] = sanitize_hex_color( $input['primary_color'] ?? $defaults['primary_color'] );
		$clean['font_family']   = sanitize_text_field( $input['font_family'] ?? '' );
		$clean['border_radius'] = absint( $input['border_radius'] ?? $defaults['border_radius'] );
		// Custom CSS is output escaped via wp_strip_all_tags on the frontend; keep raw for the user.
		$clean['custom_css']    = isset( $input['custom_css'] ) ? wp_strip_all_tags( $input['custom_css'] ) : '';

		return $clean;
		}
}
