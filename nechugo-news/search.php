<?php
/**
 * Resultados de busqueda.
 *
 * @package nechugo_News
 */

get_header();
?>

<div class="nechugo-container nechugo-content-area">
	<header class="nechugo-archive-header">
		<h1 class="archive-title">
			<?php
			printf( esc_html__( 'Resultados de busqueda: %s', 'nechugo-news' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
	</header>
	<div class="<?php echo esc_attr( nechugo_layout_classes() ); ?>">
		<main id="primary" class="nechugo-main">
			<?php if ( have_posts() ) : ?>
				<div class="<?php echo esc_attr( nechugo_posts_classes() ); ?>">
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
					<h2><?php esc_html_e( 'Sin resultados', 'nechugo-news' ); ?></h2>
					<p><?php esc_html_e( 'Intenta con otras palabras.', 'nechugo-news' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</main>
		<?php if ( nechugo_show_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
			<aside class="nechugo-sidebar">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
