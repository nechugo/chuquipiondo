<?php
/**
 * The page template.
 *
 * Supports a configurable sidebar (right/left/none) with per-page
 * override via meta box, plus a configurable layout (wide/narrow).
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<?php
	$classes = chuquipiondo_get_layout_classes();
	$layout  = chuquipiondo_get_option( 'page_layout' );

	echo '<div class="chuqui-layout ' . esc_attr( $classes['wrap'] ) . ' page-layout page-layout--' . esc_attr( sanitize_html_class( $layout ) ) . '">';

	while ( have_posts() ) :
		the_post();
		?>
		<main id="primary" class="site-main <?php echo esc_attr( $classes['content'] ); ?>" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-page' ); ?> role="article">
				<?php if ( chuquipiondo_is_enabled( 'single_show_breadcrumb' ) ) { chuquipiondo_breadcrumbs(); } ?>

				<header class="entry-header single-article__header">
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

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		</main>
		<?php
	endwhile;

	chuquipiondo_get_sidebar();
	echo '</div>';
	?>
</div>

<?php
get_footer();
