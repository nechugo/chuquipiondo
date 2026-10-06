<?php
/**
 * Widgets personalizados del tema.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget: caja de anuncio.
 */
class Nechugo_Ad_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'nechugo_ad_widget',
			__( 'nechugo News: Caja de anuncio', 'nechugo-news' ),
			array( 'description' => __( 'Muestra un bloque de anuncio (AdSense, banner HTML).', 'nechugo-news' ) )
		);
	}

	public function widget( $args, $instance ) {
		$code = isset( $instance['code'] ) ? $instance['code'] : '';
		if ( '' === trim( (string) $code ) ) {
			return;
		}
		echo $args['before_widget']; // phpcs:ignore
		echo '<div class="nechugo-ad nechugo-ad--widget">';
		echo $code; // phpcs:ignore -- solo administradores con unfiltered_html.
		echo '</div>';
		echo $args['after_widget']; // phpcs:ignore
	}

	public function form( $instance ) {
		$code = isset( $instance['code'] ) ? $instance['code'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>"><?php esc_html_e( 'Codigo del anuncio:', 'nechugo-news' ); ?></label>
			<textarea class="widefat" rows="6" id="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'code' ) ); ?>"><?php echo esc_textarea( $code ); ?></textarea>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['code']  = current_user_can( 'unfiltered_html' ) ? $new_instance['code'] : strip_tags( $new_instance['code'] );
		return $instance;
	}
}

/**
 * Registro de widgets del tema.
 */
function nechugo_register_widgets() {
	register_widget( 'Nechugo_Ad_Widget' );
}
add_action( 'widgets_init', 'nechugo_register_widgets', 20 );
