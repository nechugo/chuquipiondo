<?php
/**
 * Image service: enforce the default 500px (height) x 900px (width) image
 * size, generate images via AI (DALL·E / Pollinations) and attach them to
 * posts, replacing `<!--AI_IMAGE:desc-->` markers inside AI-generated
 * articles.
 *
 * The resize uses the available engine (Imagick preferred, GD fallback).
 * It NEVER touches the original upload; it produces a derivative file in
 * the same uploads directory and returns its URL/ID.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the configured target dimensions.
 *
 * @return array {width, height}
 */
function chuquipiondo_ai_image_target_size() {
	return array(
		'width'  => (int) chuquipiondo_ai_get_int_option( 'ai_image_width', 1, 5000 ),
		'height' => (int) chuquipiondo_ai_get_int_option( 'ai_image_height', 1, 5000 ),
	);
}

/**
 * Check whether a local image engine is available.
 *
 * @return string 'imagick' | 'gd' | ''
 */
function chuquipiondo_ai_image_engine() {
	if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick', false ) ) {
		return 'imagick';
	}
	if ( function_exists( 'imagecreatetruecolor' ) ) {
		return 'gd';
	}
	return '';
}

/**
 * Resize/crop a local file to the exact target dimensions.
 *
 * @param string $source Absolute path to source image.
 * @param int    $target_w Target width.
 * @param int    $target_h Target height.
 * @param string $dest    Absolute path for the destination file.
 * @return bool|WP_Error True on success.
 */
function chuquipiondo_ai_resize_image( $source, $target_w, $target_h, $dest ) {
	if ( ! file_exists( $source ) ) {
		return new WP_Error( 'ai_img_src', __( 'Imagen de origen no encontrada.', 'chuquipiondo-ai' ) );
	}
	$engine = chuquipiondo_ai_image_engine();
	if ( '' === $engine ) {
		return new WP_Error( 'ai_img_engine', __( 'No hay motor de imagen (Imagick ni GD).', 'chuquipiondo-ai' ) );
	}

	if ( 'imagick' === $engine ) {
		try {
			$img = new Imagick( $source );
			$img->setImageCompressionQuality( (int) chuquipiondo_ai_get_int_option( 'ai_image_quality', 50, 100 ) );
			$img->cropThumbnailImage( (int) $target_w, (int) $target_h );
			$img->writeImage( $dest );
			$img->destroy();
			return true;
		} catch ( Exception $e ) {
			return new WP_Error( 'ai_img_imagick', $e->getMessage() );
		}
	}

	// GD fallback.
	$info = @getimagesize( $source );
	if ( empty( $info ) ) {
		return new WP_Error( 'ai_img_info', __( 'No se pudo leer la imagen.', 'chuquipiondo-ai' ) );
	}
	switch ( $info[2] ) {
		case IMAGETYPE_JPEG:
			$src = imagecreatefromjpeg( $source );
			break;
		case IMAGETYPE_PNG:
			$src = imagecreatefrompng( $source );
			break;
		case IMAGETYPE_GIF:
			$src = imagecreatefromgif( $source );
			break;
		case IMAGETYPE_WEBP:
			if ( function_exists( 'imagecreatefromwebp' ) ) {
				$src = imagecreatefromwebp( $source );
				break;
			}
			return new WP_Error( 'ai_img_webp', __( 'WEBP no soportado por GD.', 'chuquipiondo-ai' ) );
		default:
			return new WP_Error( 'ai_img_type', __( 'Tipo de imagen no soportado.', 'chuquipiondo-ai' ) );
	}
	if ( ! is_resource( $src ) && ! $src instanceof \GdImage ) {
		return new WP_Error( 'ai_img_gd', __( 'GD no pudo cargar la imagen.', 'chuquipiondo-ai' ) );
	}

	$sw = imagesx( $src );
	$sh = imagesy( $src );
	// Crop to aspect ratio first, then resize (cover behavior).
	$target_ratio = $target_w / $target_h;
	$src_ratio    = $sw / $sh;
	if ( $src_ratio > $target_ratio ) {
		$crop_w = (int) ( $sh * $target_ratio );
		$crop_h = $sh;
		$crop_x = (int) ( ( $sw - $crop_w ) / 2 );
		$crop_y = 0;
	} else {
		$crop_w = $sw;
		$crop_h = (int) ( $sw / $target_ratio );
		$crop_x = 0;
		$crop_y = (int) ( ( $sh - $crop_h ) / 2 );
	}
	$dst = imagecreatetruecolor( $target_w, $target_h );
	if ( IMAGETYPE_PNG === $info[2] || IMAGETYPE_GIF === $info[2] ) {
		imagealphablending( $dst, false );
		imagesavealpha( $dst, true );
	}
	imagecopyresampled( $dst, $src, 0, 0, $crop_x, $crop_y, $target_w, $target_h, $crop_w, $crop_h );

	$ok = false;
	$ext = strtolower( pathinfo( $dest, PATHINFO_EXTENSION ) ) === 'jpg' ? 'jpeg' : strtolower( pathinfo( $dest, PATHINFO_EXTENSION ) );
	switch ( $ext ) {
		case 'jpeg':
		case 'jpg':
			$ok = imagejpeg( $dst, $dest, (int) chuquipiondo_ai_get_int_option( 'ai_image_quality', 50, 100 ) );
			break;
		case 'png':
			$ok = imagepng( $dst, $dest, 6 );
			break;
		case 'webp':
			$ok = function_exists( 'imagewebp' ) ? imagewebp( $dst, $dest, (int) chuquipiondo_ai_get_int_option( 'ai_image_quality', 50, 100 ) ) : false;
			break;
		default:
			$ok = imagejpeg( $dst, $dest, (int) chuquipiondo_ai_get_int_option( 'ai_image_quality', 50, 100 ) );
	}
	imagedestroy( $src );
	imagedestroy( $dst );
	return $ok ? true : new WP_Error( 'ai_img_write', __( 'No se pudo guardar la imagen redimensionada.', 'chuquipiondo-ai' ) );
}

/**
 * Attach (or re-attach) a sized copy of an attachment to the media library
 * and return its attachment id + url. The original attachment is preserved.
 *
 * @param int    $attachment_id Source attachment id.
 * @param int    $post_id       Post to attach to.
 * @return array|WP_Error {id, url}
 */
function chuquipiondo_ai_attach_sized_image( $attachment_id, $post_id = 0 ) {
	$path = get_attached_file( $attachment_id );
	if ( ! $path || ! file_exists( $path ) ) {
		return new WP_Error( 'ai_img_attach', __( 'El archivo adjunto no existe.', 'chuquipiondo-ai' ) );
	}
	$size  = chuquipiondo_ai_image_target_size();
	$ext   = pathinfo( $path, PATHINFO_EXTENSION );
	$suffix = '-' . $size['width'] . 'x' . $size['height'];
	$dest  = preg_replace( '/\.' . preg_quote( $ext, '/' ) . '$/', $suffix . '.' . $ext, $path );

	// If already produced, reuse.
	if ( ! file_exists( $dest ) ) {
		$result = chuquipiondo_ai_resize_image( $path, $size['width'], $size['height'], $dest );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
	}

	// Register as a new attachment so it appears in the library.
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$new_id = wp_insert_attachment(
		array(
			'post_title'   => get_the_title( $attachment_id ),
			'post_content' => '',
			'post_status'  => 'inherit',
			'post_mime_type' => mime_content_type( $dest ),
			'post_parent'  => $post_id,
		),
		$dest,
		$post_id
	);
	if ( is_wp_error( $new_id ) ) {
		return $new_id;
	}
	$meta = wp_generate_attachment_metadata( $new_id, $dest );
	wp_update_attachment_metadata( $new_id, $meta );

	return array(
		'id'  => (int) $new_id,
		'url' => wp_get_attachment_url( $new_id ),
	);
}

/**
 * Force an existing <img> in content to the 500x900 size by adding
 * width/height attributes + a srcset to the AI image size when available.
 *
 * This is the lightweight, no-recrop path used when auto-resize is on but
 * we don't want to duplicate media files.
 *
 * @param string $content Post content.
 * @return string
 */
function chuquipiondo_ai_force_image_dimensions_in_content( $content ) {
	if ( ! chuquipiondo_ai_is_enabled( 'ai_image_auto_resize' ) ) {
		return $content;
	}
	$size = chuquipiondo_ai_image_target_size();
	$w    = $size['width'];
	$h    = $size['height'];

	$callback = function ( $match ) use ( $w, $h ) {
		$tag = $match[0];
		// Set/replace width.
		$tag = preg_match( '/\swidth=/i', $tag )
			? preg_replace( '/\swidth=(["\'])\d+\1/i', ' width="$1' . $w . '$1"', $tag )
			: preg_replace( '/<img\b/i', '<img width="' . $w . '"', $tag );
		// Set/replace height.
		$tag = preg_match( '/\sheight=/i', $tag )
			? preg_replace( '/\sheight=(["\'])\d+\1/i', ' height="$1' . $h . '$1"', $tag )
			: preg_replace( '/<img\b/i', '<img height="' . $h . '"', $tag );
		// Ensure loading=lazy.
		if ( ! preg_match( '/\sloading=/i', $tag ) ) {
			$tag = preg_replace( '/<img\b/i', '<img loading="lazy"', $tag );
		}
		return $tag;
	};

	return preg_replace_callback( '/<img\b[^>]*>/i', $callback, $content );
}

/**
 * Generate an image via the configured provider and return an array with
 * the (eventually resized) attachment id + url.
 *
 * Currently supports Pollinations (free, no key) and OpenAI DALL·E.
 * Falls back to a placeholder.
 *
 * @param string $description Textual prompt for the image.
 * @param int    $post_id    Post to attach to.
 * @return array|WP_Error {id, url, placeholder:bool}
 */
function chuquipiondo_ai_generate_image( $description, $post_id = 0 ) {
	$provider = chuquipiondo_ai_get_option( 'ai_image_provider', 'local' );
	$size     = chuquipiondo_ai_image_target_size();
	$prompt   = rawurlencode( (string) $description );
	$remote   = '';
	$placeholder = false;

	if ( 'pollinations' === $provider || 'local' === $provider ) {
		// Pollinations: free, no key. Returns a real image.
		$remote = 'https://image.pollinations.ai/prompt/' . $prompt . '?width=' . $size['width'] . '&height=' . $size['height'] . '&nologo=true';
	} elseif ( 'openai-dalle' === $provider ) {
		$api_key = (string) chuquipiondo_ai_get_option( 'ai_api_key', '' );
		if ( '' === $api_key ) {
			$remote = 'https://placehold.co/' . $size['width'] . 'x' . $size['height'] . '?text=' . rawurlencode( $description );
			$placeholder = true;
		} else {
			$body = array(
				'model'  => 'dall-e-3',
				'prompt' => (string) $description,
				'n'      => 1,
				'size'   => '1024x1024',
			);
			$resp = wp_remote_post(
				'https://api.openai.com/v1/images/generations',
				array(
					'timeout' => 90,
					'headers' => array(
						'Authorization' => 'Bearer ' . $api_key,
						'Content-Type'  => 'application/json',
					),
					'body'    => wp_json_encode( $body ),
				)
			);
			if ( is_wp_error( $resp ) ) {
				return $resp;
			}
			$data = json_decode( wp_remote_retrieve_body( $resp ), true );
			$remote = isset( $data['data'][0]['url'] ) ? $data['data'][0]['url'] : '';
		}
	}

	if ( '' === $remote ) {
		$remote = 'https://placehold.co/' . $size['width'] . 'x' . $size['height'] . '?text=' . rawurlencode( $description );
		$placeholder = true;
	}

	// Download into the uploads dir + register attachment.
	$tmp = download_url( $remote );
	if ( is_wp_error( $tmp ) ) {
		return $tmp;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$file_array = array(
		'name'     => 'chuquipiondo-ai-' . wp_generate_password( 6, false ) . '.jpg',
		'tmp_name' => $tmp,
	);
	if ( ! file_exists( $tmp ) ) {
		return new WP_Error( 'ai_img_download', __( 'No se pudo descargar la imagen generada.', 'chuquipiondo-ai' ) );
	}

	$id = media_handle_sideload( $file_array, $post_id, sanitize_text_field( $description ) );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return $id;
	}

	// Resize to exact 500x900 (cover crop) when a local engine exists.
	if ( ! $placeholder && chuquipiondo_ai_image_engine() ) {
		$orig = get_attached_file( $id );
		if ( $orig && file_exists( $orig ) ) {
			chuquipiondo_ai_resize_image( $orig, $size['width'], $size['height'], $orig );
			$meta = wp_generate_attachment_metadata( $id, $orig );
			wp_update_attachment_metadata( $id, $meta );
		}
	}

	return array(
		'id'        => (int) $id,
		'url'       => wp_get_attachment_url( $id ),
		'placeholder' => $placeholder,
	);
}

/**
 * Replace every `<!--AI_IMAGE:description-->` marker inside an article
 * with a generated sized image and return the updated content + ids.
 *
 * @param string $content Post content.
 * @param int    $post_id Post id.
 * @return array {content, image_ids}
 */
function chuquipiondo_ai_replace_image_markers( $content, $post_id ) {
	$ids = array();
	$count = 0;
	$limit = (int) chuquipiondo_ai_get_int_option( 'ai_max_generated_images', 1, 20 );

	$content = preg_replace_callback(
		'/<!--AI_IMAGE:(.*?)-->/i',
		function ( $m ) use ( $post_id, &$ids, &$count, $limit ) {
			$count++;
			if ( $count > $limit ) {
				return '';
			}
			$desc = trim( $m[1] );
			$result = chuquipiondo_ai_generate_image( $desc, $post_id );
			if ( is_wp_error( $result ) ) {
				return '';
			}
			$ids[] = (int) $result['id'];
			$alt   = esc_attr( $desc );
			$size  = chuquipiondo_ai_image_target_size();
			return '<figure class="wp-block-image size-chuquipiondo-ai"><img src="' . esc_url( $result['url'] ) . '" alt="' . $alt . '" width="' . (int) $size['width'] . '" height="' . (int) $size['height'] . '" loading="lazy" /></figure>';
		},
		$content
	);

	return array(
		'content'   => $content,
		'image_ids' => $ids,
	);
}
