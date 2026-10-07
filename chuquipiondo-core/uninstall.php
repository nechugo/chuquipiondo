<?php
/**
 * Uninstall CHUQUIPIONDO Core.
 *
 * Removes only this plugin's own options. Does NOT touch posts, pages,
 * media, menus, widgets, theme_mods or any other plugin's data: demo
 * content created by the importer is site content and belongs to the user.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'chuquipiondo_setup_done' );
delete_option( 'chuquipiondo_core_version' );
