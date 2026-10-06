<?php
/**
 * Pagina 404.
 *
 * @package nechugo_News
 */

get_header();
?>
<div class="nechugo-container nechugo-content-area nechugo-404">
	<h1>404</h1>
	<p><?php esc_html_e( 'La pagina que buscas no existe o fue movida.', 'nechugo-news' ); ?></p>
	<?php get_search_form(); ?>
	<a class="nechugo-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'nechugo-news' ); ?></a>
</div>
<?php
get_footer();
