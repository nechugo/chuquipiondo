<?php
/**
 * Extra widgets for the CHUQUIPIONDO Core plugin.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register extra widgets.
 */
function chuquipiondo_core_register_widgets() {
	register_widget( 'Chuquipiondo_Core_Recent_Posts_Widget' );
	register_widget( 'Chuquipiondo_Core_Tabs_Widget' );
	register_widget( 'Chuquipiondo_Core_Stats_Widget' );
}
add_action( 'widgets_init', 'chuquipiondo_core_register_widgets' );

/**
 * Recent Posts widget with thumbnail.
 */
class Chuquipiondo_Core_Recent_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'chuquipiondo_core_recent_posts',
			__( 'CHUQUIPIONDO: Articulos recientes', 'chuquipiondo-core' ),
			array( 'description' => __( 'Articulos recientes con miniatura.', 'chuquipiondo-core' ) )
		);
	}

	public function widget( $args, $instance ) {
		$count = isset( $instance['count'] ) ? (int) $instance['count'] : 5;

		$q = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => 1,
		) );

		if ( ! $q->have_posts() ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}

		echo '<ul class="chuqui-recent-posts">';
		while ( $q->have_posts() ) {
			$q->the_post();
			echo '<li class="chuqui-recent-post">';
			if ( has_post_thumbnail() ) {
				echo '<a href="' . esc_url( get_permalink() ) . '" class="chuqui-recent-post__thumb">' . get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'loading' => 'lazy' ) ) . '</a>';
			}
			echo '<div class="chuqui-recent-post__body">';
			echo '<h4 class="chuqui-recent-post__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
			echo '<span class="chuqui-recent-post__date">' . esc_html( get_the_date() ) . '</span>';
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';

		echo wp_kses_post( $args['after_widget'] );
		wp_reset_postdata();
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Recientes', 'chuquipiondo-core' );
		$count = isset( $instance['count'] ) ? $instance['count'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titulo:', 'chuquipiondo-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Cantidad:', 'chuquipiondo-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" min="1" max="20">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array(
			'title' => sanitize_text_field( $new['title'] ),
			'count' => absint( $new['count'] ),
		);
	}
}

/**
 * Tabs widget: recientes + populares + comentarios.
 */
class Chuquipiondo_Core_Tabs_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'chuquipiondo_core_tabs',
			__( 'CHUQUIPIONDO: Pestañas (recientes/populares)', 'chuquipiondo-core' ),
			array( 'description' => __( 'Widget con pestanas de articulos recientes y populares.', 'chuquipiondo-core' ) )
		);
	}

	public function widget( $args, $instance ) {
		$count = isset( $instance['count'] ) ? (int) $instance['count'] : 5;

		$recent = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => 1,
		) );

		$popular = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'orderby'             => 'comment_count',
			'order'               => 'DESC',
			'ignore_sticky_posts' => 1,
		) );

		echo wp_kses_post( $args['before_widget'] );

		echo '<div class="chuqui-tabs-widget">';
		echo '<ul class="chuqui-tabs__nav">';
		echo '<li class="active"><a href="#chuqui-tab-recent">' . esc_html__( 'Recientes', 'chuquipiondo-core' ) . '</a></li>';
		echo '<li><a href="#chuqui-tab-popular">' . esc_html__( 'Populares', 'chuquipiondo-core' ) . '</a></li>';
		echo '</ul>';

		echo '<div class="chuqui-tabs__content">';
		echo '<div id="chuqui-tab-recent" class="chuqui-tab active">';
		if ( $recent->have_posts() ) {
			echo '<ul class="chuqui-tab-list">';
			while ( $recent->have_posts() ) {
				$recent->the_post();
				echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</div>';

		echo '<div id="chuqui-tab-popular" class="chuqui-tab" style="display:none;">';
		if ( $popular->have_posts() ) {
			echo '<ul class="chuqui-tab-list">';
			while ( $popular->have_posts() ) {
				$popular->the_post();
				echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';

		echo wp_kses_post( $args['after_widget'] );
		wp_reset_postdata();
	}

	public function form( $instance ) {
		$count = isset( $instance['count'] ) ? $instance['count'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Cantidad por pestana:', 'chuquipiondo-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" min="1" max="10">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array( 'count' => absint( $new['count'] ) );
	}
}

/**
 * Stats widget: total posts, categories, comments.
 */
class Chuquipiondo_Core_Stats_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'chuquipiondo_core_stats',
			__( 'CHUQUIPIONDO: Estadisticas', 'chuquipiondo-core' ),
			array( 'description' => __( 'Muestra estadisticas del sitio.', 'chuquipiondo-core' ) )
		);
	}

	public function widget( $args, $instance ) {
		$count_posts = wp_count_posts();
		$posts       = $count_posts->publish;
		$cats        = wp_count_terms( 'category' );
		$tags        = wp_count_terms( 'post_tag' );
		$comments    = wp_count_comments();

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}

		echo '<ul class="chuqui-stats">';
		/* translators: %d: post count */
		echo '<li><span class="chuqui-stats__num">' . esc_html( number_format_i18n( $posts ) ) . '</span><span class="chuqui-stats__label">' . esc_html__( 'Articulos', 'chuquipiondo-core' ) . '</span></li>';
		/* translators: %d: category count */
		echo '<li><span class="chuqui-stats__num">' . esc_html( number_format_i18n( $cats ) ) . '</span><span class="chuqui-stats__label">' . esc_html__( 'Categorias', 'chuquipiondo-core' ) . '</span></li>';
		/* translators: %d: comment count */
		echo '<li><span class="chuqui-stats__num">' . esc_html( number_format_i18n( $comments->approved ) ) . '</span><span class="chuqui-stats__label">' . esc_html__( 'Comentarios', 'chuquipiondo-core' ) . '</span></li>';
		echo '</ul>';

		echo wp_kses_post( $args['after_widget'] );
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Estadisticas', 'chuquipiondo-core' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titulo:', 'chuquipiondo-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array( 'title' => sanitize_text_field( $new['title'] ) );
	}
}
