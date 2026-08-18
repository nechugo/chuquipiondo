=== CHUQUIPIONDO Companion ===
Contributors: nechugo
Tags: companion, header-builder, footer-builder, mega-menu, blog-pro, ads
Requires at least: 6.2
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin companion del tema CHUQUIPIONDO (estilo Astra Pro). Header Builder, Footer Builder, Mega Menu, Blog/Revista Pro, Ads Pro y Starter Sites importables.

== Description ==

CHUQUIPIONDO Companion anade modulos premium al tema CHUQUIPIONDO:

* **Header Builder**: 3 filas configurables (topbar, main, multiuso), layouts logo-left-menu-right / logo-center-menu-split / logo-left-menu-center, hereda el sistema sticky del tema.
* **Footer Builder**: layout de 1 a 4 columnas, secciones activables (widgets, menu, copyright), hereda las zonas de ads del tema.
* **Mega Menu**: panel rico multicolumna por item, posts destacados para categorias, trigger hover/click, accesible por teclado.
* **Blog / Revista Pro**: layout magazine (destacado + grid), timeline, grid pro configurable, relacionados pro por categoria+tag, filtros por categoria y load more via AJAX.
* **Ads Pro**: modos manual / rotacion / A/B / AdSense auto, analitica de impresiones (30 dias), etiqueta configurable, hereda los slots del tema.
* **Starter Sites**: galeria de 5 sitios preconfigurados importables con un clic (aplica theme_mods + preset + contenido via CHUQUIPIONDO Core).

Cada modulo se activa/desactiva individualmente desde **Companion > Companion** en el administrador.

== Installation ==

1. Sube `chuquipiondo-companion.zip` a **Plugins > Anadir nuevo > Subir plugin**.
2. Activa **CHUQUIPIONDO Companion**.
3. Ve a **Companion > Companion** para activar los modulos.
4. Ve a **Companion > Starter Sites** para importar un sitio preconfigurado.

== Frequently Asked Questions ==

= ¿Requiere el tema CHUQUIPIONDO? =
No es obligatorio, pero los modulos de render (builders, mega menu, blog pro) dependen de los helpers del tema. Sin el tema activo se muestra un aviso.

= ¿Requiere CHUQUIPIONDO Core? =
Solo para importar contenido de los starter sites. Sin Core se aplica la configuracion del tema pero no se crea contenido de muestra.

== Changelog ==

= 1.7.0 =
* Auditoria completa: Footer Builder reescrito (sin buffer global), Mega Menu walker corregido, Blog Pro sin duplicacion de relacionados, localizacion JS y checkboxes arreglados.

= 1.6.0 =
* Version inicial: Header Builder, Footer Builder, Mega Menu, Blog/Revista Pro, Ads Pro y Starter Sites.

== Upgrade Notice ==

= 1.7.0 =
Correcciones de auditoria. Compatible con el tema CHUQUIPIONDO v1.7.0.

= 1.6.0 =
Primera version del companion. Compatible con el tema CHUQUIPIONDO v1.6.0.
