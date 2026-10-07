<?php
/**
 * Pie del sitio: 3 diseños elegibles desde el personalizador,
 * todos full-width con contenido limitado a content_width.
 *
 * @package nechugo_News
 */
?>

<?php if ( ! nechugo_is_elementor_page() ) : ?>
</div><!-- #content -->
<?php endif; ?>

<footer id="colophon" class="site-footer nechugo-footer--full" style="margin-top: var(--nn-space-bf, 30px); min-height: <?php echo esc_attr( max( 0, (int) nechugo_get_option( 'footer_height' ) ) ); ?>px;">
	<?php if ( nechugo_is_enabled( 'footer_text_html_enable' ) && '' !== trim( (string) nechugo_get_option( 'footer_text_html' ) ) ) : ?>
		<div class="nechugo-footer-html nechugo-container">
			<?php echo nechugo_get_option( 'footer_text_html' ); // phpcs:ignore -- saneado en el personalizador; solo administradores. ?>
		</div>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/footer/variants', nechugo_get_option( 'footer_layout' ) ); ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
