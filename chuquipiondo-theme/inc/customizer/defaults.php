<?php
/**
 * Default options accessor for the Customizer.
 *
 * This is a thin wrapper around chuquipiondo_defaults() so that
 * the Customizer files can resolve defaults without depending on
 * load order.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the full defaults array (delegates to inc/defaults.php).
 *
 * @return array
 */
function chuquipiondo_customizer_defaults() {
	return chuquipiondo_defaults();
}

/**
 * Get a single default value for a key.
 *
 * @param string $key Option key.
 * @return mixed
 */
function chuquipiondo_default( $key ) {
	$defaults = chuquipiondo_customizer_defaults();
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}
