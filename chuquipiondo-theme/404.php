<?php
/**
 * The 404 template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<div class="chuqui-container error-404">
		<div class="error-404__inner">
			<p class="error-404__code">404</p>
			<h1 class="error-404__title"><?php esc_html_e( 'Pagina no encontrada', 'chuquipiondo' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'La pagina que buscas no existe o fue movida.', 'chuquipiondo' ); ?></p>
			<?php get_search_form(); ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn error-404__home"><?php esc_html_e( 'Volver al inicio', 'chuquipiondo' ); ?></a>
		</div>
	</div>
</div>

<?php
get_footer();
