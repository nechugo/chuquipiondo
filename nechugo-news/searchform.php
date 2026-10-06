<?php
/**
 * Formulario de busqueda.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="nechugo-search-form-el" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="nechugo-search-field"><?php esc_html_e( 'Buscar', 'nechugo-news' ); ?></label>
	<input type="search" id="nechugo-search-field" class="nechugo-search-field" placeholder="<?php esc_attr_e( 'Buscar...', 'nechugo-news' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="nechugo-search-submit" aria-label="<?php esc_attr_e( 'Buscar', 'nechugo-news' ); ?>">&rarr;</button>
</form>
