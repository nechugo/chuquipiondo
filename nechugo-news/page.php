<?php
/**
 * Pagina estatica (incluye paginas Elementor).
 *
 * @package nechugo_News
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( nechugo_is_elementor_page() ) {
		?>
		<div class="nechugo-elementor-canvas">
			<?php the_content(); ?>
		</div>
		<?php
	} else {
		$sidebar = nechugo_get_option( 'sidebar_position' );
		?>
		<div class="nechugo-container nechugo-content-area">
			<?php nechugo_breadcrumbs(); ?>
			<div class="<?php echo esc_attr( nechugo_layout_classes() ); ?>">
				<main id="primary" class="nechugo-main nechugo-main--page">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'nechugo-single' ); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header>
						<div class="entry-content">
							<?php
							the_content();
							wp_link_pages();
							?>
						</div>
					</article>
					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</main>
				<?php if ( nechugo_show_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
					<aside class="nechugo-sidebar"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
endwhile;

get_footer();
