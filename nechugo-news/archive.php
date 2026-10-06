<?php
/**
 * Archivos de categorias, etiquetas, autores y fechas.
 *
 * @package nechugo_News
 */

get_header();
$sidebar = nechugo_get_option( 'sidebar_position' );
$layout   = nechugo_get_option( 'blog_layout' );
?>

<div class="nechugo-container nechugo-content-area">
	<?php nechugo_breadcrumbs(); ?>
	<header class="nechugo-archive-header">
		<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</header>
	<div class="nechugo-layout nechugo-layout--sidebar-<?php echo esc_attr( $sidebar ); ?>">
		<main id="primary" class="nechugo-main">
			<?php if ( have_posts() ) : ?>
				<div class="nechugo-posts nechugo-posts--<?php echo esc_attr( $layout ); ?>">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/cards/card', 'post' );
					endwhile;
					?>
				</div>
				<div class="nechugo-pagination">
					<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
				</div>
			<?php else : ?>
				<div class="nechugo-no-results">
					<h2><?php esc_html_e( 'No hay contenido en esta seccion', 'nechugo-news' ); ?></h2>
				</div>
			<?php endif; ?>
		</main>
		<?php if ( 'none' !== $sidebar && is_active_sidebar( 'sidebar-1' ) ) : ?>
			<aside class="nechugo-sidebar">
				<?php nechugo_ad_slot( 'ad_sidebar_code' ); ?>
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
