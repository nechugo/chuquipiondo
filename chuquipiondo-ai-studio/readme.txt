=== CHUQUIPIONDO AI Studio ===
Contributors: nechugo
Tags: ai, artificial-intelligence, seo, content, openai, mistral, anthropic, images, editor, astra
Requires at least: 6.2
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Estudio de IA para editar Entradas y Paginas con IA: mejora textos, parrafos, SEO, etiquetas, anade HTML/PHP/JS y gestiona imagenes por defecto a 500px de alto x 900px de ancho. Compatible con multiples temas (especialmente Astra).

== Description ==

CHUQUIPIONDO AI Studio integra un motor de IA (Mistral, OpenAI, Anthropic o modo local) en el administrador de WordPress para editar y publicar contenido con ayuda de inteligencia artificial.

**Caracteristicas principales**

1. **Acceso a Entradas y Paginas** (en ese orden): navega, lee y edita el contenido. Permite modificar/editar textos, cambiar o agregar imagenes, e insertar codigo HTML, PHP, JavaScript y CSS segun la necesidad.
2. **Textos, parrafos e imagenes**: la IA mejora y reordena parrafos. Las imagenes se normalizan por defecto a **500px de alto x 900px de ancho**. Autodetecta, aparte de la primera imagen, cuantas imagenes mas podrian anadirse dentro del articulo y recomienda espacios.
3. **Publicar con IA**: genera un articulo nuevo completo con titulo, contenido, imagenes IA, meta descripcion SEO, etiquetas, slug optimizado, extracto y Schema.org JSON-LD. Publicable en un clic (o como borrador).
4. **Compatible multi-tema**: usa solo hooks agnosticos del tema (`the_content`, `wp_head`, registro de tamano de imagen) y ajustes especificos para Astra, sin generar conflictos.

**Motores de IA soportados**

* **Mistral AI** (recomendado): `mistral-large-latest`, `mistral-medium-latest`, etc.
* **OpenAI**: `gpt-4o`, `gpt-4o-mini`, etc. DALL-E 3 para imagenes.
* **Anthropic (Claude)**: `claude-3-5-sonnet-latest`, etc.
* **Modo local**: plantillas internas sin API (ideal para pruebas).

**Compatibilidad SEO**

Escribe la meta descripcion en los metas de Yoast SEO, Rank Math, AIOSEO, SEOPress y un campo propio, para que funcione aunque cambies de plugin SEO.

== Installation ==

1. Sube `chuquipiondo-ai-studio.zip` a **Plugins > Anadir nuevo > Subir plugin**.
2. Activa **CHUQUIPIONDO AI Studio**.
3. Ve a **AI Studio > Ajustes** y configura tu proveedor de IA + API key.
4. (Opcional) Ajusta las dimensiones de imagen (por defecto 500px alto x 900px ancho).
5. Usa **AI Studio > Editor IA** para editar contenido existente o **AI Studio > Generar articulo** para crear uno nuevo.

== Frequently Asked Questions ==

= ¿Necesito el tema CHUQUIPIONDO? =
No. El plugin funciona con cualquier tema, especialmente Astra. Si el tema CHUQUIPIONDO esta activo, se reconocen algunas integraciones extra, pero no es obligatorio.

= ¿Se guarda mi API key de forma segura? =
Se almacena en la base de datos de WordPress (opcion `ai_api_key`). Se recomienda servir el sitio por HTTPS. La clave nunca se muestra completa en la UI.

= ¿Las imagenes originales se modifican? =
No. El redimensionado crea derivadas; las originales se conservan. El modo ligero solo fuerza los atributos `width`/`height` en el HTML.

= ¿Pegar codigo PHP/JS es seguro? =
Es una opcion opt-in ("Codigo HTML/PHP/JS" en Ajustes). Por defecto el contenido se sanea con `wp_kses_post`.

== Changelog ==

= 1.0.0 =
* Version inicial: editor IA de Entradas y Paginas, motor IA configurable (Mistral/OpenAI/Anthropic/local), imagenes 500x900, publicacion con SEO completo y compatibilidad multi-tema (Astra).

== Upgrade Notice ==

= 1.0.0 =
Primera version de CHUQUIPIONDO AI Studio.
