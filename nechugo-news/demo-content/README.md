# Contenido demo — nechugo News

Paquete de contenido de demostracion tipo **portal de anuncios de empleo** (referencia: empleos.aepmp.com) para probar el tema con datos realistas.

## Contenido

- `demo-posts.xml` — 9 entradas de empleo (865-900 palabras cada una) en formato WXR, listas para importar en `Herramientas → Importar → WordPress`.
- `images/` — 9 imagenes destacadas 900x520 (formato exacto del tema) + logo demo.
- `images/logo-nechugo-news.png` — logo demo "NECHUGO NEWS" 440x120 (supera el minimo de 220px de ancho).
- `images/logo-nechugo-news.svg` — version vectorial del logo.
- `generar-demo.py` — script que regenera el XML (util para ampliar el demo).

## Entradas incluidas

1. Grupo Auna busca Ejecutivo(a) Comercial Corporativo (Peru)
2. Vacantes en Coppel Mexico y como postular (Mexico)
3. Empleos en Coca-Cola FEMSA Mexico (FABRICA)
4. Oportunidades de empleo en Minera Chinalco (Chile)
5. Trabajo en Suiza cuidando casas en 2026 (SUIZA)
6. Oportunidades de empleo para latinos en Canada (Canada)
7. Trabajo Disponible en una Fabrica Textil en Espana (Espana)
8. Oportunidad de trabajo en fabrica de cajas de carton (FABRICA)
9. Restaurantes en Estados Unidos contratan personal hispanohablante (Estados Unidos)

Categorias creadas: Empleos, Peru, Mexico, FABRICA, Chile, SUIZA, Canada, Espana, Estados Unidos.

## Instalacion

1. **Logo:** `Apariencia → Personalizar → Identidad del sitio → Logo` y subir `images/logo-nechugo-news.png`.
2. **Entradas:** `Herramientas → Importar → WordPress`, subir `demo-posts.xml`, marcar "Descargar e importar adjuntos" NO es aplicable (las imagenes deben subirse a mano o editarse luego).
3. **Imagenes destacadas:** subir cada imagen de `images/` como imagen destacada de su entrada (los nombres coinciden con el tema de cada anuncio).
4. **Menu sugerido:** crear menu "principal" con las categorias (Empleos, Peru, Mexico, FABRICA, ...) imitando la navegacion por paises del portal de referencia.
