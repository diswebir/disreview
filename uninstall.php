<?php
/**
 * Uninstall DisReview - clean up stored options.
 *
 * @package DisReview
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'disreview_settings' );
