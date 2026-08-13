<?php
/**
 * Custom widgets shipped with the theme.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme widgets.
 */
function chuquipiondo_register_widgets() {
	register_widget( 'Chuquipiondo_About_Widget' );
	register_widget( 'Chuquipiondo_Social_Widget' );
}
add_action( 'widgets_init', 'chuquipiondo_register_widgets' );

/**
 * About widget — short bio with avatar and links.
 */
class Chuquipiondo_About_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'chuquipiondo_about',
			__( 'CHUQUIPIONDO: Sobre', 'chuquipiondo' ),
			array( 'description' => __( 'Bloque de autor / bio corto.', 'chuquipiondo' ) )
		);
	}

	public function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}
		$text = isset( $instance['text'] ) ? $instance['text'] : '';
		echo '<p class="chuqui-about-text">' . esc_html( $text ) . '</p>';
		echo wp_kses_post( $args['after_widget'] );
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Sobre Nelson', 'chuquipiondo' );
		$text  = isset( $instance['text'] ) ? $instance['text'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titulo:', 'chuquipiondo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Texto:', 'chuquipiondo' ); ?></label>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array(
			'title' => sanitize_text_field( $new['title'] ),
			'text'  => sanitize_textarea_field( $new['text'] ),
		);
	}
}

/**
 * Social profiles widget.
 */
class Chuquipiondo_Social_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'chuquipiondo_social',
			__( 'CHUQUIPIONDO: Redes', 'chuquipiondo' ),
			array( 'description' => __( 'Iconos de redes sociales configuradas.', 'chuquipiondo' ) )
		);
	}

	public function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}
		chuquipiondo_social_profiles_links();
		echo wp_kses_post( $args['after_widget'] );
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Siguenos', 'chuquipiondo' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titulo:', 'chuquipiondo' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ) );
	}
}
