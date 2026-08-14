<?php
/**
 * The `musica` archive template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<div class="chuqui-container">
		<?php chuquipiondo_breadcrumbs(); ?>
		<header class="page-header page-header--music">
			<h1 class="page-title"><?php esc_html_e( 'Musica', 'chuquipiondo' ); ?></h1>
		</header>

		<?php chuquipiondo_ad_slot( 'ads_music_archive_top' ); ?>

		<?php if ( have_posts() ) : ?>
			<div class="music-grid post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'music-card' ); ?> role="article">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="music-card__cover" aria-label="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'chuquipiondo-square', array( 'loading' => 'lazy' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="music-card__body">
							<?php the_title( '<h2 class="music-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
							<?php $artist = get_post_meta( get_the_ID(), '_music_artist', true ); ?>
							<?php if ( $artist ) : ?>
								<span class="music-card__artist"><?php echo esc_html( $artist ); ?></span>
							<?php endif; ?>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php
			chuquipiondo_the_posts_pagination();
		else :
			echo '<p class="no-posts">' . esc_html__( 'No hay canciones para mostrar.', 'chuquipiondo' ) . '</p>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
