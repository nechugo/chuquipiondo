<?php
/**
 * The single `musica` template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="chuqui-container music-single">
			<?php chuquipiondo_breadcrumbs(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'music-single__article' ); ?> role="article">
				<header class="music-single__header">
					<?php the_title( '<h1 class="music-single__title">', '</h1>' ); ?>
					<?php $artist = get_post_meta( get_the_ID(), '_music_artist', true ); ?>
					<?php if ( $artist ) : ?>
						<span class="music-single__artist"><?php echo esc_html( $artist ); ?></span>
					<?php endif; ?>
				</header>

				<?php chuquipiondo_ad_slot( 'ads_music_single_after' ); ?>

				<div class="music-single__player">
					<?php chuquipiondo_music_player(); ?>
				</div>

				<?php
				// Platforms.
				$platforms = array(
					'Spotify'     => get_post_meta( get_the_ID(), '_music_spotify', true ),
					'Apple Music' => get_post_meta( get_the_ID(), '_music_apple_music', true ),
					'YouTube'     => get_post_meta( get_the_ID(), '_music_youtube', true ),
				);
				$platforms = array_filter( $platforms );
				if ( ! empty( $platforms ) ) :
					?>
					<div class="music-single__platforms">
						<span class="music-single__platforms-label"><?php esc_html_e( 'Escucha en:', 'chuquipiondo' ); ?></span>
						<?php foreach ( $platforms as $name => $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="music-single__platform"><?php echo esc_html( $name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php chuquipiondo_music_download_button(); ?>

				<?php
				$lyrics = get_post_meta( get_the_ID(), '_music_lyrics', true );
				if ( $lyrics ) :
					?>
					<div class="music-single__lyrics entry-content">
						<h2 class="music-single__lyrics-title"><?php esc_html_e( 'Letra', 'chuquipiondo' ); ?></h2>
						<?php echo wp_kses_post( wpautop( $lyrics ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( get_the_content() ) : ?>
					<div class="entry-content music-single__content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php chuquipiondo_social_share(); ?>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
		<?php
	endwhile;
	?>
</div>

<?php
get_footer();
