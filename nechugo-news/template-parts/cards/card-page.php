<?php
/**
 * Card generico para otros post types.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'nechugo-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="nechugo-card__thumb" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'nechugo-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="nechugo-card__body">
		<h2 class="nechugo-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="nechugo-card__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
