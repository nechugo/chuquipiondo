<?php
/**
 * The page template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<div class="chuqui-container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-page' ); ?> role="article">
				<?php if ( chuquipiondo_is_enabled( 'single_show_breadcrumb' ) ) { chuquipiondo_breadcrumbs(); } ?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title single-article__title">', '</h1>' ); ?>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail single-article__thumbnail"><?php the_post_thumbnail( 'chuquipiondo-featured' ); ?></figure>
				<?php endif; ?>
				<div class="entry-content single-article__content" style="max-width: var(--reading-width); margin-inline: auto;">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Paginas:', 'chuquipiondo' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
