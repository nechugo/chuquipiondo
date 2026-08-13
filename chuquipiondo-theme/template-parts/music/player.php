<?php
/**
 * Music player template-part.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Variables passed via $args.
$post_id   = isset( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$audio     = isset( $args['audio'] ) ? $args['audio'] : '';
$artist    = isset( $args['artist'] ) ? $args['artist'] : '';
$cover     = isset( $args['cover'] ) ? $args['cover'] : '';
$title     = isset( $args['title'] ) ? $args['title'] : get_the_title( $post_id );
$permalink = isset( $args['permalink'] ) ? $args['permalink'] : get_permalink( $post_id );

if ( ! $audio ) {
	return;
}
?>
<div class="music-player" data-music-id="<?php echo esc_attr( $post_id ); ?>" data-music-src="<?php echo esc_url( $audio ); ?>" data-music-title="<?php echo esc_attr( $title ); ?>" data-music-artist="<?php echo esc_attr( $artist ); ?>" data-music-cover="<?php echo esc_url( $cover ); ?>">
	<?php if ( $cover ) : ?>
		<div class="music-player__cover">
			<img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
		</div>
	<?php endif; ?>
	<div class="music-player__body">
		<div class="music-player__head">
			<h3 class="music-player__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
			<?php if ( $artist ) : ?>
				<span class="music-player__artist"><?php echo esc_html( $artist ); ?></span>
			<?php endif; ?>
		</div>
		<div class="music-player__controls">
			<button class="music-player__play" aria-label="<?php esc_attr_e( 'Reproducir', 'chuquipiondo' ); ?>">
				<svg class="icon-play" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>
				<svg class="icon-pause" viewBox="0 0 24 24" aria-hidden="true" hidden><path fill="currentColor" d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
			</button>
			<div class="music-player__progress">
				<span class="music-player__time music-player__time--current">0:00</span>
				<div class="music-player__bar"><div class="music-player__bar-fill"></div></div>
				<span class="music-player__time music-player__time--duration">0:00</span>
			</div>
			<div class="music-player__volume">
				<button class="music-player__mute" aria-label="<?php esc_attr_e( 'Silenciar', 'chuquipiondo' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3a4.5 4.5 0 0 0-2.5-4v8a4.5 4.5 0 0 0 2.5-4z"/></svg>
				</button>
				<input type="range" class="music-player__volume-range" min="0" max="1" step="0.05" value="1" aria-label="<?php esc_attr_e( 'Volumen', 'chuquipiondo' ); ?>">
			</div>
		</div>
	</div>
</div>
