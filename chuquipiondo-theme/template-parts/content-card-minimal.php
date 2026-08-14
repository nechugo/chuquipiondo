<?php
/**
 * Card preset: Minimal.
 * Clean, text-forward, tiny image.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'post-card post-card--minimal' ); ?> role="article">
	<div class="post-card__body">
		<header class="post-card__header">
			<?php if ( chuquipiondo_is_enabled( 'blog_show_category' ) ) { chuquipiondo_primary_category(); } ?>
			<?php the_title( '<h2 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			<div class="post-card__meta">
				<?php if ( chuquipiondo_is_enabled( 'blog_show_date' ) ) { chuquipiondo_the_date(); } ?>
			</div>
		</header>
		<?php if ( chuquipiondo_is_enabled( 'blog_show_excerpt' ) ) : ?>
			<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
		<?php endif; ?>
	</div>
</article>
