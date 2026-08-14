<?php
/**
 * The comments template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			/* translators: %s: comment count */
			printf( esc_html( _n( '%s comentario', '%s comentarios', $count, 'chuquipiondo' ) ), esc_html( number_format_i18n( $count ) ) );
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 56,
			) );
			?>
		</ol>

		<?php the_comments_pagination( array(
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
		) ); ?>

	<?php endif; ?>

	<?php
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Los comentarios estan cerrados.', 'chuquipiondo' ); ?></p>
		<?php
	endif;

	comment_form( array(
		'title_reply'   => __( 'Deja un comentario', 'chuquipiondo' ),
		'class_submit'  => 'btn submit',
		'label_submit'  => __( 'Enviar comentario', 'chuquipiondo' ),
	) );
	?>
</div>
