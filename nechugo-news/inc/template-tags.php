<?php
/**
 * Etiquetas de plantilla reutilizables.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logo o nombre del sitio.
 */
function nechugo_site_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	$home  = is_front_page();
	$tag   = $home ? 'h1' : 'p';
	printf(
		'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
		esc_html( $tag ),
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
	if ( $home || ! get_theme_mod( 'nechugo_hide_tagline', true ) ) {
		echo '<span class="site-description">' . esc_html( get_bloginfo( 'description' ) ) . '</span>';
	}
}

/**
 * HTML del toggle de busqueda.
 */
function nechugo_search_toggle() {
	if ( ! nechugo_is_enabled( 'header_search_enable' ) ) {
		return;
	}
	?>
	<div class="nechugo-search-form">
		<?php get_search_form(); ?>
	</div>
	<?php
}

/**
 * Toggle del menu movil.
 */
function nechugo_menu_toggle() {
	?>
	<button class="nechugo-menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'nechugo-news' ); ?>">
		<span></span><span></span><span></span>
	</button>
	<?php
}

/**
 * Menu de navegacion con fallback elegante.
 *
 * @param string $location Ubicacion del menu.
 * @param string $class    Clase del contenedor.
 */
function nechugo_nav_menu( $location = 'primary', $class = 'main-menu' ) {
	echo '<nav class="nechugo-nav ' . esc_attr( $class ) . '" aria-label="' . esc_attr__( 'Navegacion', 'nechugo-news' ) . '">';
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => 'nechugo-menu',
				'fallback_cb'    => false,
				'depth'          => 2,
			)
		);
	} elseif ( 'primary' === $location ) {
		echo '<ul class="nechugo-menu"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'nechugo-news' ) . '</a></li>';
		wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
		echo '</ul>';
	}
	echo '</nav>';
}

/**
 * Meta del post: autor, fecha, tiempo de lectura.
 */
function nechugo_post_meta() {
	$items = array();
	if ( nechugo_is_enabled( 'show_author' ) ) {
		$items[] = '<span class="meta-author">' . esc_html( get_the_author() ) . '</span>';
	}
	if ( nechugo_is_enabled( 'show_date' ) ) {
		$items[] = '<time class="meta-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	}
	if ( nechugo_is_enabled( 'show_reading_time' ) ) {
		$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
		$minutes = max( 1, (int) ceil( $words / 200 ) );
		$items[] = '<span class="meta-reading">' . sprintf( esc_html__( '%d min de lectura', 'nechugo-news' ), $minutes ) . '</span>';
	}
	if ( empty( $items ) ) {
		return;
	}
	echo '<div class="entry-meta">' . implode( '<span class="meta-sep">·</span>', $items ) . '</div>'; // phpcs:ignore
}

/**
 * Categoria destacada de un post.
 */
function nechugo_first_category() {
	$cats = get_the_category();
	if ( empty( $cats ) ) {
		return;
	}
	echo '<a class="nechugo-cat" href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
}

/**
 * Breadcrumbs ligeros.
 */
function nechugo_breadcrumbs() {
	if ( ! nechugo_is_enabled( 'show_breadcrumbs' ) || ( is_front_page() && ! is_paged() ) ) {
		return;
	}
	$links = array( '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'nechugo-news' ) . '</a>' );
	if ( is_singular() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor ) {
			$links[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
		}
		if ( is_page() ) {
			$links[] = '<span>' . esc_html( get_the_title() ) . '</span>';
		}
	} elseif ( is_archive() ) {
		$links[] = '<span>' . esc_html( wp_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		$links[] = '<span>' . esc_html__( 'Resultados de busqueda', 'nechugo-news' ) . '</span>';
	}
	echo '<nav class="nechugo-breadcrumbs" aria-label="' . esc_attr__( 'Miga de pan', 'nechugo-news' ) . '">' . implode( ' <span class="sep">/</span> ', $links ) . '</nav>'; // phpcs:ignore
}

/**
 * Botones de compartir en redes.
 */
function nechugo_share_buttons() {
	if ( ! nechugo_is_enabled( 'show_share_buttons' ) || ! is_singular() ) {
		return;
	}
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$net   = array(
		'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'twitter'   => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		'whatsapp'  => 'https://wa.me/?text=' . $title . '%20' . $url,
	);
	echo '<div class="nechugo-share"><span>' . esc_html__( 'Compartir:', 'nechugo-news' ) . '</span>';
	foreach ( $net as $name => $link ) {
		echo '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener nofollow" class="share-' . esc_attr( $name ) . '">' . esc_html( ucfirst( $name ) ) . '</a>';
	}
	echo '</div>';
}
