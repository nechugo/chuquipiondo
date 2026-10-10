<?php
/**
 * Pie del sitio: 3 diseños elegibles desde el personalizador,
 * todos full-width con contenido limitado a content_width.
 *
 * El div #content abierto en header.php se cierra siempre, incluidas
 * las paginas construidas con Elementor, para mantener el HTML valido.
 *
 * @package nechugo_News
 */
?>

</div><!-- #content -->

<footer id="colophon" class="site-footer nechugo-footer--full">
	<?php get_template_part( 'template-parts/footer/variants', nechugo_get_option( 'footer_layout' ) ); ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
