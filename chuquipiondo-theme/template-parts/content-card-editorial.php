<?php
/**
 * Card preset: Editorial.
 * Image top, category badge, title, excerpt, author + date.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'post-card post-card--editorial' ); ?> role="article">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-card__media">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'chuquipiondo-card', array( 'loading' => 'lazy' ) ); ?>
			</a>
		</div>
	<?php endif; ?>
	<div class="post-card__body">
		<header class="post-card__header">
			<?php if ( chuquipiondo_is_enabled( 'blog_show_category' ) ) { chuquipiondo_primary_category(); } ?>
			<?php the_title( '<h2 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<?php if ( chuquipiondo_is_enabled( 'blog_show_excerpt' ) ) : ?>
			<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
		<?php endif; ?>
		<footer class="post-card__meta">
			<?php if ( chuquipiondo_is_enabled( 'blog_show_author' ) ) { chuquipiondo_the_author(); } ?>
			<?php if ( chuquipiondo_is_enabled( 'blog_show_date' ) ) { chuquipiondo_the_date(); } ?>
		</footer>
	</div>
</article>
