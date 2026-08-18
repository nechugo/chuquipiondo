<?php
/**
 * Module 2: Blog / Revista Pro.
 *
 * Provides:
 *  - Magazine layout: first post large + grid of recent posts.
 *  - Timeline layout: posts on a vertical timeline.
 *  - Grid Pro: configurable columns (desktop/tablet/mobile) + lazy.
 *  - Related posts pro: query by shared category/tag, configurable count.
 *  - AJAX filters by category + load more button.
 *
 * Activated by `companion_blog_pro_enable`. Reuses theme helpers
 * (chuquipiondo_post_card) and post-card template-parts when present.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether blog pro is active.
 *
 * @return bool
 */
function chuquipiondo_companion_blog_pro_active() {
	return chuquipiondo_companion_is_enabled( 'companion_blog_pro_enable' );
}

/**
 * Inject filters + load more around the main archive loop when enabled.
 *
 * Uses standard WordPress loop hooks (loop_start / loop_end) so we do
 * not depend on theme-specific actions that may not exist.
 */
function chuquipiondo_companion_override_blog() {
	if ( ! chuquipiondo_companion_blog_pro_active() || ! chuquipiondo_companion_is_theme_active() ) {
		return;
	}
	if ( ! ( is_home() || is_archive() || is_search() ) ) {
		return;
	}
	add_action( 'loop_start', 'chuquipiondo_companion_maybe_render_blog_filters' );
	add_action( 'loop_end', 'chuquipiondo_companion_maybe_render_load_more' );
}
add_action( 'template_redirect', 'chuquipiondo_companion_override_blog' );

/**
 * Render filters only once, at the start of the first post loop.
 *
 * @param WP_Query $query Current query.
 */
function chuquipiondo_companion_maybe_render_blog_filters( $query ) {
	static $done = false;
	if ( $done || ! $query->is_main_query() ) {
		return;
	}
	$done = true;
	chuquipiondo_companion_render_blog_filters();
}

/**
 * Render load more only once, at the end of the first post loop.
 *
 * @param WP_Query $query Current query.
 */
function chuquipiondo_companion_maybe_render_load_more( $query ) {
	static $done = false;
	if ( $done || ! $query->is_main_query() ) {
		return;
	}
	$done = true;
	chuquipiondo_companion_render_load_more();
}

/**
 * Get the pro blog style.
 *
 * @return string
 */
function chuquipiondo_companion_blog_pro_style() {
	return sanitize_html_class( chuquipiondo_companion_get_option( 'companion_blog_pro_style' ), 'magazine' );
}

/**
 * Render the magazine layout: 1 large featured post + grid of recent posts.
 *
 * Used via shortcode [chuquipiondo_blog_pro style="magazine" count="7" columns="3"]
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function chuquipiondo_companion_blog_pro_shortcode( $atts ) {
	if ( ! chuquipiondo_companion_blog_pro_active() ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'style'   => chuquipiondo_companion_blog_pro_style(),
		'count'   => '7',
		'columns' => '',
		'category' => '',
	), $atts, 'chuquipiondo_blog_pro' );

	$style   = sanitize_html_class( $atts['style'] );
	$count   = max( 1, (int) $atts['count'] );
	$columns = '' !== $atts['columns'] ? max( 1, min( 4, (int) $atts['columns'] ) ) : (int) chuquipiondo_companion_get_option( 'companion_blog_pro_columns' );

	$q_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	);
	if ( $atts['category'] ) {
		$q_args['category_name'] = $atts['category'];
	}
	$q = new WP_Query( $q_args );
	if ( ! $q->have_posts() ) {
		wp_reset_postdata();
		return '<p>' . esc_html__( 'No hay articulos para mostrar.', 'chuquipiondo-companion' ) . '</p>';
	}

	$use_lazy = chuquipiondo_companion_is_enabled( 'companion_blog_pro_lazy' );

	ob_start();
	echo '<div class="chuqui-blog-pro chuqui-blog-pro--' . esc_attr( $style ) . '" data-style="' . esc_attr( $style ) . '" data-columns="' . esc_attr( $columns ) . '">';

	switch ( $style ) {
		case 'magazine':
			// First post large.
			$q->the_post();
			echo '<div class="blog-pro__featured">';
			if ( function_exists( 'chuquipiondo_post_card' ) ) {
				chuquipiondo_post_card( 'magazine' );
			} else {
				get_template_part( 'template-parts/content-card-magazine' );
			}
			echo '</div>';

			// Rest in grid.
			echo '<div class="blog-pro__grid post-grid" style="grid-template-columns:repeat(' . esc_attr( $columns ) . ',minmax(0,1fr))">';
			while ( $q->have_posts() ) {
				$q->the_post();
				if ( function_exists( 'chuquipiondo_post_card' ) ) {
					chuquipiondo_post_card( 'editorial' );
				} else {
					get_template_part( 'template-parts/content-card-editorial' );
				}
			}
			echo '</div>';
			break;

		case 'timeline':
			echo '<div class="blog-pro__timeline">';
			$idx = 0;
			while ( $q->have_posts() ) {
				$q->the_post();
				$idx++;
				echo '<div class="timeline-item timeline-item--' . ( $idx % 2 ? 'left' : 'right' ) . '">';
				echo '<div class="timeline-item__dot"></div>';
				echo '<div class="timeline-item__card">';
				if ( function_exists( 'chuquipiondo_post_card' ) ) {
					chuquipiondo_post_card( 'minimal' );
				} else {
					get_template_part( 'template-parts/content-card-minimal' );
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			break;

		case 'grid-pro':
		case 'list-featured':
		default:
			echo '<div class="blog-pro__grid post-grid" style="grid-template-columns:repeat(' . esc_attr( $columns ) . ',minmax(0,1fr))">';
			while ( $q->have_posts() ) {
				$q->the_post();
				$card_style = 'list-featured' === $style ? 'elegant' : 'editorial';
				if ( function_exists( 'chuquipiondo_post_card' ) ) {
					chuquipiondo_post_card( $card_style );
				} else {
					get_template_part( 'template-parts/content-card-' . $card_style );
				}
			}
			echo '</div>';
			break;
	}

	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_blog_pro', 'chuquipiondo_companion_blog_pro_shortcode' );

/**
 * Render category filters (AJAX-powered) above the blog grid.
 */
function chuquipiondo_companion_render_blog_filters() {
	if ( ! chuquipiondo_companion_is_enabled( 'companion_blog_pro_filters' ) ) {
		return;
	}
	$cats = get_categories( array(
		'hide_empty' => true,
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );
	if ( empty( $cats ) ) {
		return;
	}
	$current = is_category() ? get_queried_object_id() : 0;
	echo '<div class="chuqui-blog-pro-filters" data-current="' . esc_attr( $current ) . '">';
	echo '<button type="button" class="blog-pro-filter is-active" data-cat="0">' . esc_html__( 'Todos', 'chuquipiondo-companion' ) . '</button>';
	foreach ( $cats as $cat ) {
		$active = ( $current === (int) $cat->term_id ) ? ' is-active' : '';
		echo '<button type="button" class="blog-pro-filter' . esc_attr( $active ) . '" data-cat="' . esc_attr( $cat->term_id ) . '">' . esc_html( $cat->name ) . '</button>';
	}
	echo '</div>';
}

/**
 * Render the load-more button after the blog grid.
 */
function chuquipiondo_companion_render_load_more() {
	if ( ! chuquipiondo_companion_is_enabled( 'companion_blog_pro_load_more' ) ) {
		return;
	}
	global $wp_query;
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	if ( $paged >= $wp_query->max_num_pages ) {
		return;
	}
	echo '<div class="chuqui-blog-pro-loadmore">';
	printf(
		'<button type="button" class="blog-pro-loadmore__btn" data-page="%d" data-max="%d"><span class="blog-pro-loadmore__label">%s</span></button>',
		esc_attr( $paged ),
		esc_attr( $wp_query->max_num_pages ),
		esc_html__( 'Cargar mas', 'chuquipiondo-companion' )
	);
	echo '</div>';
}

/**
 * Related posts pro (query by shared categories + tags).
 *
 * Replaces the theme related posts when the module is enabled.
 *
 * @return void
 */
function chuquipiondo_companion_related_posts_pro() {
	if ( ! chuquipiondo_companion_blog_pro_active() || ! is_singular( 'post' ) ) {
		return;
	}
	$post_id = get_the_ID();
	$cats    = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );
	$tags    = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
	if ( empty( $cats ) && empty( $tags ) ) {
		return;
	}

	$count   = (int) chuquipiondo_companion_get_option( 'companion_blog_pro_related_count', '4' );
	$columns = (int) chuquipiondo_companion_get_option( 'companion_blog_pro_related_columns', '4' );

	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'category__in'        => $cats ? $cats : array(),
		'tag__in'             => $tags ? $tags : array(),
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	) );
	if ( ! $q->have_posts() ) {
		wp_reset_postdata();
		return;
	}
	?>
	<section class="related-posts chuqui-related-pro" aria-label="<?php esc_attr_e( 'Articulos relacionados', 'chuquipiondo-companion' ); ?>">
		<h2 class="related-posts__title"><?php esc_html_e( 'Relacionados', 'chuquipiondo-companion' ); ?></h2>
		<div class="post-grid related-posts__grid" style="grid-template-columns:repeat(<?php echo esc_attr( $columns ); ?>,minmax(0,1fr))">
			<?php
			while ( $q->have_posts() ) {
				$q->the_post();
				if ( function_exists( 'chuquipiondo_post_card' ) ) {
					chuquipiondo_post_card( 'editorial' );
				} else {
					get_template_part( 'template-parts/content-card-editorial' );
				}
			}
			?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
/**
 * Force-disable the theme's related posts when the pro module is active,
 * so we don't render two related sections on the same single post.
 *
 * Runs on the theme option read so the toggle is honored only when the
 * companion module is OFF.
 *
 * @param bool   $value   Current value.
 * @param string $key     Option key being read.
 * @return bool
 */
function chuquipiondo_companion_disable_theme_related( $value ) {
	if ( chuquipiondo_companion_blog_pro_active() ) {
		return false;
	}
	return $value;
}

/**
 * Render the pro related posts on the theme's existing
 * chuquipiondo_after_post_end_extension hook (fires after the article
 * content, before the theme's own related section).
 */
function chuquipiondo_companion_render_related_on_hook() {
	if ( ! chuquipiondo_companion_blog_pro_active() ) {
		return;
	}
	chuquipiondo_companion_related_posts_pro();
}

// Only wire up the override + render if the theme hook exists; otherwise
// fall back gracefully (the pro related simply won't render).
// Force-disable the theme related toggle via the theme_mod filter (WP core).
add_filter( 'theme_mod_single_show_related', 'chuquipiondo_companion_disable_theme_related' );
// Render the pro related posts on the theme's existing hook.
add_action( 'chuquipiondo_after_post_end_extension', 'chuquipiondo_companion_render_related_on_hook', 5 );

/**
 * AJAX handler: load more posts by page + category filter.
 */
function chuquipiondo_companion_blog_pro_ajax() {
	check_ajax_referer( 'chuquipiondo_blog_pro', 'nonce' );

	$page    = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
	$cat     = isset( $_POST['cat'] ) ? (int) $_POST['cat'] : 0;
	$style   = isset( $_POST['style'] ) ? sanitize_html_class( wp_unslash( $_POST['style'] ) ) : 'editorial';
	$columns = isset( $_POST['columns'] ) ? max( 1, min( 4, (int) $_POST['columns'] ) ) : (int) chuquipiondo_companion_get_option( 'companion_blog_pro_columns' );

	$q_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => (int) get_option( 'posts_per_page', 9 ),
		'paged'               => $page,
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => false,
	);
	if ( $cat ) {
		$q_args['cat'] = $cat;
	}
	$q = new WP_Query( $q_args );

	if ( ! $q->have_posts() ) {
		wp_send_json( array(
			'html'       => '',
			'has_more'   => false,
			'next_page'  => $page,
		) );
	}

	ob_start();
	echo '<div class="blog-pro__grid post-grid" style="grid-template-columns:repeat(' . esc_attr( $columns ) . ',minmax(0,1fr))">';
	while ( $q->have_posts() ) {
		$q->the_post();
		if ( function_exists( 'chuquipiondo_post_card' ) ) {
			chuquipiondo_post_card( $style );
		} else {
			get_template_part( 'template-parts/content-card-' . $style );
		}
	}
	echo '</div>';
	$html = ob_get_clean();

	wp_reset_postdata();
	wp_send_json( array(
		'html'      => $html,
		'has_more'  => ( $page < $q->max_num_pages ),
		'next_page' => $page + 1,
	) );
}
add_action( 'wp_ajax_chuquipiondo_blog_pro', 'chuquipiondo_companion_blog_pro_ajax' );
add_action( 'wp_ajax_nopriv_chuquipiondo_blog_pro', 'chuquipiondo_companion_blog_pro_ajax' );
