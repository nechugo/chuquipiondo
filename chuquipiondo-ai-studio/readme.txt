=== CHUQUIPIONDO AI Studio ===
Contributors: nechugo
Tags: ai, artificial-intelligence, seo, content, openai, mistral, anthropic, images, editor, astra
Requires at least: 6.2
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.11.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Estudio de IA para editar Entradas y Paginas con IA: mejora textos, parrafos, SEO, etiquetas, anade HTML/PHP/JS y gestiona imagenes por defecto a 500px de alto x 900px de ancho. Compatible con multiples temas (especialmente Astra).

== Description ==

CHUQUIPIONDO AI Studio integra un motor de IA (Mistral, OpenAI, Anthropic o modo local) en el administrador de WordPress para editar y publicar contenido con ayuda de inteligencia artificial.

**Caracteristicas principales**

1. **Acceso a Entradas y Paginas**: navega, lee y edita contenido respetando las capacidades del usuario sobre cada entrada o pagina.
2. **Textos, parrafos e imagenes**: la IA mejora y reordena parrafos. Las imagenes se normalizan por defecto a **500px de alto x 900px de ancho**.
3. **Publicar con IA**: genera articulos con titulo, contenido, imagenes IA, meta descripcion SEO, etiquetas, slug, extracto y Schema.org JSON-LD. La publicacion requiere la capacidad correspondiente de WordPress.
4. **Codigo HTML/PHP/JS**: es opt-in y el contenido sin filtrar solo se conserva para usuarios con `unfiltered_html`.
5. **Compatible multi-tema**: funciona con CHUQUIPIONDO, Astra y otros temas que respeten los hooks de WordPress.

**Motores de IA soportados**

* **Mistral AI**
* **OpenAI**
* **Anthropic (Claude)**
* **Modo local**

**Compatibilidad SEO**

Escribe la meta descripcion en los campos compatibles de Yoast SEO, Rank Math, AIOSEO, SEOPress y un campo propio.

== Installation ==

1. Sube `chuquipiondo-ai-studio.zip` a **Plugins > Anadir nuevo > Subir plugin**.
2. Activa **CHUQUIPIONDO AI Studio**.
3. Ve a **AI Studio > Ajustes** y configura el proveedor de IA y la API key.
4. Usa **AI Studio > Editor IA** o **AI Studio > Generar articulo**.

== Frequently Asked Questions ==

= ¿Necesito el tema CHUQUIPIONDO? =
No. El plugin funciona con otros temas compatibles con WordPress.

= ¿Como se trata mi API key? =
Se almacena en la base de datos de WordPress. Desde la version 1.11.0 la clave almacenada no se inserta completa en el HTML de la pagina de ajustes y enviar el campo vacio conserva la clave existente.

= ¿Las imagenes originales se modifican? =
El servicio puede trabajar con derivadas y normaliza las dimensiones declaradas en el contenido. Se recomienda conservar copias de seguridad antes de operaciones masivas.

= ¿Pegar codigo PHP/JS es seguro? =
Es una funcion privilegiada. En la version 1.11.0 requiere que la opcion este habilitada y que el usuario disponga de la capacidad `unfiltered_html`.

== Changelog ==

= 1.11.0 =
* Corrige autenticacion REST usando el nonce canonico `wp_rest`.
* Agrega controles por objeto para lectura y edicion de entradas y paginas.
* Exige capacidades de publicacion antes de cambiar contenido a publicado o privado.
* Restringe HTML/PHP/JS sin filtrar a usuarios con `unfiltered_html`.
* Evita exponer la API key completa en el DOM de la pagina de ajustes.
* Corrige la normalizacion de atributos width/height de imagenes.

= 1.0.0 =
* Version inicial: editor IA, motores configurables, imagenes 500x900 y publicacion con SEO.

== Upgrade Notice ==

= 1.11.0 =
Actualizacion recomendada por endurecimiento de permisos, autenticacion REST y manejo de secretos.
