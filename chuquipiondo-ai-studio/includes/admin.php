<?php
/**
 * Admin UI for CHUQUIPIONDO AI Studio.
 *
 * Registers a top-level menu with three screens:
 *  1. AI Editor  - browse/edit Entradas & Paginas (requirement 1 & 2).
 *  2. Generador  - publish a brand new AI article (requirement 3).
 *  3. Ajustes    - settings (rendered by settings.php).
 *
 * Also adds a meta box on the classic post/page editor with quick
 * AI actions, and injects the JSON-LD schema into the front-end head.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the admin menu.
 *
 * @return void
 */
function chuquipiondo_ai_admin_menu() {
	$cap = 'edit_posts';
	add_menu_page(
		__( 'CHUQUIPIONDO AI', 'chuquipiondo-ai' ),
		__( 'AI Studio', 'chuquipiondo-ai' ),
		$cap,
		'chuquipiondo-ai',
		'chuquipiondo_ai_editor_page_render',
		'dashicons-welcome-learn-more',
		58
	);
	add_submenu_page(
		'chuquipiondo-ai',
		__( 'Editor IA', 'chuquipiondo-ai' ),
		__( 'Editor IA', 'chuquipiondo-ai' ),
		$cap,
		'chuquipiondo-ai',
		'chuquipiondo_ai_editor_page_render'
	);
	add_submenu_page(
		'chuquipiondo-ai',
		__( 'Generar articulo', 'chuquipiondo-ai' ),
		__( 'Generar articulo', 'chuquipiondo-ai' ),
		$cap,
		'chuquipiondo-ai-generate',
		'chuquipiondo_ai_generate_page_render'
	);
	add_submenu_page(
		'chuquipiondo-ai',
		__( 'Ajustes', 'chuquipiondo-ai' ),
		__( 'Ajustes', 'chuquipiondo-ai' ),
		'manage_options',
		'chuquipiondo-ai-settings',
		'chuquipiondo_ai_settings_page_render'
	);
}
add_action( 'admin_menu', 'chuquipiondo_ai_admin_menu' );

/**
 * Helper: header for AI screens.
 *
 * @return void
 */
function chuquipiondo_ai_screen_header() {
	echo '<div class="wrap chuquipiondo-ai-admin"><h1>' . esc_html__( 'CHUQUIPIONDO AI Studio', 'chuquipiondo-ai' ) . '</h1>';
	if ( ! chuquipiondo_ai_is_enabled( 'ai_compat_safe_hooks' ) ) {
		echo '<div class="notice notice-info inline"><p>' . esc_html__( 'Recomendacion: activa "Hooks seguros" en Ajustes para maximizar la compatibilidad multi-tema.', 'chuquipiondo-ai' ) . '</p></div>';
	}
	echo '<div id="chuquipiondo-ai-root" data-nonce="' . esc_attr( wp_create_nonce( 'chuquipiondo_ai_rest' ) ) . '" data-rest="' . esc_url( esc_url_raw( rest_url( 'chuquipiondo-ai/v1' ) ) ) . '"></div>';
}

/**
 * Helper: footer.
 *
 * @return void
 */
function chuquipiondo_ai_screen_footer() {
	echo '</div>';
}

/**
 * Render the AI Editor screen.
 *
 * @return void
 */
function chuquipiondo_ai_editor_page_render() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	chuquipiondo_ai_screen_header();
	?>
	<p class="description"><?php esc_html_e( 'Selecciona una Entrada o Pagina. La IA puede mejorar textos, reordenar/agregar imagenes (500x900) e insertar HTML/PHP/JS.', 'chuquipiondo-ai' ); ?></p>
	<div class="chuquipiondo-ai-editor">
		<div class="chuquipiondo-ai-editor__list">
			<div class="chuquipiondo-ai-toolbar">
				<select id="cai-post-type">
					<option value="post"><?php esc_html_e( 'Entradas', 'chuquipiondo-ai' ); ?></option>
					<option value="page" <?php disabled( ! chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ); ?>><?php esc_html_e( 'Paginas', 'chuquipiondo-ai' ); ?></option>
				</select>
				<input type="search" id="cai-search" placeholder="<?php esc_attr_e( 'Buscar...', 'chuquipiondo-ai' ); ?>" class="regular-text" />
				<button type="button" class="button" id="cai-refresh"><?php esc_html_e( 'Actualizar', 'chuquipiondo-ai' ); ?></button>
			</div>
			<div id="cai-list" class="chuquipiondo-ai-list"></div>
			<div id="cai-pager" class="chuquipiondo-ai-pager"></div>
		</div>
		<div class="chuquipiondo-ai-editor__main">
			<div id="cai-empty" class="chuquipiondo-ai-empty">
				<p><?php esc_html_e( 'Selecciona un contenido de la lista para editarlo con IA.', 'chuquipiondo-ai' ); ?></p>
			</div>
			<div id="cai-editor" class="chuquipiondo-ai-editor-form" hidden>
				<h2><input type="text" id="cai-title" class="cai-title-input" placeholder="<?php esc_attr_e( 'Titulo', 'chuquipiondo-ai' ); ?>" /></h2>

				<div class="cai-tabs">
					<button type="button" class="cai-tab is-active" data-tab="content"><?php esc_html_e( 'Contenido', 'chuquipiondo-ai' ); ?></button>
					<button type="button" class="cai-tab" data-tab="seo"><?php esc_html_e( 'SEO', 'chuquipiondo-ai' ); ?></button>
					<button type="button" class="cai-tab" data-tab="images"><?php esc_html_e( 'Imagenes', 'chuquipiondo-ai' ); ?></button>
					<button type="button" class="cai-tab" data-tab="code"><?php esc_html_e( 'Codigo', 'chuquipiondo-ai' ); ?></button>
				</div>

				<div class="cai-tabpanel" data-panel="content">
					<div class="cai-actions">
						<button type="button" class="button button-primary" data-task="improve_text"><?php esc_html_e( 'Mejorar texto', 'chuquipiondo-ai' ); ?></button>
						<button type="button" class="button" data-task="generate_paragraphs"><?php esc_html_e( 'Generar parrafos', 'chuquipiondo-ai' ); ?></button>
						<button type="button" class="button" data-task="title_ideas"><?php esc_html_e( 'Ideas de titulo', 'chuquipiondo-ai' ); ?></button>
					</div>
					<p><label><?php esc_html_e( 'Instruccion extra para la IA:', 'chuquipiondo-ai' ); ?></label><br>
						<textarea id="cai-prompt" rows="2" class="large-text"></textarea>
					</p>
					<textarea id="cai-content" class="cai-content-area" rows="20"></textarea>
				</div>

				<div class="cai-tabpanel" data-panel="seo" hidden>
					<p><label><?php esc_html_e( 'Meta descripcion (SEO):', 'chuquipiondo-ai' ); ?></label><br>
						<textarea id="cai-meta-desc" rows="3" class="large-text"></textarea>
					</p>
					<p><label><?php esc_html_e( 'Etiquetas (coma):', 'chuquipiondo-ai' ); ?></label><br>
						<input type="text" id="cai-tags" class="large-text" />
					</p>
					<p><label><?php esc_html_e( 'Slug:', 'chuquipiondo-ai' ); ?></label><br>
						<input type="text" id="cai-slug" class="large-text" />
					</p>
					<p><label><?php esc_html_e( 'Extracto:', 'chuquipiondo-ai' ); ?></label><br>
						<textarea id="cai-excerpt" rows="3" class="large-text"></textarea>
					</p>
					<div class="cai-actions">
						<button type="button" class="button" id="cai-seo-generate"><?php esc_html_e( 'Generar SEO con IA', 'chuquipiondo-ai' ); ?></button>
					</div>
				</div>

				<div class="cai-tabpanel" data-panel="images" hidden>
					<div id="cai-images-report" class="cai-images-report"></div>
					<div class="cai-actions">
						<button type="button" class="button" id="cai-analyze-images"><?php esc_html_e( 'Analizar imagenes', 'chuquipiondo-ai' ); ?></button>
						<button type="button" class="button" id="cai-resize-images"><?php esc_html_e( 'Forzar 500x900 en el contenido', 'chuquipiondo-ai' ); ?></button>
						<button type="button" class="button" id="cai-add-image"><?php esc_html_e( 'Anadir imagen IA (500x900)', 'chuquipiondo-ai' ); ?></button>
					</div>
				</div>

				<div class="cai-tabpanel" data-panel="code" hidden>
					<p><?php esc_html_e( 'Genera codigo HTML/PHP/JS listo para pegar en el contenido.', 'chuquipiondo-ai' ); ?></p>
					<p><label><?php esc_html_e( 'Lenguaje:', 'chuquipiondo-ai' ); ?>
						<select id="cai-code-lang">
							<option value="html">HTML</option>
							<option value="php">PHP</option>
							<option value="javascript">JavaScript</option>
							<option value="css">CSS</option>
						</select></label></p>
					<p><label><?php esc_html_e( 'Que debe hacer el codigo:', 'chuquipiondo-ai' ); ?></label><br>
						<textarea id="cai-code-desc" rows="3" class="large-text"></textarea></p>
					<button type="button" class="button" id="cai-generate-code"><?php esc_html_e( 'Generar codigo', 'chuquipiondo-ai' ); ?></button>
					<pre id="cai-code-out" class="cai-code-out"></pre>
					<button type="button" class="button" id="cai-insert-code"><?php esc_html_e( 'Insertar al final del contenido', 'chuquipiondo-ai' ); ?></button>
				</div>

				<div class="cai-actions cai-actions__bottom">
					<select id="cai-status">
						<option value="draft"><?php esc_html_e( 'Borrador', 'chuquipiondo-ai' ); ?></option>
						<option value="pending"><?php esc_html_e( 'Pendiente', 'chuquipiondo-ai' ); ?></option>
						<option value="publish"><?php esc_html_e( 'Publicar', 'chuquipiondo-ai' ); ?></option>
					</select>
					<button type="button" class="button button-primary" id="cai-save"><?php esc_html_e( 'Guardar', 'chuquipiondo-ai' ); ?></button>
					<button type="button" class="button" id="cai-save-view"><?php esc_html_e( 'Guardar y ver', 'chuquipiondo-ai' ); ?></button>
					<span id="cai-status-msg" class="cai-status-msg"></span>
				</div>
			</div>
		</div>
	</div>
	<?php
	chuquipiondo_ai_screen_footer();
}

/**
 * Render the "Generate article" screen (requirement 3).
 *
 * @return void
 */
function chuquipiondo_ai_generate_page_render() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	chuquipiondo_ai_screen_header();
	?>
	<p class="description"><?php esc_html_e( 'Crea un articulo nuevo con IA: titulo, contenido, imagenes 500x900, SEO, etiquetas y schema. Publicable en un clic.', 'chuquipiondo-ai' ); ?></p>
	<div class="chuquipiondo-ai-generate">
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="cai-gen-topic"><?php esc_html_e( 'Tema / idea', 'chuquipiondo-ai' ); ?></label></th>
				<td><input type="text" id="cai-gen-topic" class="large-text" placeholder="<?php esc_attr_e( 'Ej.: Beneficios del liderazgo con proposito', 'chuquipiondo-ai' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="cai-gen-title"><?php esc_html_e( 'Titulo (opcional)', 'chuquipiondo-ai' ); ?></label></th>
				<td><input type="text" id="cai-gen-title" class="large-text" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="cai-gen-type"><?php esc_html_e( 'Tipo', 'chuquipiondo-ai' ); ?></label></th>
				<td>
					<select id="cai-gen-type">
						<option value="post"><?php esc_html_e( 'Entrada', 'chuquipiondo-ai' ); ?></option>
						<option value="page" <?php disabled( ! chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ); ?>><?php esc_html_e( 'Pagina', 'chuquipiondo-ai' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="cai-gen-words"><?php esc_html_e( 'Palabras', 'chuquipiondo-ai' ); ?></label></th>
				<td><input type="number" id="cai-gen-words" class="small-text" value="800" min="200" max="3000" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="cai-gen-images"><?php esc_html_e( 'Imagenes IA', 'chuquipiondo-ai' ); ?></label></th>
				<td><input type="number" id="cai-gen-images" class="small-text" value="3" min="0" max="20" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="cai-gen-prompt"><?php esc_html_e( 'Tono / instrucciones extra', 'chuquipiondo-ai' ); ?></label></th>
				<td><textarea id="cai-gen-prompt" rows="3" class="large-text"></textarea></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Estado al crear', 'chuquipiondo-ai' ); ?></th>
				<td>
					<label><input type="radio" name="cai-gen-status" value="draft" checked /> <?php esc_html_e( 'Borrador', 'chuquipiondo-ai' ); ?></label>
					<label><input type="radio" name="cai-gen-status" value="publish" /> <?php esc_html_e( 'Publicar ahora', 'chuquipiondo-ai' ); ?></label>
				</td>
			</tr>
		</table>
		<p>
			<button type="button" class="button button-primary" id="cai-gen-run"><?php esc_html_e( 'Generar articulo con IA', 'chuquipiondo-ai' ); ?></button>
			<span id="cai-gen-status" class="cai-status-msg"></span>
		</p>
		<div id="cai-gen-result" class="cai-gen-result"></div>
	</div>
	<?php
	chuquipiondo_ai_screen_footer();
}

/**
 * Add a meta box to the classic editor with quick AI actions.
 *
 * @return void
 */
function chuquipiondo_ai_add_meta_box() {
	$types = array();
	if ( chuquipiondo_ai_is_enabled( 'ai_scope_posts' ) ) {
		$types[] = 'post';
	}
	if ( chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ) {
		$types[] = 'page';
	}
	if ( empty( $types ) ) {
		return;
	}
	foreach ( $types as $type ) {
		add_meta_box(
			'chuquipiondo-ai-mb',
			__( 'CHUQUIPIONDO AI Studio', 'chuquipiondo-ai' ),
			'chuquipiondo_ai_meta_box_render',
			$type,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'chuquipiondo_ai_add_meta_box' );

/**
 * Render the side meta box.
 *
 * @param WP_Post $post Post.
 * @return void
 */
function chuquipiondo_ai_meta_box_render( $post ) {
	wp_nonce_field( 'chuquipiondo_ai_metabox', 'chuquipiondo_ai_metabox_nonce' );
	$url = admin_url( 'admin.php?page=chuquipiondo-ai' );
	echo '<p>' . esc_html__( 'Abre este contenido en el Editor IA para mejoras completas (textos, imagenes 500x900, SEO y codigo).', 'chuquipiondo-ai' ) . '</p>';
	echo '<p><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Abrir en Editor IA', 'chuquipiondo-ai' ) . '</a></p>';
	echo '<p class="description">' . sprintf( /* translators: images count */ esc_html__( 'Imagenes detectadas en el contenido: %d', 'chuquipiondo-ai' ), (int) chuquipiondo_ai_count_images_in_content( $post->post_content ) ) . '</p>';
}

/**
 * Inject the AI-generated JSON-LD schema into the front-end <head>.
 *
 * Uses wp_head (theme-agnostic, works with Astra and any theme).
 *
 * @return void
 */
function chuquipiondo_ai_inject_schema() {
	if ( ! is_singular() ) {
		return;
	}
	if ( ! chuquipiondo_ai_is_enabled( 'ai_seo_add_schema' ) ) {
		return;
	}
	$schema = get_post_meta( get_queried_object_id(), '_chuquipiondo_ai_schema', true );
	if ( empty( $schema ) ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_strip_all_tags( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'chuquipiondo_ai_inject_schema' );
