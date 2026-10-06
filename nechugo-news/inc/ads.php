<?php
/**
 * Motor de anuncios Google AdSense: master switch, auto-ads,
 * slots manuales y widget de caja.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Si los anuncios estan activos globalmente.
 *
 * @return bool
 */
function nechugo_ads_active() {
	if ( ! nechugo_is_enabled( 'ads_master_switch' ) ) {
		return false;
	}
	return '' !== trim( (string) nechugo_get_option( 'adsense_publisher_id' ) ) || nechugo_has_any_ad_code();
}

/**
 * Si hay al menos un bloque de codigo configurado.
 *
 * @return bool
 */
function nechugo_has_any_ad_code() {
	foreach ( array( 'ad_header_code', 'ad_in_article_code', 'ad_after_post_code', 'ad_sidebar_code' ) as $key ) {
		if ( '' !== trim( (string) nechugo_get_option( $key ) ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Script de Auto Ads (modos auto / both).
 */
function nechugo_adsense_auto_script() {
	if ( ! nechugo_ads_active() ) {
		return;
	}
	$mode = nechugo_get_option( 'ads_mode' );
	if ( 'auto' !== $mode && 'both' !== $mode ) {
		return;
	}
	$pub = nechugo_get_option( 'adsense_publisher_id' );
	if ( '' === $pub ) {
		return;
	}
	?>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo esc_attr( $pub ); ?>" crossorigin="anonymous"></script>
	<?php
}
add_action( 'wp_head', 'nechugo_adsense_auto_script', 5 );

/**
 * Renderiza un slot de anuncio si esta configurado.
 *
 * @param string $slot Clave de la opcion (ad_header_code, etc.).
 */
function nechugo_ad_slot( $slot ) {
	if ( ! nechugo_ads_active() ) {
		return;
	}
	$code = nechugo_get_option( $slot );
	if ( '' === trim( (string) $code ) ) {
		return;
	}
	echo '<div class="nechugo-ad nechugo-ad--' . esc_attr( str_replace( '_code', '', $slot ) ) . '">';
	echo $code; // phpcs:ignore -- saneado en el customizer; solo admins con unfiltered_html.
	echo '</div>';
}

/**
 * Inserta el anuncio in-article tras el primer parrafo del contenido.
 *
 * @param string $content Contenido.
 * @return string
 */
function nechugo_in_article_ad( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$code = nechugo_get_option( 'ad_in_article_code' );
	if ( '' === trim( (string) $code ) || ! nechugo_ads_active() ) {
		return $content;
	}
	$parts = explode( '</p>', $content, 2 );
	if ( count( $parts ) < 2 ) {
		return $content;
	}
	$ad    = '<div class="nechugo-ad nechugo-ad--in-article">' . $code . '</div>';
	return $parts[0] . '</p>' . $ad . $parts[1];
}
add_filter( 'the_content', 'nechugo_in_article_ad', 20 );
