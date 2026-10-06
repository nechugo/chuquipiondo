<?php
/**
 * Header 4 - Compacto: logo + menu + busqueda en una sola barra.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="nechugo-header-main header--compact">
	<div class="nechugo-container header__inner header__inner--split">
		<div class="header__brand">
			<?php nechugo_site_logo(); ?>
			<?php nechugo_menu_toggle(); ?>
		</div>
		<div class="header__nav">
			<?php nechugo_nav_menu( 'primary' ); ?>
		</div>
		<div class="header__tools">
			<?php nechugo_search_toggle(); ?>
		</div>
	</div>
</div>
