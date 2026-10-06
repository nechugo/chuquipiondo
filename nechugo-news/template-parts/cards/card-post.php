<?php
/**
 * Card de entrada para listados (grid / list / masonry).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'nechugo-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="nechugo-card__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'nechugo-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="nechugo-card__body">
		<div class="nechugo-card__cats"><?php nechugo_first_category(); ?></div>
		<h2 class="nechugo-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<?php nechugo_post_meta(); ?>
		<div class="nechugo-card__excerpt"><?php the_excerpt(); ?></div>
		<a class="nechugo-card__more" href="<?php the_permalink(); ?>">
			<?php echo esc_html( nechugo_get_option( 'read_more_text' ) ); ?>
		</a>
	</div>
</article>
