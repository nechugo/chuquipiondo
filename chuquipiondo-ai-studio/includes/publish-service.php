<?php
/**
 * Publish service: create a brand new article with IA, full SEO,
 * meta description, tags and properly sized images.
 *
 * Implements requirement 3 of the plugin: publish with the IA an article
 * or post that combines content editing + image management + SEO.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Publish service class.
 */
final class Chuquipiondo_AI_Publish_Service {

	/**
	 * Create (and optionally publish) an AI-generated article.
	 *
	 * Accepted params (from the REST request):
	 *  - topic (string)     Topic/idea to write about.
	 *  - title (string)     Optional explicit title.
	 *  - post_type (string) post|page (default post).
	 *  - words (int)        Target word count (default 800).
	 *  - images (int)       Number of AI images to inject (default 3).
	 *  - status (string)    draft|pending|publish (default from settings).
	 *  - categories (array) Category ids.
	 *  - tags (array)       Tag names.
	 *  - force_publish (bool) Publish even if ai_default_post_status is draft.
	 *
	 * @param array $params        Parameters.
	 * @param bool  $force_publish Force publish.
	 * @return array|WP_Error {success, id, post_url, edit_url, seo}
	 */
	public static function create( array $params, $force_publish = false ) {
		$topic    = isset( $params['topic'] ) ? sanitize_text_field( $params['topic'] ) : '';
		$title_in = isset( $params['title'] ) ? sanitize_text_field( $params['title'] ) : '';
		$post_type = isset( $params['post_type'] ) ? sanitize_key( $params['post_type'] ) : 'post';
		if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
			$post_type = 'post';
		}

		$allowed_types = array();
		if ( chuquipiondo_ai_is_enabled( 'ai_scope_posts' ) ) {
			$allowed_types[] = 'post';
		}
		if ( chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ) {
			$allowed_types[] = 'page';
		}
		if ( empty( $allowed_types ) ) {
			$allowed_types = array( 'post' );
		}
		if ( ! in_array( $post_type, $allowed_types, true ) ) {
			$post_type = $allowed_types[0];
		}

		if ( '' === $topic ) {
			return new WP_Error( 'ai_no_topic', __( 'Indica un tema para el articulo.', 'chuquipiondo-ai' ) );
		}

		$words  = isset( $params['words'] ) ? absint( $params['words'] ) : 800;
		$imgs   = isset( $params['images'] ) ? absint( $params['images'] ) : 3;
		$extra  = isset( $params['prompt'] ) ? sanitize_textarea_field( $params['prompt'] ) : '';

		$client = Chuquipiondo_AI::instance()->client;

		// 1) Title (generate if not provided).
		$title = $title_in;
		if ( '' === $title ) {
			$t = $client->run_task( 'title_ideas', $topic, '', array() );
			if ( is_wp_error( $t ) ) {
				return $t;
			}
			$lines = preg_split( '/\r?\n/', trim( (string) $t['content'] ) );
			$title = isset( $lines[0] ) ? trim( $lines[0], " \t-*0123456789. " ) : $topic;
		}

		// 2) Full article HTML with image markers.
		$article = $client->run_task(
			'full_article',
			$topic,
			$extra,
			array( 'words' => $words, 'images' => $imgs )
		);
		if ( is_wp_error( $article ) ) {
			return $article;
		}
		$content = (string) $article['content'];

		// 3) Insert as draft first (so we have an id to attach images to).
		$post_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content'  => '', // set after image handling.
				'post_type'     => $post_type,
				'post_status'   => 'draft',
				'post_author'   => get_current_user_id() ?: 1,
				'post_excerpt'  => '',
			),
			true
		);
		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		// 4) Replace AI image markers -> generated 500x900 images + featured.
		$replaced = chuquipiondo_ai_replace_image_markers( $content, $post_id );
		$content = $replaced['content'];
		$image_ids = $replaced['image_ids'];

		if ( ! empty( $image_ids ) && chuquipiondo_ai_is_enabled( 'ai_auto_featured_image' ) ) {
			set_post_thumbnail( $post_id, (int) $image_ids[0] );
		}

		// Force width/height attributes on every <img> to the target size.
		$content = chuquipiondo_ai_force_image_dimensions_in_content( $content );

		// 5) SEO: meta description, keywords, slug, excerpt.
		$seo    = $client->run_task( 'seo_meta', $content ? $content : $topic, '', array() );
		$meta_desc = '';
		$keywords  = array();
		if ( ! is_wp_error( $seo ) ) {
			$parts = preg_split( '/\r?\nKEYWORDS:\s*/i', (string) $seo['content'], 2 );
			$meta_desc = trim( $parts[0] );
			$meta_desc = mb_substr( $meta_desc, 0, (int) chuquipiondo_ai_get_int_option( 'ai_seo_meta_desc_len', 80, 320 ) );
			if ( isset( $parts[1] ) ) {
				$keywords = array_filter( array_map( 'trim', explode( ',', $parts[1] ) ) );
				$keywords = array_slice( $keywords, 0, (int) chuquipiondo_ai_get_int_option( 'ai_seo_keywords_count', 3, 30 ) );
			}
		}
		if ( '' === $meta_desc ) {
			$meta_desc = wp_strip_all_tags( $content );
			$meta_desc = mb_substr( trim( preg_replace( '/\s+/', ' ', $meta_desc ) ), 0, (int) chuquipiondo_ai_get_int_option( 'ai_seo_meta_desc_len', 80, 320 ) );
		}

		$slug = sanitize_title( $title );
		if ( chuquipiondo_ai_is_enabled( 'ai_seo_generate_slug' ) ) {
			$slug = wp_unique_post_slug( $slug, $post_id, 'draft', $post_type, 0 );
		}

		// 6) Update the post with final content + meta.
		$update = array(
			'ID'           => $post_id,
			'post_content' => chuquipiondo_ai_sanitize_content( $content ),
			'post_excerpt' => $meta_desc,
			'post_name'    => $slug,
		);
		if ( chuquipiondo_ai_is_enabled( 'ai_seo_generate_excerpt' ) ) {
			$update['post_excerpt'] = $meta_desc;
		}

		$status = $force_publish ? 'publish' : chuquipiondo_ai_get_option( 'ai_default_post_status', 'draft' );
		$update['post_status'] = $status;
		$result = wp_update_post( $update, true );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// 7) SEO meta + tags + categories.
		chuquipiondo_ai_set_meta_description( $post_id, $meta_desc );

		$tags = isset( $params['tags'] ) ? (array) $params['tags'] : array();
		if ( chuquipiondo_ai_is_enabled( 'ai_seo_generate_tags' ) && 'post' === $post_type ) {
			$tags = array_merge( $tags, $keywords );
		}
		if ( 'post' === $post_type && ! empty( $tags ) ) {
			wp_set_post_tags( $post_id, array_map( 'sanitize_text_field', $tags ), false );
		}

		if ( 'post' === $post_type && ! empty( $params['categories'] ) && is_array( $params['categories'] ) ) {
			wp_set_post_categories( $post_id, array_map( 'absint', $params['categories'] ) );
		}

		// 8) Optional JSON-LD Schema for the post (SEO technical).
		if ( chuquipiondo_ai_is_enabled( 'ai_seo_add_schema' ) ) {
			chuquipiondo_ai_add_schema_meta( $post_id, $title, $meta_desc, $image_ids );
		}

		return array(
			'success'   => true,
			'id'        => (int) $post_id,
			'title'     => $title,
			'slug'      => $slug,
			'status'    => $status,
			'post_url'  => get_permalink( $post_id ),
			'edit_url'  => get_edit_post_link( $post_id, 'raw' ),
			'image_ids' => $image_ids,
			'seo'       => array(
				'meta_description' => $meta_desc,
				'keywords'         => $keywords,
				'tags'             => $tags,
			),
		);
	}

	/**
	 * Persist a simple Article JSON-LD schema as post meta (SEO technical).
	 *
	 * @param int    $post_id   Post id.
	 * @param string $title     Title.
	 * @param string $desc      Description.
	 * @param array  $image_ids Image attachment ids.
	 * @return void
	 */
	private static function add_schema_meta( $post_id, $title, $desc, array $image_ids ) {
		$image_url = '';
		foreach ( $image_ids as $id ) {
			$url = wp_get_attachment_url( (int) $id );
			if ( $url ) {
				$image_url = $url;
				break;
			}
		}
		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => $title,
			'description'      => $desc,
			'datePublished'    => get_the_date( 'c', $post_id ),
			'dateModified'     => get_the_modified_date( 'c', $post_id ),
			'author'            => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			),
		);
		if ( '' !== $image_url ) {
			$schema['image'] = $image_url;
		}
		update_post_meta( $post_id, '_chuquipiondo_ai_schema', wp_json_encode( $schema ) );
	}
}
