<?php
/**
 * Custom search form.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="chuqui-search-<?php echo esc_attr( uniqid() ); ?>" class="screen-reader-text">
		<?php esc_html_e( 'Buscar:', 'chuquipiondo' ); ?>
	</label>
	<div class="search-form__inner">
		<input type="search" id="chuqui-search-<?php echo esc_attr( uniqid() ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Buscar...', 'chuquipiondo' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
		<button type="submit" class="search-form__submit btn" aria-label="<?php esc_attr_e( 'Buscar', 'chuquipiondo' ); ?>">
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.32l5.39 5.39 1.42-1.42-5.39-5.39A8 8 0 0 0 10 2Zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12Z"/></svg>
		</button>
	</div>
</form>
