<?php
/**
 * Card preset: Elegant.
 * Large featured image with overlay text, refined spacing.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'post-card post-card--elegant' ); ?> role="article">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-card__media">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'chuquipiondo-card-large', array( 'loading' => 'lazy' ) ); ?>
			</a>
			<div class="post-card__overlay">
				<header class="post-card__header">
					<?php if ( chuquipiondo_is_enabled( 'blog_show_category' ) ) { chuquipiondo_primary_category(); } ?>
					<?php the_title( '<h2 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
					<div class="post-card__meta">
						<?php if ( chuquipiondo_is_enabled( 'blog_show_author' ) ) { chuquipiondo_the_author(); } ?>
						<?php if ( chuquipiondo_is_enabled( 'blog_show_date' ) ) { chuquipiondo_the_date(); } ?>
					</div>
				</header>
			</div>
		</div>
	<?php else : ?>
		<div class="post-card__body">
			<header class="post-card__header">
				<?php if ( chuquipiondo_is_enabled( 'blog_show_category' ) ) { chuquipiondo_primary_category(); } ?>
				<?php the_title( '<h2 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			</header>
			<?php if ( chuquipiondo_is_enabled( 'blog_show_excerpt' ) ) : ?>
				<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</article>
