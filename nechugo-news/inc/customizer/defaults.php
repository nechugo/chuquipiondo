<?php
/**
 * Wrapper de defaults para el customizer.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defaults del personalizador (delega en nechugo_defaults()).
 *
 * @return array
 */
function nechugo_customizer_defaults() {
	return nechugo_defaults();
}
