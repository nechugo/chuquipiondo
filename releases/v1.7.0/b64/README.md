# CHUQUIPIONDO v1.7.0 — Descarga de entregables

El repositorio es **privado**, por lo que descargar los ZIPs directamente con el enlace `raw` desde el navegador **corrompe los binarios** (GitHub los sirve como `text/plain` y el navegador los reescribe). Esto provoca el error:

> No se ha podido descomprimir el paquete. El tema no tiene la hoja de estilos style.css.

## ✅ Solución: descargar como base64 y reconstruir

Los 4 entregables están en `releases/v1.7.0/b64/` como texto base64 (servido correctamente como texto plano, sin corrupción):

| Entregable | Archivo base64 |
|------------|----------------|
| Tema padre | [`chuquipiondo-theme.zip.b64`](https://github.com/nechugo/chuquipiondo/raw/main/releases/v1.7.0/b64/chuquipiondo-theme.zip.b64) |
| Tema hijo | [`chuquipiondo-child.zip.b64`](https://github.com/nechugo/chuquipiondo/raw/main/releases/v1.7.0/b64/chuquipiondo-child.zip.b64) |
| Plugin core | [`chuquipiondo-core.zip.b64`](https://github.com/nechugo/chuquipiondo/raw/main/releases/v1.7.0/b64/chuquipiondo-core.zip.b64) |
| Plugin companion | [`chuquipiondo-companion.zip.b64`](https://github.com/nechugo/chuquipiondo/raw/main/releases/v1.7.0/b64/chuquipiondo-companion.zip.b64) |

### Reconstrucción

**Linux / macOS / WSL / Git Bash** (tienes `base64` instalado):

```bash
# Descarga el .b64 (botón Raw de GitHub) y reconstruye el ZIP:
base64 -d chuquipiondo-theme.zip.b64 > chuquipiondo-theme.zip
```

**Windows PowerShell** (sin instalar nada):

```powershell
# Descarga el .b64 (botón Raw de GitHub) y reconstruye el ZIP:
$b64 = Get-Content -Raw chuquipiondo-theme.zip.b64
[IO.File]::WriteAllBytes("chuquipiondo-theme.zip", [Convert]::FromBase64String($b64))
```

**Windows CMD** (con `certutil`, incluido en Windows):

```cmd
certutil -decode chuquipiondo-theme.zip.b64 chuquipiondo-theme.zip
```

Repite el comando para cada entregable cambiando el nombre del archivo.

## Verificación

Después de reconstruir, el ZIP debe ser válido. Puedes comprobarlo:

- **Windows**: el archivo debería abrirse con el Explorador y mostrar la carpeta `chuquipiondo-theme/` con `style.css` dentro.
- **Linux/macOS**: `unzip -t chuquipiondo-theme.zip` (debe decir "No errors detected").

## Instalación en WordPress

1. Sube `chuquipiondo-theme.zip` en **Apariencia > Temas > Añadir nuevo > Subir tema** y actívalo.
2. Sube `chuquipiondo-core.zip` y `chuquipiondo-companion.zip` en **Plugins > Añadir nuevo > Subir plugin** y actívalos.
3. (Opcional) Sube `chuquipiondo-child.zip` para personalizaciones seguras.
4. Ve a **Companion > Starter Sites** para importar un sitio preconfigurado.

## Versión

Todos los entregables están en **v1.7.0**.
