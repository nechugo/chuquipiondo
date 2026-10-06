<?php
/**
 * Entrada individual.
 *
 * @package nechugo_News
 */

get_header();
?>

<div class="nechugo-container nechugo-content-area">
	<?php
	while ( have_posts() ) :
		the_post();
		nechugo_breadcrumbs();
		$sidebar = nechugo_get_option( 'sidebar_position' );
		?>
		<div class="<?php echo esc_attr( nechugo_layout_classes() ); ?>">
			<main id="primary" class="nechugo-main">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'nechugo-single' ); ?>>
					<header class="entry-header">
						<div class="nechugo-card__cats"><?php nechugo_first_category(); ?></div>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php nechugo_post_meta(); ?>
					</header>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="entry-thumb"><?php the_post_thumbnail( 'nechugo-single' ); ?></figure>
					<?php endif; ?>
					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Paginas:', 'nechugo-news' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
					<?php nechugo_share_buttons(); ?>
					<?php nechugo_ad_slot( 'ad_after_post_code' ); ?>
				</article>

				<?php
				if ( nechugo_is_enabled( 'comments_enable' ) && ( comments_open() || get_comments_number() ) ) {
					comments_template();
				}
				?>
			</main>

			<?php if ( nechugo_show_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
				<aside class="nechugo-sidebar">
					<?php nechugo_ad_slot( 'ad_sidebar_code' ); ?>
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</div>

<?php
get_footer();
