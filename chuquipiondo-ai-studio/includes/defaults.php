<?php
/**
 * Default options for the CHUQUIPIONDO AI Studio plugin.
 *
 * Single source of truth for every AI option. Mirrors the pattern used by
 * the theme (inc/defaults.php), the core and the companion plugin.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the full AI defaults map.
 *
 * @return array
 */
function chuquipiondo_ai_defaults() {
	return array(
		// ===== AI Provider / Engine =====
		'ai_provider'            => 'mistral',   // mistral | openai | anthropic | local
		'ai_api_key'             => '',          // stored encrypted-ish; never displayed in full
		'ai_model'               => 'mistral-large-latest',
		'ai_temperature'         => '0.7',
		'ai_max_tokens'          => '4096',
		'ai_language'            => 'es',
		'ai_timeout'             => '60',

		// ===== Default image dimensions (requirement: 500px alto x 900px ancho) =====
		'ai_image_height'        => '500',
		'ai_image_width'         => '900',
		'ai_image_auto_resize'   => '1',         // force 500x900 on every managed image
		'ai_image_provider'      => 'local',     // local | openai-dalle | pollinations
		'ai_image_quality'       => '85',
		'ai_image_alt_lang'      => 'es',
		'ai_max_generated_images' => '6',        // max images auto-generated per article

		// ===== Content access scope =====
		'ai_scope_posts'         => '1',         // Entradas (first option per requirement)
		'ai_scope_pages'         => '1',         // Paginas (second option per requirement)
		'ai_allowed_html'        => '1',         // allow pasting raw HTML/PHP/JS blocks
		'ai_default_post_status' => 'draft',     // draft | pending | publish

		// ===== Publishing / SEO defaults =====
		'ai_seo_meta_desc_len'   => '160',
		'ai_seo_generate_tags'    => '1',
		'ai_seo_generate_excerpt' => '1',
		'ai_seo_generate_slug'    => '1',
		'ai_seo_keywords_count'   => '8',
		'ai_seo_add_schema'       => '1',
		'ai_auto_featured_image'  => '1',

		// ===== Compat / safety =====
		'ai_compat_safe_hooks'   => '1',         // use only theme-agnostic hooks
		'ai_compat_astra'        => '1',         // enable Astra-specific tweaks
		'ai_log_requests'        => '0',         // log API calls (masked) for debugging

		// ===== Internal =====
		'chuquipiondo_ai_version' => CHUQUIPIONDO_AI_VERSION,
	);
}

/**
 * Available AI providers and their default endpoints/models.
 *
 * @return array
 */
function chuquipiondo_ai_providers() {
	return array(
		'mistral'  => array(
			'label'   => __( 'Mistral AI (recomendado)', 'chuquipiondo-ai' ),
			'endpoint' => 'https://api.mistral.ai/v1/chat/completions',
			'models'  => array( 'mistral-large-latest', 'mistral-medium-latest', 'mistral-small-latest', 'open-mistral-nemo' ),
			'image'   => false,
		),
		'openai'   => array(
			'label'   => __( 'OpenAI (GPT-4 / GPT-4o)', 'chuquipiondo-ai' ),
			'endpoint' => 'https://api.openai.com/v1/chat/completions',
			'models'  => array( 'gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo' ),
			'image'   => true,
		),
		'anthropic' => array(
			'label'   => __( 'Anthropic (Claude)', 'chuquipiondo-ai' ),
			'endpoint' => 'https://api.anthropic.com/v1/messages',
			'models'  => array( 'claude-3-5-sonnet-latest', 'claude-3-5-haiku-latest', 'claude-3-opus-latest' ),
			'image'   => false,
		),
		'local'    => array(
			'label'   => __( 'Modo local (sin API, plantillas)', 'chuquipiondo-ai' ),
			'endpoint' => '',
			'models'  => array( 'local-template' ),
			'image'   => false,
		),
	);
}
