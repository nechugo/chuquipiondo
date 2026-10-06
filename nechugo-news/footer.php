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

<footer id="colophon" class="site-footer nechugo-footer--full" style="margin-top: var(--nn-space-bf, 30px);">
	<?php get_template_part( 'template-parts/footer/variants', nechugo_get_option( 'footer_layout' ) ); ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
