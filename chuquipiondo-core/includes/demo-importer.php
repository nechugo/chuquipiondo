<?php
/**
 * Demo importer for the CHUQUIPIONDO Core plugin.
 *
 * Creates a full demo with sample articles (with Unsplash images),
 * fictitious ads, pages, music, menus, widgets and theme configuration.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the demo importer admin page.
 */
function chuquipiondo_core_demo_admin_menu() {
	add_submenu_page(
		'chuquipiondo-options',
		__( 'Importar Demo', 'chuquipiondo-core' ),
		__( 'Importar Demo', 'chuquipiondo-core' ),
		'manage_options',
		'chuquipiondo-demo',
		'chuquipiondo_core_demo_page_render'
	);
}
add_action( 'admin_menu', 'chuquipiondo_core_demo_admin_menu' );

/**
 * Render the demo importer page.
 */
function chuquipiondo_core_demo_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$demos = chuquipiondo_core_get_demos();

	?>
	<div class="wrap chuquipiondo-core-demo">
		<h1><?php esc_html_e( 'CHUQUIPIONDO - Importar Demo', 'chuquipiondo-core' ); ?></h1>
		<p><?php esc_html_e( 'Importa una configuracion demo con contenido de prueba. Esto creara articulos con imagenes, paginas, canciones, ads ficticios y configurara el tema.', 'chuquipiondo-core' ); ?></p>

		<div class="chuquipiondo-demo-grid">
			<?php foreach ( $demos as $demo_id => $demo ) : ?>
				<div class="chuquipiondo-demo-card">
					<h3><?php echo esc_html( $demo['name'] ); ?></h3>
					<p><?php echo esc_html( $demo['description'] ); ?></p>
					<form method="post">
						<input type="hidden" name="chuquipiondo_demo_id" value="<?php echo esc_attr( $demo_id ); ?>">
						<?php wp_nonce_field( 'chuquipiondo_demo_import', 'chuquipiondo_demo_nonce' ); ?>
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Importar', 'chuquipiondo-core' ); ?></button>
					</form>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Get available demos.
 *
 * @return array
 */
function chuquipiondo_core_get_demos() {
	return array(
		'editorial' => array(
			'name'        => __( 'Portal Editorial (Completo)', 'chuquipiondo-core' ),
			'description' => __( 'Demo completo: 10 articulos con imagenes de Unsplash, ads ficticios, 3 paginas, 4 canciones, categorias, menu, widgets y configuracion del tema.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 10,
				'pages'     => 3,
				'music'     => 4,
				'categories' => array( 'Liderazgo', 'Gestion', 'Formacion', 'Fe Cristiana', 'Musica', 'Recursos' ),
			),
		),
		'minimal' => array(
			'name'        => __( 'Minimal', 'chuquipiondo-core' ),
			'description' => __( 'Configuracion simple: 4 articulos con imagenes y una pagina.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 4,
				'pages'     => 1,
				'categories' => array( 'General' ),
			),
		),
		'music' => array(
			'name'        => __( 'Plataforma Musical', 'chuquipiondo-core' ),
			'description' => __( 'Enfocado en musica: 6 canciones + 3 articulos con imagenes.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 3,
				'pages'     => 1,
				'music'     => 6,
				'categories' => array( 'Musica', 'Fe Cristiana' ),
			),
		),
	);
}

/**
 * Handle the demo import.
 */
function chuquipiondo_core_handle_demo_import() {
	if ( ! isset( $_POST['chuquipiondo_demo_id'] ) ) {
		return;
	}
	if ( ! isset( $_POST['chuquipiondo_demo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_demo_nonce'] ), 'chuquipiondo_demo_import' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$demo_id = sanitize_key( $_POST['chuquipiondo_demo_id'] );
	chuquipiondo_core_do_demo_import( $demo_id );

	wp_safe_redirect( add_query_arg( 'chuquipiondo_demo_imported', '1', admin_url( 'admin.php?page=chuquipiondo-demo' ) ) );
	exit;
}
add_action( 'admin_init', 'chuquipiondo_core_handle_demo_import' );

/**
 * Execute the demo import (without nonce checks or redirects).
 * Called by both handle_demo_import() and the setup wizard.
 *
 * @param string $demo_id Demo ID to import.
 */
function chuquipiondo_core_do_demo_import( $demo_id ) {
	$demos = chuquipiondo_core_get_demos();

	if ( ! isset( $demos[ $demo_id ] ) ) {
		return;
	}

	$demo    = $demos[ $demo_id ];
	$content = $demo['content'];
	$demos   = chuquipiondo_core_get_demos();

	if ( ! isset( $demos[ $demo_id ] ) ) {
		return;
	}

	$demo    = $demos[ $demo_id ];
	$content = $demo['content'];
	$user_id = get_current_user_id();

	// ===== 1. Create categories =====
	$cat_ids = array();
	if ( isset( $content['categories'] ) ) {
		foreach ( $content['categories'] as $cat_name ) {
			$existing = get_term_by( 'name', $cat_name, 'category' );
			if ( $existing ) {
				$cat_ids[] = $existing->term_id;
			} else {
				$result = wp_insert_term( $cat_name, 'category' );
				if ( ! is_wp_error( $result ) ) {
					$cat_ids[] = $result['term_id'];
				}
			}
		}
	}

	// ===== 2. Fictitious ad code (image-based placeholders) =====
	$ad_wide  = '<a href="#" target="_blank"><img src="https://via.placeholder.com/728x90/06133a/7fd6ff?text=AD+728x90" alt="Ad" style="width:100%;max-width:728px;height:auto;display:block;margin:0 auto;" /></a>';
	$ad_box   = '<a href="#" target="_blank"><img src="https://via.placeholder.com/336x280/0a1f44/ffffff?text=AD+336x280" alt="Ad" style="width:100%;max-width:336px;height:auto;display:block;margin:0 auto;" /></a>';
	$ad_resp  = '<a href="#" target="_blank"><img src="https://via.placeholder.com/970x250/06133a/27b6ff?text=AD+Responsive" alt="Ad" style="width:100%;max-width:970px;height:auto;display:block;margin:0 auto;" /></a>';
	$ad_tall  = '<a href="#" target="_blank"><img src="https://via.placeholder.com/300x600/0a1f44/7fd6ff?text=AD+300x600" alt="Ad" style="width:100%;max-width:300px;height:auto;display:block;margin:0 auto;" /></a>';

	// ===== 3. Article titles and content templates =====
	$article_data = array(
		array(
			'title'   => __( 'Liderazgo con proposito: 7 claves para guiar con vision', 'chuquipiondo-core' ),
			'cat'     => 0,
			'img'     => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=1280&h=720&fit=crop',
			'excerpt' => __( 'El liderazgo no se trata de poder, sino de servicio. Descubre las 7 claves que todo lider con proposito debe conocer para transformar su entorno.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Gestion del tiempo: como organizar tu dia con eficacia', 'chuquipiondo-core' ),
			'cat'     => 1,
			'img'     => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=1280&h=720&fit=crop',
			'excerpt' => __( 'La gestion del tiempo es la base de la productividad. Aprende tecnicas probadas para organizar tu dia y alcanzar tus metas.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Formacion continua: el poder del aprendizaje permanente', 'chuquipiondo-core' ),
			'cat'     => 2,
			'img'     => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=1280&h=720&fit=crop',
			'excerpt' => __( 'Nunca dejes de aprender. La formacion continua es el motor del crecimiento personal y profesional en un mundo cambiante.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Fe Cristiana: encontrar proposito en lo cotidiano', 'chuquipiondo-core' ),
			'cat'     => 3,
			'img'     => 'https://images.unsplash.com/photo-1507692049790-de58290a4334?w=1280&h=720&fit=crop',
			'excerpt' => __( 'La fe no se vive solo en la iglesia, sino en cada acto cotidiano. Reflexiones sobre como integrar la fe en tu vida diaria.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Musica como herramienta de transformacion social', 'chuquipiondo-core' ),
			'cat'     => 4,
			'img'     => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=1280&h=720&fit=crop',
			'excerpt' => __( 'La musica tiene el poder de unir, sanar y transformar comunidades. Exploramos su impacto en la sociedad.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Recursos audiovisuales para el aprendizaje moderno', 'chuquipiondo-core' ),
			'cat'     => 5,
			'img'     => 'https://images.unsplash.com/photo-1535303311164-664fc9ec6532?w=1280&h=720&fit=crop',
			'excerpt' => __( 'Los recursos audiovisuales revolucionan la educacion. Descubre como aprovecharlos al maximo en tu formacion.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Como construir equipos de alto rendimiento', 'chuquipiondo-core' ),
			'cat'     => 0,
			'img'     => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1280&h=720&fit=crop',
			'excerpt' => __( 'Un equipo de alto rendimiento no surge por casualidad. Conoce los pilares para construir y liderar equipos extraordinarios.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Gestion financiera personal: primer paso hacia la libertad', 'chuquipiondo-core' ),
			'cat'     => 1,
			'img'     => 'https://images.unsplash.com/photo-1579621970795-87facc2f976d?w=1280&h=720&fit=crop',
			'excerpt' => __( 'La gestion financiera personal es fundamental para la libertad. Aprende los principios basicos para tomar control de tus finanzas.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'El arte de la oracion: guia practica para principiantes', 'chuquipiondo-core' ),
			'cat'     => 3,
			'img'     => 'https://images.unsplash.com/photo-1518306727298-4c17e1bf69a4?w=1280&h=720&fit=crop',
			'excerpt' => __( 'La oracion es una conversacion con lo divino. Una guia sencilla para comenzar tu camino de fe con confianza.', 'chuquipiondo-core' ),
		),
		array(
			'title'   => __( 'Produccion musical independiente: del garage al escenario', 'chuquipiondo-core' ),
			'cat'     => 4,
			'img'     => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1280&h=720&fit=crop',
			'excerpt' => __( 'Como producir musica desde casa y llevarla al publico. Herramientas, consejos y estrategias para el musico independiente.', 'chuquipiondo-core' ),
		),
	);

	// ===== 4. Create posts =====
	$created_posts = array();
	if ( isset( $content['posts'] ) ) {
		$num_posts = min( $content['posts'], count( $article_data ) );
		for ( $i = 0; $i < $num_posts; $i++ ) {
			$adata = $article_data[ $i ];
			$cat_idx = isset( $adata['cat'] ) && isset( $cat_ids[ $adata['cat'] ] ) ? $adata['cat'] : 0;
			$cat_id = isset( $cat_ids[ $cat_idx ] ) ? array( $cat_ids[ $cat_idx ] ) : array();

			// Build rich content with paragraphs, headings, lists, blockquote, and an image.
			$rich_content  = '<p>' . $adata['excerpt'] . '</p>';
			$rich_content .= '<h2>' . __( 'Introduccion', 'chuquipiondo-core' ) . '</h2>';
			$rich_content .= '<p>' . __( 'En este articulo exploraremos a fondo el tema, con ejemplos practicos y reflexiones que puedes aplicar en tu vida diaria. Nuestro proposito es aportar valor real, no solo teoria.', 'chuquipiondo-core' ) . '</p>';
			$rich_content .= '<figure class="wp-block-image"><img src="' . esc_url( $adata['img'] ) . '" alt="' . esc_attr( $adata['title'] ) . '" /><figcaption>' . esc_html__( 'Ilustracion del articulo', 'chuquipiondo-core' ) . '</figcaption></figure>';
			$rich_content .= '<p>' . __( 'El primer paso para cualquier cambio significativo es la awareness, la conciencia de donde estamos y hacia donde queremos ir. Sin esa claridad, cualquier esfuerzo se dispersa y pierde fuerza.', 'chuquipiondo-core' ) . '</p>';
			$rich_content .= '<p>' . __( 'En segundo lugar, necesitamos un plan. No tiene que ser perfecto, pero debe existir. Un plan simple ejecutado con disciplina siempre vencera a un plan brillante que nunca se pone en marcha.', 'chuquipiondo-core' ) . '</p>';
			$rich_content .= '<h2>' . __( 'Puntos clave', 'chuquipiondo-core' ) . '</h2>';
			$rich_content .= '<ul><li>' . __( 'Define tu vision con claridad y precision.', 'chuquipiondo-core' ) . '</li>';
			$rich_content .= '<li>' . __( 'Establece metas medibles y alcanzables.', 'chuquipiondo-core' ) . '</li>';
			$rich_content .= '<li>' . __( 'Crea un sistema de seguimiento y accountability.', 'chuquipiondo-core' ) . '</li>';
			$rich_content .= '<li>' . __( 'Celebra los pequenos avances, no solo el resultado final.', 'chuquipiondo-core' ) . '</li></ul>';
			$rich_content .= '<blockquote><p>' . __( 'El exito no es la clave de la felicidad. La felicidad es la clave del exito. Si amas lo que estas haciendo, tendras exito.', 'chuquipiondo-core' ) . '</p></blockquote>';
			$rich_content .= '<p>' . __( 'Finalmente, recuerda que el camino no es lineal. Habra obstaculos, dudas y momentos de dificultad. Pero cada paso, por pequeno que sea, te acerca a tu destino. !Juntos, si podemos!', 'chuquipiondo-core' ) . '</p>';

			// Verificar si ya existe un post con ese titulo (evitar duplicados).
			$existing_post = get_page_by_title( $adata['title'], OBJECT, 'post' );
			if ( $existing_post ) {
				$created_posts[] = $existing_post->ID;
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_title'    => $adata['title'],
				'post_content'  => $rich_content,
				'post_excerpt'  => $adata['excerpt'],
				'post_status'   => 'publish',
				'post_author'   => $user_id,
				'post_category' => $cat_id,
				'post_type'     => 'post',
			) );

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				$created_posts[] = $post_id;
				// Attach featured image from Unsplash (sideload).
				chuquipiondo_core_sideload_image( $adata['img'], $post_id, true );
			}
		}
	}

	// ===== 5. Create pages =====
	$page_data = array(
		array( 'Sobre Nosotros', '<h1>Sobre CHUQUIPIONDO</h1><p>CHUQUIPIONDO es un ecosistema digital que integra Fe Cristiana, Musica, Articulos, Reflexiones, Liderazgo, Gestion, Formacion y Recursos Audiovisuales.</p><p>Nuestra mision es aportar valor real a la comunidad, con contenido que inspire, forme y transforme.</p>' ),
		array( 'Contacto', '<h1>Contacto</h1><p>Puedes escribirnos a contacto@chuquipiondo.com o seguirnos en nuestras redes sociales.</p>' ),
		array( 'Servicios', '<h1>Nuestros Servicios</h1><p>Ofrecemos contenido editorial, musica, formacion y recursos audiovisuales para la comunidad.</p>' ),
	);

	if ( isset( $content['pages'] ) ) {
		$num_pages = min( $content['pages'], count( $page_data ) );
		for ( $i = 0; $i < $num_pages; $i++ ) {
			$pdata = $page_data[ $i ];
			$existing_page = get_page_by_title( $pdata[0], OBJECT, 'page' );
			if ( $existing_page ) {
				continue;
			}
			wp_insert_post( array(
				'post_title'   => $pdata[0],
				'post_content' => $pdata[1],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => $user_id,
			) );
		}
	}

	// ===== 6. Create music =====
	$music_data = array(
		array( 'Gracia Increible', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3' ),
		array( 'Juntos Si Podemos', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3' ),
		array( 'Liderazgo y Fe', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3' ),
		array( 'Caminando con Proposito', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3' ),
		array( 'Nueva Tierra', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3' ),
		array( 'Esperanza', 'Nelson Chuquipiondo', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-6.mp3' ),
	);

	if ( isset( $content['music'] ) && post_type_exists( 'musica' ) ) {
		$num_music = min( $content['music'], count( $music_data ) );
		for ( $i = 0; $i < $num_music; $i++ ) {
			$mdata = $music_data[ $i ];
			$existing_music = get_page_by_title( $mdata[0], OBJECT, 'musica' );
			if ( $existing_music ) {
				continue;
			}
			$music_id = wp_insert_post( array(
				'post_title'   => $mdata[0],
				'post_content' => '<p>' . sprintf( __( 'Letra de %s. Una cancion inspirada en la fe y el liderazgo con proposito.', 'chuquipiondo-core' ), $mdata[0] ) . '</p>',
				'post_status'  => 'publish',
				'post_type'    => 'musica',
				'post_author'  => $user_id,
			) );
			if ( $music_id && ! is_wp_error( $music_id ) ) {
				update_post_meta( $music_id, '_chuquipiondo_artist', $mdata[1] );
				update_post_meta( $music_id, '_chuquipiondo_audio_url', $mdata[2] );
			}
		}
	}

	// ===== 7. Configure ads (fictitious placeholders) =====
	$ad_options = array(
		'ads_master_switch'        => '1',
		'ads_mode'                 => 'manual',
		'ads_after_title'          => $ad_wide,
		'ads_after_thumbnail'      => $ad_box,
		'ads_after_paragraph_3'     => $ad_box,
		'ads_after_paragraph_6'     => $ad_wide,
		'ads_after_paragraph_8'     => $ad_resp,
		'ads_before_related'        => $ad_wide,
		'ads_blog_top'             => $ad_wide,
		'ads_blog_after_row'       => $ad_wide,
		'ads_blog_middle'          => $ad_box,
		'ads_blog_bottom'          => $ad_resp,
		'ads_sidebar_top'          => $ad_box,
		'ads_sidebar_middle'       => $ad_tall,
		'ads_sidebar_bottom'       => $ad_box,
		'ads_header_after'         => $ad_wide,
		'ads_home_after_hero'      => $ad_resp,
		'ads_home_after_featured'  => $ad_wide,
		'ads_footer_before'        => $ad_wide,
		'ads_footer_after'         => $ad_wide,
	);

	foreach ( $ad_options as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// ===== 8. Configure social profiles =====
	$social_options = array(
		'social_master_switch' => '1',
		'social_facebook'      => 'https://facebook.com/chuquipiondo',
		'social_youtube'       => 'https://www.youtube.com/@chuquipiondo',
		'social_instagram'     => 'https://instagram.com/chuquipiondo',
		'social_x'             => 'https://x.com/chuquipiondo',
		'social_linkedin'      => 'https://linkedin.com/in/chuquipiondo',
		'social_telegram'      => 'https://t.me/chuquipiondo',
		'social_tiktok'        => 'https://tiktok.com/@chuquipiondo',
		'social_position'      => 'after',
		'social_floating'      => '1',
	);
	foreach ( $social_options as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// ===== 9. Configure WhatsApp =====
	$whatsapp_options = array(
		'whatsapp_master_switch' => '1',
		'whatsapp_number'        => '51921497257',
		'whatsapp_mode'          => 'message',
		'whatsapp_message'       => __( 'Hola, me interesa el contenido de CHUQUIPIONDO', 'chuquipiondo-core' ),
		'whatsapp_position'      => 'bottom-right',
		'whatsapp_size'          => '52',
		'whatsapp_mobile_size'    => '48',
	);
	foreach ( $whatsapp_options as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// ===== 10. Configure theme settings =====
	$theme_options = array(
		'header_topbar_enable'        => '1',
		'header_topbar_date'          => '1',
		'header_topbar_time'          => '1',
		'header_topbar_email'         => 'contacto@chuquipiondo.com',
		'header_topbar_gap'           => '4',
		'hero_enable'                => '1',
		'hero_mode'                  => 'slider',
		'hero_autoplay'              => '1',
		'hero_speed'                 => '5000',
		'home_modules'               => 'hero,featured,latest,categories,song,videos,about,newsletter',
		'footer_columns'             => '4',
		'footer_show_brand'          => '1',
		'footer_show_copyright'      => '1',
		'footer_show_menu'           => '1',
		'footer_show_social'         => '1',
		'footer_about'               => 'CHUQUIPIONDO - Liderazgo, Gestion y Formacion con proposito. !Juntos, si podemos!',
		'footer_copyright'           => '(c) {year} Nelson Chuquipiondo. Todos los derechos reservados.',
		'single_content_font'        => '',
		'single_content_size'        => '17',
		'single_content_weight'      => '400',
		'single_content_line_height' => '1.7',
		'header_content_gap'         => '25',
		'ads_blog_after_posts'       => '2',
	);
	foreach ( $theme_options as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// ===== 11. Create footer widgets (if sidebar-footer is empty) =====
	if ( ! is_active_sidebar( 'sidebar-footer' ) ) {
		$footer_widgets = array(
			array(
				'title' => __( 'Sobre CHUQUIPIONDO', 'chuquipiondo-core' ),
				'text'  => '<p>Liderazgo, Gestion y Formacion con proposito. !Juntos, si podemos!</p>',
			),
			array(
				'title' => __( 'Enlaces', 'chuquipiondo-core' ),
				'text'  => '<ul><li><a href="#">Inicio</a></li><li><a href="#">Sobre Nosotros</a></li><li><a href="#">Contacto</a></li><li><a href="#">Servicios</a></li></ul>',
			),
			array(
				'title' => __( 'Categorias', 'chuquipiondo-core' ),
				'text'  => '<ul><li><a href="#">Liderazgo</a></li><li><a href="#">Gestion</a></li><li><a href="#">Formacion</a></li><li><a href="#">Fe Cristiana</a></li></ul>',
			),
			array(
				'title' => __( 'Contacto', 'chuquipiondo-core' ),
				'text'  => '<p>contacto@chuquipiondo.com</p><p>+51 921 497 257</p>',
			),
		);

		// Find the next available text widget ID.
		$text_widgets = get_option( 'widget_text', array() );
		$next_id = 2;
		while ( isset( $text_widgets[ $next_id ] ) ) {
			$next_id++;
		}

		$sidebars = get_option( 'sidebars_widgets', array() );
		if ( ! isset( $sidebars['sidebar-footer'] ) || ! is_array( $sidebars['sidebar-footer'] ) ) {
			$sidebars['sidebar-footer'] = array();
		}

		foreach ( $footer_widgets as $wdata ) {
			$widget_id = 'text-' . $next_id;
			$text_widgets[ $next_id ] = array(
				'title'  => $wdata['title'],
				'text'   => $wdata['text'],
				'filter' => true,
				'visual' => true,
			);
			$sidebars['sidebar-footer'][] = $widget_id;
			$next_id++;
		}

		update_option( 'widget_text', $text_widgets );
		update_option( 'sidebars_widgets', $sidebars );
	}

	// ===== 12. Create menu (if not exists) =====
	$menu_name   = 'Menu Principal Demo';
	$menu_exists = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu_exists ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		$home_page = get_page_by_path( 'sobre-nosotros' );
		if ( $home_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Sobre Nosotros', 'chuquipiondo-core' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $home_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			) );
		}
		$contact_page = get_page_by_path( 'contacto' );
		if ( $contact_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Contacto', 'chuquipiondo-core' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $contact_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			) );
		}
		$services_page = get_page_by_path( 'servicios' );
		if ( $services_page ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => __( 'Servicios', 'chuquipiondo-core' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $services_page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			) );
		}
		// Custom link to blog.
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => __( 'Blog', 'chuquipiondo-core' ),
			'menu-item-url'    => home_url( '/' ),
			'menu-item-status' => 'publish',
		) );

		// Assign to primary location.
		$locations = get_theme_mod( 'nav_menu_locations' );
		if ( ! is_array( $locations ) ) {
			$locations = array();
		}
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	// ===== 13. Set front page to show latest posts =====
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );

	// ===== 14. Apply the "Chuquipiondo Original" preset =====
	if ( function_exists( 'chuquipiondo_apply_preset' ) ) {
		chuquipiondo_apply_preset( 'original' );
	}

}

/**
 * Sideload an image from a URL and attach it to a post.
 *
 * @param string $url     Image URL.
 * @param int    $post_id Post ID.
 * @param bool   $featured Whether to set as featured image.
 */
function chuquipiondo_core_sideload_image( $url, $post_id, $featured = false ) {
	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	// Temporarily suppress errors (external image may be slow or blocked).
	$old           = error_reporting( 0 );
	$attachment_id = media_sideload_image( $url, $post_id, null, 'id' );
	error_reporting( $old );

	if ( $featured && $attachment_id && ! is_wp_error( $attachment_id ) ) {
		set_post_thumbnail( $post_id, $attachment_id );
	}
}

/**
 * Admin notice for successful import.
 */
function chuquipiondo_core_demo_imported_notice() {
	if ( ! isset( $_GET['chuquipiondo_demo_imported'] ) ) {
		return;
	}
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo importado correctamente. Se crearon articulos con imagenes, paginas, musica, ads ficticios y configuracion del tema. Revisa tu sitio.', 'chuquipiondo-core' ) . '</p></div>';
}
add_action( 'admin_notices', 'chuquipiondo_core_demo_imported_notice' );

/**
 * Setup wizard handler: processes the 3 setup options from the welcome page.
 *
 * Modes:
 *  - full_demo:       Import the complete demo (articles, pages, music, ads, config).
 *  - adapt:           Reorganize existing content (pages, posts, menus) to the theme architecture.
 *  - structure_only:   Configure the theme (presets, menus, widgets) without creating content.
 */
function chuquipiondo_core_handle_setup_wizard() {
	if ( ! isset( $_POST['chuquipiondo_setup_mode'] ) ) {
		return;
	}
	if ( ! isset( $_POST['chuquipiondo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_nonce'] ), 'chuquipiondo_setup_wizard' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$mode = sanitize_key( $_POST['chuquipiondo_setup_mode'] );

	switch ( $mode ) {
		case 'full_demo':
			// Import the editorial demo directly (no $_POST injection to avoid
			// double execution via admin_init).
			chuquipiondo_core_do_demo_import( 'editorial' );
			break;

		case 'adapt':
			chuquipiondo_core_adapt_existing_content();
			break;

		case 'structure_only':
			chuquipiondo_core_apply_theme_structure();
			break;
	}

	// Mark setup as done so the wizard doesn't show again.
	update_option( 'chuquipiondo_setup_done', true, false );

	wp_safe_redirect( add_query_arg( 'chuquipiondo_setup_done', $mode, admin_url( 'admin.php?page=chuquipiondo-welcome' ) ) );
	exit;
}
add_action( 'admin_post_chuquipiondo_setup_wizard', 'chuquipiondo_core_handle_setup_wizard' );

/**
 * Reorganize and adapt existing content to the theme architecture.
 *
 * - Ensures the front page shows latest posts.
 * - Reassigns existing posts to categories that match the theme's structure.
 * - Creates a primary menu from existing pages if none exists.
 * - Assigns the footer menu location.
 * - Applies the Chuquipiondo Original preset.
 * - Configures sidebar and widget zones.
 */
function chuquipiondo_core_adapt_existing_content() {
	$user_id = get_current_user_id();

	// 1. Ensure the theme's default categories exist.
	$default_cats = array( 'Liderazgo', 'Gestion', 'Formacion', 'Fe Cristiana', 'Musica', 'Recursos', 'General' );
	$cat_ids      = array();
	foreach ( $default_cats as $cat_name ) {
		$existing = get_term_by( 'name', $cat_name, 'category' );
		if ( $existing ) {
			$cat_ids[ $cat_name ] = $existing->term_id;
		} else {
			$result = wp_insert_term( $cat_name, 'category' );
			if ( ! is_wp_error( $result ) ) {
				$cat_ids[ $cat_name ] = $result['term_id'];
			}
		}
	}

	// 2. Reassign posts without categories to 'General'.
	$general_id = isset( $cat_ids['General'] ) ? $cat_ids['General'] : 0;
	if ( $general_id ) {
		$uncategorized = get_posts( array(
			'numberposts' => -1,
			'post_type'    => 'post',
			'post_status'  => 'any',
			'fields'       => 'ids',
			'category__not_in' => array_values( $cat_ids ),
		) );
		foreach ( $uncategorized as $post_id ) {
			wp_set_post_categories( $post_id, array( $general_id ), false );
		}
	}

	// 3. Front page: show latest posts (so the home builder works).
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );

	// 4. Create or update the primary menu from existing pages.
	$menu_name = 'Menu Principal';
	$menu      = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
		// Clear existing items to rebuild cleanly.
		$items = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item->ID, true );
			}
		}
	}

	// Add all published pages to the menu.
	$pages = get_pages( array( 'sort_column' => 'menu_order, post_title' ) );
	foreach ( $pages as $page ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $page->post_title,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
		) );
	}

	// Assign to primary + footer + mobile locations.
	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	if ( empty( $locations['primary'] ) ) {
		$locations['primary'] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// 5. Apply theme structure (presets, widgets, options).
	chuquipiondo_core_apply_theme_structure();

	// 6. Ensure existing posts have excerpts if missing.
	$posts = get_posts( array( 'numberposts' => -1, 'post_type' => 'post', 'post_status' => 'publish' ) );
	foreach ( $posts as $post ) {
		if ( empty( $post->post_excerpt ) ) {
			$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
			wp_update_post( array(
				'ID'           => $post->ID,
				'post_excerpt' => $excerpt,
			) );
		}
	}
}

/**
 * Apply theme structure without creating content.
 *
 * - Applies the Chuquipiondo Original preset.
 * - Creates footer widgets if the sidebar is empty.
 * - Configures default theme options (topbar, hero, footer, etc.).
 * - Sets up the front page to show posts.
 */
function chuquipiondo_core_apply_theme_structure() {
	// 1. Apply the "Chuquipiondo Original" preset.
	if ( function_exists( 'chuquipiondo_apply_preset' ) ) {
		chuquipiondo_apply_preset( 'original' );
	}

	// 2. Configure default theme options.
	$theme_options = array(
		'header_topbar_enable'        => '1',
		'header_topbar_date'          => '1',
		'header_topbar_time'          => '1',
		'header_topbar_email'         => 'contacto@chuquipiondo.com',
		'header_topbar_gap'           => '4',
		'hero_enable'                => '1',
		'hero_mode'                  => 'slider',
		'hero_autoplay'              => '1',
		'hero_speed'                 => '5000',
		'home_modules'               => 'hero,featured,latest,categories,song,videos,about,newsletter',
		'footer_columns'             => '4',
		'footer_show_brand'          => '1',
		'footer_show_copyright'      => '1',
		'footer_show_menu'           => '1',
		'footer_show_social'         => '1',
		'footer_about'               => 'CHUQUIPIONDO - Liderazgo, Gestion y Formacion con proposito. !Juntos, si podemos!',
		'footer_copyright'           => '(c) {year} Nelson Chuquipiondo. Todos los derechos reservados.',
		'single_content_font'        => '',
		'single_content_size'        => '17',
		'single_content_weight'      => '400',
		'single_content_line_height' => '1.7',
		'header_content_gap'         => '25',
		'ads_blog_after_posts'       => '2',
		'social_master_switch'       => '1',
		'social_youtube'             => 'https://www.youtube.com/@chuquipiondo',
		'whatsapp_master_switch'     => '1',
		'whatsapp_number'            => '51921497257',
		'whatsapp_position'          => 'bottom-right',
		'whatsapp_size'              => '52',
		'whatsapp_mobile_size'       => '48',
	);
	foreach ( $theme_options as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// 3. Create footer widgets if the sidebar is empty.
	if ( ! is_active_sidebar( 'sidebar-footer' ) ) {
		$footer_widgets = array(
			array(
				'title' => __( 'Sobre CHUQUIPIONDO', 'chuquipiondo-core' ),
				'text'  => '<p>Liderazgo, Gestion y Formacion con proposito. !Juntos, si podemos!</p>',
			),
			array(
				'title' => __( 'Enlaces', 'chuquipiondo-core' ),
				'text'  => '<ul><li><a href="#">Inicio</a></li><li><a href="#">Blog</a></li><li><a href="#">Contacto</a></li></ul>',
			),
			array(
				'title' => __( 'Categorias', 'chuquipiondo-core' ),
				'text'  => '<ul><li><a href="#">Liderazgo</a></li><li><a href="#">Gestion</a></li><li><a href="#">Formacion</a></li></ul>',
			),
			array(
				'title' => __( 'Contacto', 'chuquipiondo-core' ),
				'text'  => '<p>contacto@chuquipiondo.com</p>',
			),
		);

		$text_widgets = get_option( 'widget_text', array() );
		$next_id      = 2;
		while ( isset( $text_widgets[ $next_id ] ) ) {
			$next_id++;
		}

		$sidebars = get_option( 'sidebars_widgets', array() );
		if ( ! isset( $sidebars['sidebar-footer'] ) || ! is_array( $sidebars['sidebar-footer'] ) ) {
			$sidebars['sidebar-footer'] = array();
		}

		foreach ( $footer_widgets as $wdata ) {
			$widget_id = 'text-' . $next_id;
			$text_widgets[ $next_id ] = array(
				'title'  => $wdata['title'],
				'text'   => $wdata['text'],
				'filter' => true,
				'visual' => true,
			);
			$sidebars['sidebar-footer'][] = $widget_id;
			$next_id++;
		}

		update_option( 'widget_text', $text_widgets );
		update_option( 'sidebars_widgets', $sidebars );
	}

	// 4. Front page: show latest posts.
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );
}

/**
 * Admin notice for setup wizard completion.
 */
function chuquipiondo_core_setup_done_notice() {
	if ( ! isset( $_GET['chuquipiondo_setup_done'] ) ) {
		return;
	}
	$mode  = sanitize_key( $_GET['chuquipiondo_setup_done'] );
	$msgs  = array(
		'full_demo'     => __( 'Demo completa importada y tema configurado. Revisa tu sitio.', 'chuquipiondo-core' ),
		'adapt'         => __( 'Contenido existente adaptado a la arquitectura del tema. Paginas, blog, articulos y menus reorganizados.', 'chuquipiondo-core' ),
		'structure_only' => __( 'Estructura del tema aplicada (presets, widgets, menus y opciones). No se creo contenido nuevo.', 'chuquipiondo-core' ),
	);
	$msg = isset( $msgs[ $mode ] ) ? $msgs[ $mode ] : $msgs['structure_only'];
	echo '<div class="notice notice-success"><p>' . esc_html( $msg ) . '</p></div>';
}
add_action( 'admin_notices', 'chuquipiondo_core_setup_done_notice' );
