<?php
/**
 * Template tags used across templates.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the site logo (custom-logo) with fallback to site name.
 */
function chuquipiondo_site_logo( $args = array() ) {
	$defaults = array(
		'class' => 'site-logo',
	);
	$args = wp_parse_args( $args, $defaults );

	if ( has_custom_logo() ) {
		echo '<div class="' . esc_attr( $args['class'] ) . '">' . get_custom_logo() . '</div>';
	} else {
		echo '<div class="' . esc_attr( $args['class'] ) . ' site-logo--text">';
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
		bloginfo( 'name' );
		echo '</a></div>';
	}
}

/**
 * Display the post thumbnail with responsive attributes.
 *
 * @param string $size Image size.
 */
function chuquipiondo_post_thumbnail( $size = 'chuquipiondo-card' ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}
	$attrs = array(
		'loading' => 'lazy',
	);
	// First image of a grid: eager for LCP.
	global $wp_query;
	if ( in_the_loop() && 0 === ( $wp_query->current_post % 5 ) && ! is_single() ) {
		$attrs = array(
			'loading'        => 'eager',
			'fetchpriority'  => 'high',
		);
	}
	?>
	<figure class="post-thumbnail post-thumbnail--<?php echo esc_attr( $size ); ?>">
		<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
			<?php the_post_thumbnail( $size, $attrs ); ?>
		</a>
	</figure>
	<?php
}

/**
 * Output the primary category of the current post.
 */
function chuquipiondo_primary_category() {
	$cats = get_the_category();
	if ( empty( $cats ) ) {
		return;
	}
	$primary = $cats[0];
	$first = array_shift( $cats );
	echo '<a href="' . esc_url( get_category_link( $first->term_id ) ) . '" class="post-card__category">' . esc_html( $first->name ) . '</a>';
}

/**
 * Custom excerpt length based on theme options.
 *
 * @param int $length Default length.
 * @return int
 */
function chuquipiondo_excerpt_length( $length ) {
	$length = (int) chuquipiondo_get_option( 'blog_excerpt_length' );
	return $length > 0 ? $length : 24;
}
add_filter( 'excerpt_length', 'chuquipiondo_excerpt_length', 999 );

/**
 * Custom excerpt more string.
 *
 * @param string $more Default.
 * @return string
 */
function chuquipiondo_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'chuquipiondo_excerpt_more' );

/**
 * Estimate reading time in minutes.
 *
 * @param int $post_id Optional post ID.
 * @return int
 */
function chuquipiondo_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 220 ) );
	return $minutes;
}

/**
 * Display the reading time markup.
 */
function chuquipiondo_the_reading_time() {
	$minutes = chuquipiondo_reading_time();
	printf(
		/* translators: %d: reading minutes */
		esc_html__( '%d min de lectura', 'chuquipiondo' ),
		(int) $minutes
	);
}

/**
 * Display the author byline.
 */
function chuquipiondo_the_author() {
	echo '<span class="byline">';
	echo esc_html__( 'Por', 'chuquipiondo' ) . ' ';
	echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" class="author">' . esc_html( get_the_author() ) . '</a>';
	echo '</span>';
}

/**
 * Display the post date.
 */
function chuquipiondo_the_date() {
	echo '<time class="post-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
}

/**
 * Display a list of social profile links based on Customizer.
 */
function chuquipiondo_social_profiles_links() {
	$profiles = array(
		'facebook'  => array( chuquipiondo_get_option( 'social_facebook' ), __( 'Facebook', 'chuquipiondo' ) ),
		'x'         => array( chuquipiondo_get_option( 'social_x' ), __( 'X', 'chuquipiondo' ) ),
		'youtube'   => array( chuquipiondo_get_option( 'social_youtube' ), __( 'YouTube', 'chuquipiondo' ) ),
		'instagram' => array( chuquipiondo_get_option( 'social_instagram' ), __( 'Instagram', 'chuquipiondo' ) ),
		'linkedin'  => array( chuquipiondo_get_option( 'social_linkedin' ), __( 'LinkedIn', 'chuquipiondo' ) ),
		'telegram'  => array( chuquipiondo_get_option( 'social_telegram' ), __( 'Telegram', 'chuquipiondo' ) ),
		'tiktok'     => array( chuquipiondo_get_option( 'social_tiktok' ), __( 'TikTok', 'chuquipiondo' ) ),
	);

	echo '<ul class="social-profiles">';
	foreach ( $profiles as $slug => $data ) {
		list( $url, $label ) = $data;
		if ( empty( $url ) ) {
			continue;
		}
		echo '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="social-profile social-profile--' . esc_attr( $slug ) . '" aria-label="' . esc_attr( $label ) . '">';
		chuquipiondo_social_icon( $slug );
		echo '</a></li>';
	}
	echo '</ul>';
}

/**
 * Output an inline SVG icon for a social network.
 *
 * @param string $slug Network slug.
 */
function chuquipiondo_social_icon( $slug ) {
	$icons = array(
		'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/></svg>',
		'x'        => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.66l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.03l4.713 6.231 5.5-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>',
		'youtube'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M23.5 6.2a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.51A3.02 3.02 0 0 0 .5 6.2C0 8.08 0 12 0 12s0 3.92.5 5.8a3.02 3.02 0 0 0 2.12 2.14c1.88.51 9.38.51 9.38.51s7.5 0 9.38-.51a3.02 3.02 0 0 0 2.12-2.14c.5-1.88.5-5.8.5-5.8s0-3.92-.5-5.8ZM9.55 15.57V8.43L15.82 12l-6.27 3.57Z"/></svg>',
		'instagram'=> '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.43.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.43.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.71 3.71 0 0 1-1.38-.9 3.71 3.71 0 0 1-.9-1.38c-.16-.43-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.43-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07Zm0 2.16c-3.15 0-3.52.01-4.76.07-1.15.05-1.77.24-2.19.4-.55.22-.94.47-1.35.88-.41.41-.66.8-.88 1.35-.16.42-.35 1.04-.4 2.19-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.05 1.15.24 1.77.4 2.19.22.55.47.94.88 1.35.41.41.8.66 1.35.88.42.16 1.04.35 2.19.4 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c1.15-.05 1.77-.24 2.19-.4.55-.22.94-.47 1.35-.88.41-.41.66-.8.88-1.35.16-.42.35-1.04.4-2.19.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.05-1.15-.24-1.77-.4-2.19a3.63 3.63 0 0 0-.88-1.35 3.63 3.63 0 0 0-1.35-.88c-.42-.16-1.04-.35-2.19-.4-1.24-.06-1.61-.07-4.76-.07Zm0 3.68a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm0 6.6a2.6 2.6 0 1 0 0-5.2 2.6 2.6 0 0 0 0 5.2Zm5.1-6.78a.94.94 0 1 1-1.88 0 .94.94 0 0 1 1.88 0Z"/></svg>',
		'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.36V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0Z"/></svg>',
		'telegram' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="m11.94 14.95-1.97 1.94c-.24.23-.43.5-.43.83l-.01 2.66c0 .25.31.37.49.19l2.16-2.13 5.21-4.36c.35-.29.35-.83 0-1.12l-5.21-4.36c-.18-.15-.46-.03-.46.21v4.34c0 .09.06.17.14.21l2.34 1.06c.16.07.2.28.06.4l-2.04 1.27c-.16.1-.37.08-.51-.04Zm-1.45-3.69-4.2-1.61c-.24-.09-.24-.43 0-.52l13.43-5.18c.21-.08.42.12.35.33l-3.42 12.99c-.07.26-.4.32-.55.1l-3.94-3.89-2.42 2.21c-.16.15-.42.04-.42-.18v-3.61c0-.13.08-.25.2-.29Z"/></svg>',
		'tiktok'    => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.32v13.16a3 3 0 1 1-2.12-2.86V9.06a6.19 6.19 0 1 0 5.44 6.15V8.66a8 8 0 0 0 4.69 1.5V6.69h-.92Z"/></svg>',
		'whatsapp'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M.06 24l1.68-6.14a11.86 11.86 0 0 1-1.6-5.95C.14 5.33 5.47 0 12.06 0a11.82 11.82 0 0 1 8.41 3.49 11.82 11.82 0 0 1 3.48 8.42c0 6.59-5.33 11.95-11.95 11.95a11.94 11.94 0 0 1-5.72-1.46L.06 24Zm6.6-3.8c1.68.99 3.28 1.58 5.39 1.58 5.47 0 9.93-4.45 9.93-9.92a9.86 9.86 0 0 0-2.91-7.02 9.86 9.86 0 0 0-7.01-2.92c-5.48 0-9.93 4.46-9.93 9.93 0 2.23.65 3.89 1.74 5.63l-1 3.65 3.79-.93Zm11.39-5.46c-.07-.12-.27-.2-.57-.35-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.39-1.48-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.09 3.2 5.07 4.48.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41Z"/></svg>',
		'email'     => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/></svg>',
		'copy'       => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 1H4a2 2 0 0 0-2 2v14h2V3h12V1Zm3 4H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm0 16H8V7h11v14Z"/></svg>',
	);
	if ( isset( $icons[ $slug ] ) ) {
		echo $icons[ $slug ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
	}
}

/**
 * Output the pagination markup.
 */
function chuquipiondo_the_posts_pagination() {
	the_posts_pagination( array(
		'mid_size'           => 1,
		'prev_text'          => '&larr; ' . esc_html__( 'Anterior', 'chuquipiondo' ),
		'next_text'          => esc_html__( 'Siguiente', 'chuquipiondo' ) . ' &rarr;',
		'screen_reader_text' => esc_html__( 'Navegacion de articulos', 'chuquipiondo' ),
	) );
}

/**
 * Render a post card with the configured style.
 *
 * @param string $style Card style preset.
 */
function chuquipiondo_post_card( $style = '' ) {
	$style = $style ? $style : chuquipiondo_get_option( 'blog_card_style' );
	$style = sanitize_html_class( $style );

	/**
	 * Filters the card style before rendering.
	 */
	$style = apply_filters( 'chuquipiondo_card_style', $style );

	chuquipiondo_get_template_part( 'template-parts/content-card', $style, array(
		'style' => $style,
	) );
}
