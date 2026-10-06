# nechugo News

Tema WordPress de revista digital, inspirado en los demos editoriales de Newspaper (tagDiv), ligero y rápido.

## Características

- **4 encabezados** y **3 pies de página**, todos full-width (de extremo a extremo), elegibles desde el Personalizador.
- Contenido del blog con ancho mínimo de **1200px** (configurable).
- **Multicolor**: 7 presets + colores individuales con vista previa en vivo.
- Tipografías configurables; por defecto texto de artículo **12px** y H1 **16px**.
- Espacios listos para **Google AdSense**: auto-ads, slots de header/artículo/sidebar y widget de caja de anuncio.
- **Slider** de destacados en portada, grid/list/masonry, breadcrumbs, compartir, back-to-top.
- Compatible con **Elementor** (canvas full-width, Theme Builder), WooCommerce y Gutenberg.
- Mobile-first responsive, JS vanilla de ~2KB, sin dependencias.

## Estructura

```
nechugo-news/
├── functions.php              # Solo requires (modular)
├── inc/
│   ├── setup.php              # Soportes, menús, sidebars
│   ├── enqueue.php            # Assets + CSS inline del personalizador
│   ├── ads.php                # Motor AdSense
│   ├── compatibility.php      # Elementor / WooCommerce
│   └── customizer/            # Registro, CSS dinámico, preview
├── template-parts/
│   ├── header/               # 4 variantes
│   ├── footer/               # 3 variantes
│   └── cards/                # Cards de listado
└── assets/css/main.css       # Diseño completo en un archivo
```

## Personalizador

`Apariencia → Personalizar → nechugo News: Opciones`:
- Colores (presets e individuales)
- Tipografía (fuente y tamaños)
- Encabezado (4 diseños, topbar, sticky, fecha, redes)
- Pie de página (3 diseños, columnas, copyright con `{year}` y `{sitename}`)
- Blog y layout (ancho, grid, sidebar, extracto, slider)
- Google AdSense (master switch, publisher ID, modo, 4 slots)
- Redes sociales

## Licencia

GPL v2 or later.
