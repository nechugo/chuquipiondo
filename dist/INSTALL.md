# CHUQUIPIONDO v1.7.0 — Entregables

Reconstrucción completa de los 4 entregables, verificados uno a uno.

## Archivos

| Archivo | Tipo | Versión | Tamaño |
|---------|------|---------|--------|
| `chuquipiondo-theme.zip` | Tema padre | 1.7.0 | ~234 KB |
| `chuquipiondo-child.zip` | Tema hijo | 1.7.0 | ~2 KB |
| `chuquipiondo-core.zip` | Plugin core | 1.7.0 | ~22 KB |
| `chuquipiondo-companion.zip` | Plugin companion | 1.7.0 | ~36 KB |
| `chuquipiondo-bundle-v1.7.0.zip` | Los 4 anteriores en un solo ZIP | 1.7.0 | ~295 KB |

## ⚠️ Cómo descargar SIN corrupción (repo privado)

El repositorio es **privado**. Descargar con el enlace `raw` desde el navegador **corrompe los binarios** y provoca el error:

> No se ha podido descomprimir el paquete. El tema no tiene la hoja de estilos style.css.

### Método recomendado: `git clone` + copia local

```bash
git clone https://github.com/nechugo/chuquipiondo.git
cd chuquipiondo/dist
```

Los ZIPs están en la carpeta `dist/`. Cópielos a su equipo y súbalos a WordPress.

### Método alternativo: GitHub Desktop o "Download ZIP" del repo

1. En GitHub, botón verde **"Code" > "Download ZIP"** (descarga TODO el repo como ZIP).
2. Descomprima. Los entregables están en `dist/`.

### Método alternativo: `gh` CLI

```bash
gh api repos/nechugo/chuquipiondo/contents/dist/chuquipiondo-theme.zip?ref=main \
  -H "Accept: application/vnd.github.raw" > chuquipiondo-theme.zip
```

## Instalación en WordPress

1. **Tema**: `chuquipiondo-theme.zip` → **Apariencia > Temas > Añadir nuevo > Subir tema** → Activar.
2. **Plugins**: `chuquipiondo-core.zip` y `chuquipiondo-companion.zip` → **Plugins > Añadir nuevo > Subir plugin** → Activar.
3. **Tema hijo (opcional)**: `chuquipiondo-child.zip` → **Apariencia > Temas > Subir tema** → Activar (después del padre).
4. Vaya a **Companion > Starter Sites** para importar un sitio preconfigurado.

## Orden recomendado de activación

1. Tema padre (`chuquipiondo-theme`)
2. Plugin core (`chuquipiondo-core`)
3. Plugin companion (`chuquipiondo-companion`)
4. (Opcional) Tema hijo (`chuquipiondo-child`)

## Verificación de integridad

Cada ZIP fue verificado:
- Firma `PK` (ZIP válido).
- Carpeta contenedora correcta (`chuquipiondo-theme/`, `chuquipiondo-core/`, etc.).
- Archivo principal presente (`style.css` para temas, `*.php` principal para plugins).
- Versión 1.7.0 en cabecera.
- CRC sin errores.

## Requisitos

- WordPress 6.2+
- PHP 7.4+
