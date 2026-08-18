<?php
/**
 * Module 1c: Mega Menu.
 *
 * Adds a mega-menu walker that renders rich, multi-column dropdowns
 * for top-level menu items flagged as "mega". Configurable per item
 * via a nav meta box. Trigger mode (hover/click) and column count
 * are global options.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mega menu walker.
 *
 * Extends the default walker to emit a mega-panel for top-level items
 * that have the "_chuquipiondo_mega" meta set to 1. Children are
 * resolved from the menu items array passed by wp_nav_menu().
 */
class Chuquipiondo_Companion_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Cached menu items keyed by parent ID.
	 *
	 * @var array
	 */
	protected $children_cache = array();

	/**
	 * Starts the list of child elements.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output            Used to append additional content.
	 * @param WP_Post  $item              Menu item data object.
	 * @param int      $depth             Depth of menu item.
	 * @param stdClass $args              An object of wp_nav_menu() arguments.
	 * @param int      $id                ID of the current menu item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Prime the children cache from the menu object on the first call.
		$this->prime_children_cache( $args );

		$is_mega = ( '1' === get_post_meta( $item->ID, '_chuquipiondo_mega', true ) );

		if ( 0 === $depth && $is_mega ) {
			$item->classes[] = 'menu-item-mega';
		}

		parent::start_el( $output, $item, $depth, $args, $id );

		// Inject the mega panel right after the opening <li> of a mega item.
		if ( 0 === $depth && $is_mega ) {
			$output .= chuquipiondo_companion_render_mega_panel( $item, $this->children_cache );
		}
	}

	/**
	 * Populate the children cache from the menu items in $args.
	 *
	 * @param stdClass $args wp_nav_menu() arguments.
	 */
	protected function prime_children_cache( $args ) {
		if ( ! empty( $this->children_cache ) ) {
			return;
		}
		// wp_nav_menu() stores the flat list of items in $args->menu or
		// in the walker's internal $elements. We resolve them via the
		// current menu term if available.
		$menu   = isset( $args->menu ) ? $args->menu : null;
		$term_id = 0;
		if ( is_object( $menu ) && isset( $menu->term_id ) ) {
			$term_id = (int) $menu->term_id;
		} elseif ( is_numeric( $menu ) ) {
			$term_id = (int) $menu;
		}

		if ( ! $term_id ) {
			// Fallback: resolve from the assigned theme location.
			$locations = get_nav_menu_locations();
			$loc       = isset( $args->theme_location ) ? $args->theme_location : 'primary';
			if ( isset( $locations[ $loc ] ) ) {
				$term_id = (int) $locations[ $loc ];
			}
		}

		if ( ! $term_id ) {
			return;
		}

		$items = wp_get_nav_menu_items( $term_id, array( 'post_status' => 'publish' ) );
		if ( ! $items ) {
			return;
		}
		foreach ( $items as $i ) {
			$parent = (int) ( isset( $i->menu_item_parent ) ? $i->menu_item_parent : 0 );
			if ( ! isset( $this->children_cache[ $parent ] ) ) {
				$this->children_cache[ $parent ] = array();
			}
			$this->children_cache[ $parent ][] = $i;
		}
	}
}

/**
 * Render the mega panel content for a top-level menu item.
 *
 * @param WP_Post $item       Menu item.
 * @param array   $children   Children cache (parent_id => items[]).
 * @return string HTML markup.
 */
function chuquipiondo_companion_render_mega_panel( $item, $children = array() ) {
	$columns = (int) chuquipiondo_companion_get_option( 'companion_mega_menu_columns', '4' );
	$columns = max( 2, min( 6, $columns ) );
	$width   = sanitize_html_class( chuquipiondo_companion_get_option( 'companion_mega_menu_width' ) );

	$style = '';
	if ( 'custom' === $width ) {
		$custom = (int) chuquipiondo_companion_get_option( 'companion_mega_menu_custom_width', '1200' );
		$style  = ' style="max-width:' . $custom . 'px"';
	}

	$direct_children = isset( $children[ $item->ID ] ) ? $children[ $item->ID ] : array();

	ob_start();
	echo '<div class="chuqui-mega-panel mega-cols--' . esc_attr( $columns ) . ' mega-width--' . esc_attr( $width ) . '"' . $style . ' aria-hidden="true">';

	if ( $direct_children ) {
		echo '<div class="mega-links">';
		foreach ( $direct_children as $child ) {
			echo '<div class="mega-links__col">';
			echo '<a href="' . esc_url( $child->url ) . '" class="mega-links__heading">' . esc_html( $child->title ) . '</a>';
			$grandchildren = isset( $children[ $child->ID ] ) ? $children[ $child->ID ] : array();
			if ( $grandchildren ) {
				echo '<ul class="mega-links__list">';
				foreach ( $grandchildren as $gc ) {
					echo '<li><a href="' . esc_url( $gc->url ) . '">' . esc_html( $gc->title ) . '</a></li>';
				}
				echo '</ul>';
			}
			echo '</div>';
		}
		echo '</div>';
	}

	// Featured posts column (when the item points to a category).
	$featured = chuquipiondo_companion_mega_featured_posts( $item );
	if ( $featured ) {
		echo '<div class="mega-featured">';
		echo '<span class="mega-featured__label">' . esc_html__( 'Destacados', 'chuquipiondo-companion' ) . '</span>';
		echo '<ul class="mega-featured__list">';
		foreach ( $featured as $p ) {
			echo '<li>';
			echo '<a href="' . esc_url( get_permalink( $p ) ) . '">';
			if ( has_post_thumbnail( $p ) ) {
				echo get_the_post_thumbnail( $p, 'chuquipiondo-card', array( 'loading' => 'lazy' ) );
			}
			echo '<span class="mega-featured__title">' . esc_html( get_the_title( $p ) ) . '</span>';
			echo '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
	}

	echo '</div>';
	return ob_get_clean();
}

/**
 * Fetch recent posts for a category-linked mega item.
 *
 * @param WP_Post $item Menu item.
 * @return WP_Post[] Empty if not a category or no posts.
 */
function chuquipiondo_companion_mega_featured_posts( $item ) {
	if ( 'category' !== $item->object || empty( $item->object_id ) ) {
		return array();
	}
	$posts = get_posts( array(
		'posts_per_page' => 3,
		'cat'            => (int) $item->object_id,
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	) );
	return $posts ? $posts : array();
}

/**
 * Register the nav meta box for the mega-menu toggle.
 */
function chuquipiondo_companion_mega_meta_box() {
	add_meta_box(
		'chuquipiondo-mega-menu',
		__( 'CHUQUIPIONDO Mega Menu', 'chuquipiondo-companion' ),
		'chuquipiondo_companion_mega_meta_box_render',
		'nav-menus',
		'side',
		'default'
	);
}
add_action( 'admin_head-nav-menus.php', 'chuquipiondo_companion_mega_meta_box' );

/**
 * Render the mega-menu meta box (JS-driven, updates selected items).
 */
function chuquipiondo_companion_mega_meta_box_render() {
	?>
	<p class="description"><?php esc_html_e( 'Selecciona uno o mas items del menu de nivel superior y marca "Mega Menu" para convertir su desplegable en un panel rico con columnas y posts destacados.', 'chuquipiondo-companion' ); ?></p>
	<div id="chuqui-mega-toggle">
		<label><input type="checkbox" id="chuqui-mega-enable" value="1"> <?php esc_html_e( 'Activar Mega Menu para este item', 'chuquipiondo-companion' ); ?></label>
		<p><button type="button" class="button" id="chuqui-mega-save"><?php esc_html_e( 'Guardar', 'chuquipiondo-companion' ); ?></button></p>
	</div>
	<?php
}

/**
 * Localize the admin JS with the ajax URL, nonce and i18n strings.
 */
function chuquipiondo_companion_mega_admin_localize() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<script type="text/javascript">
		window.chuquiCompanionAdmin = {
			ajaxUrl: <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,
			nonce: <?php echo wp_json_encode( wp_create_nonce( 'chuquipiondo_mega_save' ) ); ?>,
			i18n: {
				noItem: <?php echo wp_json_encode( __( 'Selecciona al menos un item del menu.', 'chuquipiondo-companion' ) ); ?>,
				saving: <?php echo wp_json_encode( __( 'Guardando...', 'chuquipiondo-companion' ) ); ?>,
				saved: <?php echo wp_json_encode( __( 'Mega Menu actualizado.', 'chuquipiondo-companion' ) ); ?>,
				error: <?php echo wp_json_encode( __( 'Error al guardar.', 'chuquipiondo-companion' ) ); ?>
			}
		};
	</script>
	<?php
}
add_action( 'admin_footer-nav-menus.php', 'chuquipiondo_companion_mega_admin_localize' );

/**
 * AJAX handler: persist the mega-menu flag for one or more menu items.
 */
function chuquipiondo_companion_mega_save_ajax() {
	check_ajax_referer( 'chuquipiondo_mega_save', 'nonce' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Permiso denegado.', 'chuquipiondo-companion' ) ) );
	}
	$ids     = isset( $_POST['ids'] ) ? array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['ids'] ) ) ) ) ) : array();
	$enabled = ( isset( $_POST['enabled'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) ) ? '1' : '0';
	if ( empty( $ids ) ) {
		wp_send_json_error( array( 'message' => __( 'No se recibieron items.', 'chuquipiondo-companion' ) ) );
	}
	foreach ( $ids as $item_id ) {
		update_post_meta( $item_id, '_chuquipiondo_mega', $enabled );
	}
	wp_send_json_success( array( 'updated' => count( $ids ) ) );
}
add_action( 'wp_ajax_chuquipiondo_mega_save', 'chuquipiondo_companion_mega_save_ajax' );
