# CHUQUIPIONDO WordPress Theme

> **¡Juntos, sí podemos!** — Liderazgo, Gestión y Formación con propósito.

Un tema WordPress profesional, ligero, elegante y **real** (instalable y administrable), creado específicamente para Nelson Chuquipiondo. Es un ecosistema digital que integra de forma coherente: **Fe Cristiana, Música, Artículos, Reflexiones, Liderazgo, Gestión, Formación y Recursos Audiovisuales.**

- **Tema padre:** `chuquipiondo-theme`
- **Tema hijo:** `chuquipiondo-child`
- **Versión:** 1.6.0
- **Requiere WordPress:** 6.2+
- **Requiere PHP:** 7.4+
- **Autor:** Nelson Chuquipiondo

---

## Tabla de contenidos

1. [Instalación](#instalación)
2. [Estructura de archivos](#estructura-de-archivos)
3. [Sistema de diseño (CSS)](#sistema-de-diseño-css)
4. [Customizer](#customizer)
5. [Sistema de cabecera (Header)](#sistema-de-cabecera-header)
6. [Hero / Slider](#hero--slider)
7. [Home Builder](#home-builder)
8. [Blog y archivos](#blog-y-archivos)
9. [Artículo individual (Single)](#artículo-individual-single)
10. [Publicidad (Ads)](#publicidad-ads)
11. [Redes sociales (Share)](#redes-sociales-share)
12. [WhatsApp flotante](#whatsapp-flotante)
13. [Módulo de música](#módulo-de-música)
14. [Panel de opciones avanzado](#panel-de-opciones-avanzado)
15. [Importar / Exportar / Presets](#importar--exportar--presets)
16. [Hooks y filtros](#hooks-y-filtros)
17. [Widgets y zonas](#widgets-y-zonas)
18. [Zonas de anuncios](#zonas-de-anuncios)
19. [Seguridad y buenas prácticas](#seguridad-y-buenas-prácticas)
20. [Integraciones](#integraciones)
21. [SEO y rendimiento](#seo-y-rendimiento)
22. [Actualización](#actualización)

---

## Instalación

### Opción 1: ZIP (recomendado)

1. Sube `chuquipiondo-theme.zip` a **Apariencia > Temas > Añadir nuevo > Subir tema**.
2. Activa el tema **CHUQUIPIONDO**.
3. (Opcional) Sube y activa `chuquipiondo-child.zip` para personalizaciones seguras.

### Opción 2: FTP

1. Descomprime `chuquipiondo-theme.zip` en `wp-content/themes/`.
2. La carpeta debe llamarse `chuquipiondo-theme`.
3. Activa el tema desde el administrador.

### Después de activar

1. Ve a **Apariencia > Personalizar** para configurar colores, tipografía, cabecera, hero, blog, ads, social, WhatsApp, música y código personalizado.
2. Ve a **CHUQUIPIONDO** (menú principal) para importar/exportar, aplicar presets y ver la referencia de hooks.
3. Asigna menús en **Apariencia > Menús** (Principal, Top Bar, Pie de página, Móvil, Redes sociales).
4. Crea contenido de música desde el menú **Música** del administrador.

---

## Estructura de archivos

```
chuquipiondo-theme/
├── style.css                    # Cabecera del tema + @import de módulos CSS
├── theme.json                   # Configuración de Gutenberg (paleta, tipografía, layout)
├── functions.php                # SOLO require_once (regla de oro: limpio y modular)
├── header.php                   # <head> + <body> + hook del header
├── footer.php                   # Footer + cierre
├── index.php                     # Blog index / fallback
├── single.php                    # Artículo individual
├── single-musica.php             # Canción individual
├── page.php                      # Página
├── archive.php                   # Archivo (categoría, tag, autor, fecha)
├── archive-musica.php            # Archivo de música
├── search.php                    # Resultados de búsqueda
├── searchform.php                # Formulario de búsqueda personalizado
├── 404.php                       # Página no encontrada
├── comments.php                  # Plantilla de comentarios
├── screenshot.png               # Vista previa del tema
├── README.md                     # Esta documentación
│
├── inc/                          # Lógica del tema
│   ├── setup.php                 # after_setup_theme, menús, image sizes, widgets
│   ├── enqueue.php               # Carga condicional de CSS/JS (rendimiento)
│   ├── helpers.php               # Utilidades (get_option, template_part, etc.)
│   ├── sanitize.php              # Callbacks de sanitización del Customizer
│   ├── defaults.php              # Mapa central de opciones default
│   ├── widgets.php               # Widgets personalizados (About, Social)
│   ├── sidebar.php               # Selección de sidebar (right/left/none)
│   ├── template-tags.php         # Funciones de plantilla (logo, cards, icons)
│   ├── breadcrumbs.php           # Migas de pan nativas
│   ├── schema.php                # JSON-LD (Article, MusicRecording, Person)
│   ├── header.php                # Sistema de cabecera (3 filas, cajas multiuso)
│   ├── home.php                  # Home Builder modular
│   ├── blog.php                  # Blog / archivo (grid revista)
│   ├── single.php                # Single post (layouts, related, nav)
│   ├── admin-panel.php           # Panel de opciones avanzado
│   ├── ie-port.php               # Importar / Exportar / Reset
│   │
│   ├── customizer/               # Sistema de personalización
│   │   ├── register.php          # Registro principal + helper add_setting_control
│   │   ├── sections.php          # Todas las secciones del Customizer
│   │   ├── controls.php          # Control personalizado (slides repeater)
│   │   ├── presets.php           # Presets de color (6)
│   │   ├── defaults.php          # Wrapper de defaults
│   │   ├── css.php               # CSS dinámico (variables) en <head>
│   │   └── preview.php           # Selective refresh / live preview
│   │
│   ├── hero/
│   │   └── hero.php              # Lógica del hero/slider
│   │
│   ├── ads/
│   │   ├── slots.php             # Registro de 30+ zonas de anuncio
│   │   └── ads.php               # Motor de ads + inserción en párrafos
│   │
│   ├── social/
│   │   ├── share.php             # Botones de compartir
│   │   └── whatsapp.php          # WhatsApp flotante
│   │
│   └── music/
│       ├── cpt.php               # Custom Post Type `musica`
│       ├── meta-boxes.php        # Meta boxes (audio, video, letra, plataformas)
│       └── player.php            # Reproductor HTML5 + mini player sticky
│
├── template-parts/
│   ├── header/
│   │   ├── topbar.php            # Header 1 (fecha, email, redes)
│   │   ├── main.php              # Header 2 (logo, menú, buscador)
│   │   └── multiuse.php          # Header 3 (cajas multiuso configurables)
│   ├── hero/
│   │   └── slider.php            # Hero (image, slider, video, html, shortcode, elementor)
│   ├── footer/                   # (vía footer.php)
│   ├── social/
│   │   └── whatsapp-float.php    # Botón flotante de WhatsApp
│   ├── music/
│   │   └── player.php            # Reproductor HTML5
│   ├── blocks/                   # Reservado para bloques Gutenberg
│   ├── sidebar.php               # Barra lateral
│   ├── content-card.php          # Dispatcher de tarjetas
│   ├── content-card-minimal.php  # Preset Minimal
│   ├── content-card-editorial.php# Preset Editorial
│   ├── content-card-elegant.php  # Preset Elegant
│   ├── content-card-magazine.php # Preset Magazine
│   └── content-card-image-focus.php # Preset Image Focus
│
├── assets/
│   ├── css/
│   │   ├── _variables.css        # Variables CSS (colores, fuentes, anchos, radios)
│   │   ├── _base.css             # Reset, tipografía, botones, accesibilidad
│   │   ├── _layout.css          # Contenedor, grid, sidebar
│   │   ├── _header.css          # 3 filas de cabecera, móvil, sticky
│   │   ├── _hero.css             # Hero / slider / ken burns
│   │   ├── _blog.css            # Tarjetas (5 presets), paginación, breadcrumbs
│   │   ├── _single.css          # Artículo, autor, relacionados, navegación
│   │   ├── _home.css            # Módulos del home builder, 404
│   │   ├── _ads.css              # Slots de publicidad
│   │   ├── _social.css          # Share, perfiles, flotante
│   │   ├── _whatsapp.css        # Botón flotante + 9 posiciones + pulse
│   │   ├── _music.css           # Reproductor, cards, mini player
│   │   ├── _footer.css          # Pie de página
│   │   ├── _responsive.css      # Breakpoints móvil/tablet/desktop
│   │   ├── _editor.css          # Estilos del editor Gutenberg
│   │   ├── customizer.css        # Estilos del control de slides
│   │   └── admin.css             # Estilos del panel de opciones
│   ├── js/
│   │   ├── navigation.js         # Menú móvil, sticky, buscador
│   │   ├── slider.js             # Hero slider (fade, slide, ken burns)
│   │   ├── social.js             # Copy link, popups
│   │   ├── whatsapp.js           # WhatsApp float + analytics
│   │   ├── player.js            # Reproductor de música + mini player
│   │   ├── customizer-slides.js  # Repeater de slides (Customizer)
│   │   ├── customizer-preview.js # Live preview
│   │   └── admin.js              # Confirmaciones del panel
│   └── images/                   # Imágenes del tema
│
└── languages/                    # Traducciones (.po/.mo)
```

---

## Sistema de diseño (CSS)

- **Enfoque:** CSS moderno nativo (Grid, Flexbox, `aspect-ratio`, `clamp()`, `calc()`).
- **Sin dependencias obligatorias:** no Bootstrap ni Tailwind.
- **Variables CSS:** todas en `_variables.css`, sobrescritas en runtime por el CSS dinámico del Customizer (`inc/customizer/css.php`).
- **Contenedor central:** `max-width: var(--container-width)` (1280px por defecto), `margin-inline: auto`, `padding: 0 1.5rem`.
- **Mobile First:** los estilos base son para móvil; los breakpoints escalan a tablet y desktop.
- **Preset "Chuquipiondo Original":** azul marino profundo, blanco, acento celeste brillante, apariencia editorial.

---

## Customizer

Organizado en **12 secciones** accesibles desde **Apariencia > Personalizar**:

| Sección | Contenido |
|---|---|
| **Global** | Presets de color, colores, tipografía, contenedor, lectura, botones |
| **Cabecera** | Top Bar, header principal (sticky, altura, layout), Header 3 multiuso, 4 cajas configurables, distribuciones |
| **Hero / Slider** | Master switch, modos, efectos, slides, autoplay, velocidad, overlay |
| **Home Builder** | Módulos activos y orden, títulos y cantidades |
| **Blog** | Columnas (desktop/tablet/móvil), estilo de tarjeta (5 presets), sidebar, extracto, metadatos |
| **Artículo** | Layout, sidebar, toggles (breadcrumb, categorías, autor, fecha, lectura, tags, bio, relacionados), navegación, Post End Extension |
| **Publicidad** | Master switch, modos, client ID, 30+ slots |
| **Redes Sociales** | Master switch, redes a compartir, color modes, posición, flotante, perfiles |
| **WhatsApp** | Master switch, número, modo, posición (9), tamaño desktop/móvil |
| **Pie de página** | Columnas, about, copyright, colores, redes |
| **Música** | Mini player, descargas globales, color del reproductor, columnas |
| **Código personalizado** | CSS, head, body, footer |

---

## Sistema de cabecera (Header)

Tres filas configurables:

1. **Top Bar (Header 1):** fecha, hora, email, menú, redes sociales.
2. **Principal (Header 2):** logo, menú principal, buscador. Sticky opcional.
3. **Multiuso (Header 3):** hasta 4 cajas con tipo (logo, menú, texto, HTML, widget, buscador) y distribución (100%, 50/50, 33/33/33, 25×4, 60/40, 40/60). Cada caja puede ocultarse por dispositivo.

---

## Hero / Slider

- **Master Switch:** si está OFF, no se carga ningún script ni HTML.
- **Modos:** imagen única, slider, video, HTML, shortcode, template de Elementor.
- **Slides administrables:** añadir, editar, eliminar, reordenar. Cada slide: imagen desktop/tablet/móvil, títulos, botones, overlay.
- **Efectos:** fade, slide, ken burns.
- **Optimización:** el slider JS solo carga con 2+ slides. La primera imagen usa `loading="eager"` + `fetchpriority="high"`.
- **Accesibilidad:** soporta teclado, swipe, pausa en hover, respeta `prefers-reduced-motion`.

---

## Home Builder

Módulos activables y reordenables (vía la opción `home_modules`):

1. Hero
2. Artículos destacados
3. Últimos artículos
4. Categorías (Fe, Liderazgo, Gestión...)
5. Canción destacada
6. Últimos videos
7. Sobre Nelson
8. Newsletter / CTA

Se insertan zonas de publicidad entre módulos (widgets `sidebar-home-ads-1/2/3`).

---

## Blog y archivos

- **Portada editorial:** no usa el loop básico; vista tipo revista.
- **Grid configurable:** 1-4 columnas (desktop), 1-2 (tablet), 1-2 (móvil).
- **5 estilos de tarjeta:** Minimal, Editorial, Elegant, Magazine, Image Focus.
- **Sidebar:** derecha, izquierda o ninguna. Anchos: contenido ~880px, sidebar ~340px.

---

## Artículo individual (Single)

- **Layouts:** Classic, Editorial, Wide, Hero Image.
- **Control de elementos:** breadcrumb, categorías, autor, fecha, tiempo de lectura, etiquetas, biografía, relacionados (todos toggleables).
- **Ancho de lectura:** configurable (default 800px).
- **Post End Extension Area:** área extensible tras el contenido, antes de relacionados. Soporta widgets, shortcodes y templates de Elementor.
- **Navegación:** anterior/siguiente con estilos (cards, texto, oculto).

---

## Publicidad (Ads)

- **Master Switch:** activa/desactiva globalmente todos los anuncios.
- **Modos:** Desactivado, Site Kit, Auto Ads, Manual, Auto + Manual.
- **30+ slots** organizados en: Header (6), Home (5), Blog (4), Single (6), Sidebar (3), Footer (3), Música (2).
- **Comportamiento inteligente:**
  - Si un slot está OFF o vacío, no se imprime HTML.
  - No fuerza cantidad mínima de anuncios.
  - Inserción por párrafos (3, 6, 8) nunca dentro de headings, lists, blockquotes, audio, video, etc.

Ver [Zonas de anuncios](#zonas-de-anuncios) para la lista completa.

---

## Redes sociales (Share)

- **Master Switch.**
- **Redes:** Facebook, X, LinkedIn, WhatsApp, Telegram, Email, Copiar enlace.
- **Color modes:** oficial, monocromático, personalizado.
- **Posiciones:** antes/después/ambos del artículo, flotante lateral (desktop), flotante inferior (móvil).

---

## WhatsApp flotante

- **Master Switch.**
- **Número editable:** formato interno solo dígitos (default: 51921497257).
- **Modos:** mensaje privado, unirse a grupo.
- **9 posiciones predefinidas.**
- **Tamaño configurable:** 35-96px (default 52px desktop, 48px móvil).
- **Efecto pulse** que respeta `prefers-reduced-motion`.

---

## Módulo de música

- **CPT `musica`:** slug `/musica/`, con géneros (taxonomía `genero`).
- **Meta boxes:** artista, audio (URL/archivo), video (YouTube), letra, Spotify, Apple Music, YouTube, descarga.
- **Reproductor HTML5** propio: portada, título, artista, play/pausa, progreso, volumen.
- **Mini player sticky:** opcional, flota en la parte inferior.
- **Descargas:** control global + por canción. Solo archivos distribuibles legalmente.

---

## Panel de opciones avanzado

Menú **CHUQUIPIONDO** en el administrador:

- **Importar/Exportar:** configuración completa en JSON.
- **Restablecer:** vuelve a los defaults.
- **Presets de color:** aplica los 6 presets predefinidos.
- **Referencia de hooks:** tabla de acciones y filtros para desarrolladores.

No duplica controles del Customizer.

---

## Importar / Exportar / Presets

- **Exportar:** descarga un JSON con todos los `theme_mod`.
- **Importar:** sube un JSON (sobrescribe la configuración actual).
- **Reset:** elimina todos los `theme_mod` y aplica el preset "Chuquipiondo Original".
- **Presets predefinidos:** Chuquipiondo Original, Oscuro, Claro, Editorial, Music, Minimal.

Todos los handlers usan nonce y requieren `manage_options`.

---

## Hooks y filtros

### Acciones

| Hook | Uso |
|---|---|
| `chuquipiondo_before_header` | Antes de la cabecera |
| `chuquipiondo_after_header` | Después de la cabecera |
| `chuquipiondo_before_hero` | Antes del hero |
| `chuquipiondo_after_hero` | Después del hero |
| `chuquipiondo_home` | Render del home builder |
| `chuquipiondo_before_home_module` | Antes de cada módulo del home |
| `chuquipiondo_after_home_module` | Después de cada módulo del home |
| `chuquipiondo_before_post_end_extension` | Antes del área Post End |
| `chuquipiondo_after_post_end_extension` | Después del área Post End |

### Filtros

| Hook | Uso |
|---|---|
| `chuquipiondo_card_style` | Estilo de tarjeta de artículo |
| `chuquipiondo_home_modules` | Módulos activos del home |
| `chuquipiondo_css_vars` | Variables CSS dinámicas |
| `chuquipiondo_ad_code` | Código de un slot de anuncio |
| `chuquipiondo_ad_slots` | Registro de slots de anuncio |
| `chuquipiondo_share_networks` | Redes de share |
| `chuquipiondo_presets` | Presets de color |
| `chuquipiondo_sidebar_position` | Posición de la barra lateral |
| `chuquipiondo_dynamic_css` | CSS dinámico completo |
| `chuquipiondo_article_schema` | Schema JSON-LD de artículo |
| `chuquipiondo_music_schema` | Schema JSON-LD de música |
| `chuquipiondo_person_schema` | Schema JSON-LD de persona |
| `chuquipiondo_located_template` | Path de template localizado |
| `chuquipiondo_hero_elementor_template` | ID de template de Elementor para hero |

Ejemplos en `chuquipiondo-child/functions.php`.

---

## Widgets y zonas

### Sidebars registradas

| ID | Nombre | Uso |
|---|---|---|
| `sidebar-1` | Barra lateral principal | Blog y artículos |
| `sidebar-footer` | Pie de página | Footer |
| `sidebar-post-end` | Post End Extension | Área extensible tras el contenido |
| `sidebar-header-multiuse` | Header multiuso | Cajas del Header 3 |
| `sidebar-home-ads-1/2/3` | Home - Publicidad | Entre módulos del home |

### Widgets personalizados

- **CHUQUIPIONDO: Sobre** — bio corta.
- **CHUQUIPIONDO: Redes** — iconos de perfiles.

---

## Zonas de anuncios

30+ slots (todos configurables en **Apariencia > Personalizar > Publicidad**):

### Header (6)
- `ads_header_before` — antes de todo
- `ads_header_before_topbar` — antes del Top Bar
- `ads_header_after_topbar` — después del Top Bar
- `ads_header_between` — entre Top Bar y Principal
- `ads_header_after_main` — después del Principal
- `ads_header_after` — después de todo

### Home (5)
- `ads_home_after_hero`, `ads_home_after_featured`, `ads_home_after_latest`, `ads_home_after_categories`, `ads_home_after_about`

### Blog (4)
- `ads_blog_top`, `ads_blog_after_row`, `ads_blog_middle`, `ads_blog_bottom`

### Single (6)
- `ads_after_title`, `ads_after_thumbnail`, `ads_after_paragraph_3`, `ads_after_paragraph_6`, `ads_after_paragraph_8`, `ads_before_related`

### Sidebar (3)
- `ads_sidebar_top`, `ads_sidebar_middle`, `ads_sidebar_bottom`

### Footer (3)
- `ads_footer_before`, `ads_footer_between`, `ads_footer_after`

### Música (2)
- `ads_music_archive_top`, `ads_music_single_after`

Tamaños soportados: 728×90, 300×250, 336×280, responsive.

---

## Seguridad y buenas prácticas

- **Sanitización:** todos los inputs del Customizer usan callbacks de sanitización (`inc/sanitize.php`).
- **Escape:** toda salida usa `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`.
- **Nonces:** los handlers de import/export/reset y los meta boxes usan `wp_verify_nonce`.
- **Capacidades:** `current_user_can('manage_options')` en todas las acciones de administración.
- **No ejecución de PHP arbitrario** desde áreas de administración: solo hooks, filtros y shortcodes.

---

## Integraciones

- **Elementor/SeedProd:** compatibles pero **no obligatorios**. El tema funciona sin ellos.
- **Gutenberg:** compatibilidad total (wide, full, columns, cover). `theme.json` para consistencia.
- **SEO Plugins:** compatible con Rank Math, Yoast, SEOPress (breadcrumbs detectados automáticamente).
- **Caché/CDN:** no interfiere con Cloudflare, LiteSpeed, WP Rocket.

---

## SEO y rendimiento

- **SEO técnico:** HTML semántico, breadcrumbs nativos, Schema.org JSON-LD (Person, Article, MusicRecording).
- **Core Web Vitals:** CSS crítico inline, JS deferido, carga condicional de scripts, `loading="lazy"` en imágenes.
- **Imágenes:** `srcset`, `sizes`, `loading`, `fetchpriority="high"` en LCP.
- **Fuentes:** Google Fonts con `preconnect`.
- **CSS modular:** cargado vía `@import` en `style.css`.

---

## Actualización

1. Exporta tu configuración desde **CHUQUIPIONDO > Exportar** (backup).
2. Reemplaza la carpeta del tema.
3. Si usas el tema hijo, tus personalizaciones se conservan.
4. Importa el JSON de configuración si es necesario.

---

## Soporte

- **Sitio:** [www.chuquipiondo.com](https://www.chuquipiondo.com)
- **Autor:** Nelson Chuquipiondo

---

**¡Juntos, sí podemos!**
