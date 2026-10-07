<?php
/**
 * Site reader for CHUQUIPIONDO AI Studio.
 *
 * Lets the AI read the whole site: tracks article views (own counter with
 * support for popular analytics plugins), ranks most-read articles,
 * analyzes existing content (topics, gaps, structure) and builds a
 * data-driven brief used to write unique new articles.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Track a single article view (front-end, single posts only).
 *
 * Uses a lightweight meta counter; skips bots, logged-in editors and
 * the author viewing their own post. Supports external view plugins
 * by preferring their counters when present.
 */
function chuquipiondo_ai_track_view() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	// Skip common bots and prefetch.
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$ua = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		if ( preg_match( '/bot|crawl|spider|preview|slurp|facebookexternalhit|whatsapp/i', $ua ) ) {
			return;
		}
	}
	// Editors and the post author do not count.
	if ( current_user_can( 'edit_posts' ) ) {
		return;
	}
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return;
	}
	$author_id = (int) get_post_field( 'post_author', $post_id );
	if ( get_current_user_id() === $author_id ) {
		return;
	}
	$views = (int) get_post_meta( $post_id, '_chuquipiondo_ai_views', true );
	update_post_meta( $post_id, '_chuquipiondo_ai_views', $views + 1 );
}
add_action( 'wp', 'chuquipiondo_ai_track_view', 20 );

/**
 * Get the view count for a post, preferring popular analytics plugins.
 *
 * @param int $post_id Post id.
 * @return int
 */
function chuquipiondo_ai_get_views( $post_id ) {
	// WP-PostViews.
	$views = (int) get_post_meta( $post_id, 'views', true );
	if ( $views > 0 ) {
		return $views;
	}
	// Post Views Counter.
	if ( function_exists( 'pvc_get_post_views' ) ) {
		$views = (int) pvc_get_post_views( $post_id );
		if ( $views > 0 ) {
			return $views;
		}
	}
	return (int) get_post_meta( $post_id, '_chuquipiondo_ai_views', true );
}

/**
 * Rank the most-read articles.
 *
 * @param int    $count How many.
 * @param string $period 'all' | '30' | '7' (days window approximated by publish date
 *                       for plugin counters; own counter is lifetime).
 * @return array[] Each: id, title, url, views, comments, categories, excerpt.
 */
function chuquipiondo_ai_top_articles( $count = 10, $period = 'all' ) {
	$count = max( 1, min( 50, (int) $count ) );
	$args  = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count * 3, // over-fetch, re-rank by views
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'     => '_chuquipiondo_ai_views',
				'value'   => '0',
				'compare' => '>',
				'type'    => 'NUMERIC',
			),
		),
	);
	if ( 'all' !== $period ) {
		$days = max( 1, (int) $period );
		$args['date_query'] = array( array( 'after' => $days . ' days ago' ) );
	}
	$q = new WP_Query( $args );
	$ranked = array();
	if ( $q->have_posts() ) {
		foreach ( $q->posts as $p ) {
			$ranked[] = array(
				'id'    => $p->ID,
				'title' => $p->post_title,
				'url'   => get_permalink( $p->ID ),
				'views' => chuquipiondo_ai_get_views( $p->ID ),
			);
		}
	}
	if ( empty( $ranked ) ) {
		// No tracked views yet: fall back to comments as popularity signal.
		$q = new WP_Query( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'no_found_rows'  => true,
			'orderby'        => 'comment_count',
		) );
		foreach ( $q->posts as $p ) {
			$ranked[] = array(
				'id'    => $p->ID,
				'title' => $p->post_title,
				'url'   => get_permalink( $p->ID ),
				'views' => chuquipiondo_ai_get_views( $p->ID ),
			);
		}
	}
	usort( $ranked, function ( $a, $b ) {
		return $b['views'] <=> $a['views'];
	} );
	$ranked = array_slice( $ranked, 0, $count );

	// Enrich with categories + excerpt for the AI brief.
	foreach ( $ranked as &$r ) {
		$r['categories'] = wp_get_post_terms( $r['id'], 'category', array( 'fields' => 'names' ) );
		$r['is_wp_error'] = null;
		$r['excerpt'] = wp_trim_words( wp_strip_all_tags( get_the_excerpt( $r['id'] ) ), 25, '...' );
		unset( $r['is_wp_error'] );
	}
	return $ranked;
}

/**
 * Read the whole site into a structured digest for the AI.
 *
 * @param int $max_posts Max posts to include.
 * @return array {categories, top_articles, recent_titles, digest}
 */
function chuquipiondo_ai_read_site( $max_posts = 30 ) {
	$max_posts = max( 5, min( 100, (int) $max_posts ) );

	$categories = array();
	$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => true, 'number' => 30 ) );
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			$categories[] = sprintf( '%s (%d articulos)', $t->name, (int) $t->count );
		}
	}

	$top = chuquipiondo_ai_top_articles( 10 );

	$q = new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $max_posts,
		'no_found_rows'  => true,
	) );
	$recent = array();
	$recent_titles = array();
	if ( $q->have_posts() ) {
		foreach ( $q->posts as $p ) {
			$recent[] = array(
				'title'   => $p->post_title,
				'excerpt' => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $p->ID ) ), 20, '...' ),
				'tags'    => wp_get_post_terms( $p->ID, 'post_tag', array( 'fields' => 'names' ) ),
				'views'   => chuquipiondo_ai_get_views( $p->ID ),
			);
			$recent_titles[] = $p->post_title;
		}
	}

	// Human-readable digest the AI can consume in one prompt.
	$digest = "CATEGORIAS DEL SITIO: " . ( empty( $categories ) ? '(ninguna)' : implode( '; ', $categories ) ) . "\n\n";
	$digest .= "ARTICULOS MAS LEIDOS:\n";
	foreach ( $top as $i => $t ) {
		$digest .= sprintf( "%d. %s — %d lecturas — categorias: %s\n", $i + 1, $t['title'], $t['views'], implode( ', ', (array) $t['categories'] ) );
	}
	$digest .= "\nTITULOS RECIENTES (no repetir):\n- " . implode( "\n- ", $recent_titles ) . "\n";

	return array(
		'categories'    => $categories,
		'top_articles'  => $top,
		'recent_titles' => $recent_titles,
		'recent'        => $recent,
		'digest'        => $digest,
	);
}

/**
 * Ask the AI to review the site digest and propose data-driven topics.
 *
 * @param int $proposals How many article proposals.
 * @return array|WP_Error Each proposal: {topic, angle, why, target_keyword} | WP_Error.
 */
function chuquipiondo_ai_propose_from_site( $proposals = 5 ) {
	$client = Chuquipiondo_AI::instance()->client;
	$site = chuquipiondo_ai_read_site();
	$proposals = max( 1, min( 10, (int) $proposals ) );
	$brand = (string) chuquipiondo_ai_get_option( 'ai_brand_voice', '' );

	$result = $client->run_task(
		'improve_text',
		$site['digest'],
		sprintf(
			'Con base en este analisis del sitio (categorias, articulos mas leidos y titulos recientes), propone %d articulos NUEVOS que: (1) amplien los temas que mas se leen, (2) llenen huecos de contenido no cubiertos, (3) NO dupliquen los titulos existentes. Identidad editorial: %s. Formato EXACTO por propuesta, una por bloque:\nPROPUESTA N:\nTEMA: [titulo sugerido]\nANGULO: [enfoque diferencial en 1 linea]\nPORQUE: [razon basada en los datos de lectura]\nPALABRA_CLAVE: [focus keyword 2-4 palabras]',
			$proposals,
			$brand
		)
	);
	if ( is_wp_error( $result ) ) {
		return $result;
	}
	return chuquipiondo_ai_parse_proposals( (string) $result['content'] );
}

/**
 * Parse the proposals response into structured data.
 *
 * @param string $raw Raw AI response.
 * @return array[]
 */
function chuquipiondo_ai_parse_proposals( $raw ) {
	$out = array();
	$blocks = preg_split( '/PROPUESTA\s+\d+\s*:/i', (string) $raw );
	array_shift( $blocks ); // preamble
	foreach ( $blocks as $block ) {
		$grab = function ( $key ) use ( $block ) {
			if ( preg_match( '/^' . $key . ':\s*(.+)$/im', $block, $m ) ) {
				return trim( $m[1] );
			}
			return '';
		};
		$topic = sanitize_text_field( $grab( 'TEMA' ) );
		if ( '' === $topic ) {
			continue;
		}
		$out[] = array(
			'topic'          => $topic,
			'angle'          => sanitize_text_field( $grab( 'ANGULO' ) ),
			'why'            => sanitize_text_field( $grab( 'PORQUE' ) ),
			'target_keyword' => sanitize_text_field( $grab( 'PALABRA_CLAVE' ) ),
		);
	}
	return $out;
}
