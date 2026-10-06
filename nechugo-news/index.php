<?php
/**
 * Plantilla principal (blog y portada de entradas).
 *
 * @package nechugo_News
 */

get_header();

$sidebar = nechugo_get_option( 'sidebar_position' );
$layout   = nechugo_get_option( 'blog_layout' );
?>

<div class="nechugo-container nechugo-content-area">
	<?php nechugo_breadcrumbs(); ?>

	<?php if ( is_home() && nechugo_is_enabled( 'home_slider_enable' ) && ! is_paged() ) : ?>
		<?php get_template_part( 'template-parts/hero-slider' ); ?>
	<?php endif; ?>

	<div class="nechugo-layout nechugo-layout--sidebar-<?php echo esc_attr( $sidebar ); ?>">
		<main id="primary" class="nechugo-main">
			<?php if ( have_posts() ) : ?>
				<div class="nechugo-posts nechugo-posts--<?php echo esc_attr( $layout ); ?>">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/cards/card', get_post_type() === 'post' ? 'post' : get_post_type() );
					endwhile;
					?>
				</div>
				<div class="nechugo-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="nechugo-no-results">
					<h2><?php esc_html_e( 'No hay contenido', 'nechugo-news' ); ?></h2>
					<p><?php esc_html_e( 'Publica tu primera entrada para verla aqui.', 'nechugo-news' ); ?></p>
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
