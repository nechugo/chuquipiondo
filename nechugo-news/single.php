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

					<?php if ( nechugo_is_enabled( 'show_post_tags' ) && has_tag() ) : ?>
						<div class="nechugo-tags">
							<?php the_tags( '<span class="tags-label">' . esc_html__( 'Etiquetas:', 'nechugo-news' ) . '</span> ', ' ' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( nechugo_is_enabled( 'show_author_box' ) ) : ?>
						<div class="nechugo-author-box">
							<div class="author-box__avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 64 ); ?></div>
							<div class="author-box__info">
								<h3 class="author-box__name"><?php the_author(); ?></h3>
								<p class="author-box__bio"><?php the_author_meta( 'description' ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( nechugo_is_enabled( 'show_post_nav' ) ) : ?>
						<nav class="nechugo-post-nav" aria-label="<?php esc_attr_e( 'Navegacion de entradas', 'nechugo-news' ); ?>">
							<?php
							$prev = get_previous_post();
							$next = get_next_post();
							if ( $prev ) :
								?>
								<a class="post-nav__link post-nav__prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
									<span class="post-nav__label">&laquo; <?php esc_html_e( 'Anterior', 'nechugo-news' ); ?></span>
									<span class="post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( $next ) : ?>
								<a class="post-nav__link post-nav__next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
									<span class="post-nav__label"><?php esc_html_e( 'Siguiente', 'nechugo-news' ); ?> &raquo;</span>
									<span class="post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
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
