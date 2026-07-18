<?php
/**
 * Tools: Import/Export settings and top reviews shortcode.
 *
 * @package DisReview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds Import/Export of settings (JSON) and a [disreview_top] shortcode.
 */
class DisReview_Tools {

	/**
	 * Constructor. Wires hooks.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'handle_export' ) );
		add_action( 'admin_init', array( $this, 'handle_import' ) );
		add_shortcode( 'disreview_top', array( $this, 'top_reviews_shortcode' ) );

		// Optional entrance animation on the frontend.
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_animation' ), 20 );
	}

	/**
	 * Export current settings as a downloadable JSON file.
	 */
	public function handle_export() {
		if ( ! isset( $_GET['disreview_export'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'disreview_export_nonce' );

		$settings = get_option( DisReview::OPTION_KEY, array() );
		$data     = wp_json_encode( $settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

		header( 'Content-Type: application/json' );
		header( 'Content-Disposition: attachment; filename="disreview-settings.json"' );
		echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Import settings from an uploaded JSON file.
	 */
	public function handle_import() {
		if ( ! isset( $_POST['disreview_import'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'disreview_import_nonce' );

		if ( empty( $_FILES['disreview_import_file']['tmp_name'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$raw  = file_get_contents( $_FILES['disreview_import_file']['tmp_name'] );
		$decoded = json_decode( $raw, true );

		if ( ! is_array( $decoded ) ) {
			add_settings_error( 'disreview_settings_group', 'dr_import_fail', __( 'فایل نامعتبر است.', 'disreview' ), 'error' );
			return;
		}

		$clean = DisReview_Settings::sanitize( $decoded );
		update_option( DisReview::OPTION_KEY, $clean );
		add_settings_error( 'disreview_settings_group', 'dr_import_ok', __( 'تنظیمات با موفقیت بازیابی شد.', 'disreview' ), 'updated' );

		// Redirect to avoid resubmission and persist the admin notice.
		wp_safe_redirect( admin_url( 'options-general.php?page=disreview&settings-updated=1' ) );
		exit;
	}

	/**
	 * Shortcode: display top-rated reviews.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function top_reviews_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'count' => 5,
				'type' => 'product',
			),
			$atts,
			'disreview_top'
		);

		$count = absint( $atts['count'] );
		$type  = sanitize_key( $atts['type'] );

		$args = array(
			'status' => 'approve',
			'number' => $count,
			'type'   => 'review' === $type ? 'review' : ( DisReview::is_edd_active() && 'download' === $type ? 'edd_review' : 'comment' ),
			'meta_query' => array(
				'relation' => 'OR',
				array( 'key' => 'rating', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ),
				array( 'key' => 'rating', 'compare' => 'NOT EXISTS' ),
			),
			'orderby' => 'comment_karma',
			'order'   => 'DESC',
		);

		$comments = get_comments( $args );

		if ( empty( $comments ) ) {
			return '<p class="dr-top-empty">' . esc_html__( 'نظری یافت نشد.', 'disreview' ) . '</p>';
		}

		$out  = '<div class="dr-top-reviews disreview-active">';
		foreach ( $comments as $c ) {
			$rating = (int) get_comment_meta( $c->comment_ID, 'rating', true );
			$stars  = $rating ? str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ) : '';
			$out .= '<div class="dr-top-item">';
			$out .= '<div class="dr-top-meta"><strong>' . esc_html( $c->comment_author ) . '</strong>';
			if ( $stars ) {
				$out .= ' <span class="dr-top-stars">' . esc_html( $stars ) . '</span>';
			}
			$out .= '</div>';
			$out .= '<p>' . wp_kses_post( wp_trim_words( $c->comment_content, 30 ) ) . '</p>';
			$out .= '</div>';
		}
		$out .= '</div>';

		return $out;
	}

	/**
	 * Enqueue entrance animation script when reviews are present.
	 */
	public function maybe_animation() {
		if ( ! is_singular() ) {
			return;
		}
		wp_enqueue_script(
			'disreview-anim',
			DisReview::plugin_url() . 'assets/js/anim.js',
			array(),
			DisReview::VERSION,
			true
		);
	}
}
