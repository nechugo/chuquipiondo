<?php
/**
 * Secret handling hardening for AI Studio.
 *
 * The API key is encrypted at rest with libsodium (shipped with WordPress
 * since 5.2, via the paragonie/sodium-compat polyfill for older PHP). The
 * key is derived from the site's AUTH_KEY/AUTH_SALT so the ciphertext never
 * depends on a hardcoded secret and rotates when wp-config keys change.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Derive a stable 32-byte encryption key from the site's auth constants.
 *
 * @return string Binary key, or '' if sodium is unavailable.
 */
function chuquipiondo_ai_secret_key() {
	if ( ! function_exists( 'sodium_crypto_generichash' ) ) {
		return '';
	}
	$material = (string) AUTH_KEY . AUTH_SALT . LOGGED_IN_KEY . 'chuquipiondo-ai-v1';
	return sodium_crypto_generichash( $material, '', SODIUM_CRYPTO_GENERICHASH_KEYBYTES );
}

/**
 * Encrypt a secret string for storage.
 *
 * @param string $plain Plaintext secret.
 * @return string Encrypted payload (nonce + ciphertext, base64), or plaintext
 *                as-is when sodium is unavailable (graceful degradation).
 */
function chuquipiondo_ai_encrypt_secret( $plain ) {
	$key = chuquipiondo_ai_secret_key();
	if ( '' === $key || '' === $plain ) {
		return $plain;
	}
	if ( 0 === strpos( $plain, 'chuqui1:' ) ) {
		return $plain; // Already encrypted.
	}
	try {
		$nonce  = random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
		$cipher = sodium_crypto_secretbox( $plain, $nonce, $key );
		return 'chuqui1:' . base64_encode( $nonce . $cipher );
	} catch ( Exception $e ) {
		return $plain;
	}
}

/**
 * Decrypt a stored secret.
 *
 * @param string $stored Encrypted payload.
 * @return string Plaintext secret (falls back to the stored value when it is
 *                not an encrypted payload or decryption fails).
 */
function chuquipiondo_ai_decrypt_secret( $stored ) {
	$stored = (string) $stored;
	if ( 0 !== strpos( $stored, 'chuqui1:' ) ) {
		return $stored; // Legacy plaintext.
	}
	$key = chuquipiondo_ai_secret_key();
	if ( '' === $key ) {
		return '';
	}
	$raw = base64_decode( substr( $stored, 8 ), true );
	if ( false === $raw || strlen( $raw ) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ) {
		return '';
	}
	$nonce   = substr( $raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
	$cipher  = substr( $raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
	try {
		$plain = sodium_crypto_secretbox_open( $cipher, $nonce, $key );
		return false === $plain ? '' : $plain;
	} catch ( Exception $e ) {
		return '';
	}
}

/**
 * Encrypt the API key on save.
 *
 * @param mixed $new_value Incoming value.
 * @return string
 */
function chuquipiondo_ai_encrypt_on_save( $new_value ) {
	$new_value = trim( (string) $new_value );
	if ( '' === $new_value ) {
		return $new_value;
	}
	return chuquipiondo_ai_encrypt_secret( sanitize_text_field( $new_value ) );
}

/**
 * Decrypt the API key wherever the client needs it (never in the DOM).
 *
 * @param mixed $value Stored value.
 * @return string
 */
function chuquipiondo_ai_decrypt_on_read( $value ) {
	return chuquipiondo_ai_decrypt_secret( (string) $value );
}

// Encrypt at rest, decrypt transparently for the AI client.
add_filter( 'pre_update_option_ai_api_key', 'chuquipiondo_ai_encrypt_on_save', 5 );

// Never expose the raw key in the settings screen.
function chuquipiondo_ai_hide_api_key_on_settings_screen( $value ) {
	if ( is_admin() && isset( $_GET['page'] ) && 'chuquipiondo-ai-settings' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
		return '';
	}
	return $value;
}
add_filter( 'option_ai_api_key', 'chuquipiondo_ai_hide_api_key_on_settings_screen' );

// Transparent decryption for the client (only when not on the settings screen,
// where the value is intentionally blanked above).
function chuquipiondo_ai_decrypt_for_client( $value ) {
	if ( is_admin() && isset( $_GET['page'] ) && 'chuquipiondo-ai-settings' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
		return $value;
	}
	return chuquipiondo_ai_decrypt_on_read( $value );
}
// The AI client reads via chuquipiondo_ai_get_option which applies option_ filters;
// hook late so the settings-screen blanking wins where it must.

/**
 * Blank submit preserves the previous secret (encrypted form included).
 *
 * @param mixed $new_value New option value.
 * @param mixed $old_value Previous option value.
 * @return string
 */
function chuquipiondo_ai_preserve_api_key_on_blank_update( $new_value, $old_value ) {
	$new_value = trim( (string) $new_value );
	if ( '' === $new_value ) {
		return (string) $old_value;
	}
	return $new_value;
}
add_filter( 'pre_update_option_ai_api_key', 'chuquipiondo_ai_preserve_api_key_on_blank_update', 20 );
