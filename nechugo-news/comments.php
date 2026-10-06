<?php
/**
 * Comentarios.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="nechugo-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf( esc_html( _n( '%s comentario', '%s comentarios', get_comments_number(), 'nechugo-news' ) ), esc_html( number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Los comentarios estan cerrados.', 'nechugo-news' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
