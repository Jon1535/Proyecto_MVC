# Vista: Encabezado

**Archivo:** `Vistas/Encabezado.php`

## Propósito
Plantilla de cabecera global que se incluye en todas las páginas. Contiene el `<head>` con enlaces a CSS/JS, la barra de navegación superior y el sidebar del panel de administración (Vali Admin).

## Elementos principales
- Meta tags (charset, viewport).
- Inclusión de CSS: `Assets/css/main.css`, `Diseño/docs/css/main.css`.
- Inclusión de JS en cabecera (si corresponde) y scripts de cabecera del tema.
- Navbar con enlaces a `Inicio`, `Buscar`, `MisLibros`, `Perfil`, y logout.
- Buscador con autocomplete (usa `js/image-preview.js` y `js/jquery-3.7.0.min.js` en conjunto con `Controladores/Busqueda.controlador.php`).

## Dependencias (CSS/JS)
- `Assets/css/main.css`
- `Assets/js/jquery-3.7.0.min.js`
- `Assets/js/main.js` (comportamiento de UI)
- `Assets/js/image-preview.js` (si la cabecera muestra previews)

## Variables/Estado usado
- `$_SESSION['usuario']` — para mostrar nombre/rol del usuario logueado.
- Rutas relativas usadas por enlaces y formularios (ej. `?c=Libro&a=MisLibros`).

## Puntos importantes y recomendaciones
- Centralizar `session_start()` en el `index.php` o en un `bootstrap` para evitar llamadas repetidas en cada controlador/vista.
- Evitar lógica pesada en la vista; la cabecera sólo debe presentar elementos y consumir variables (ej. nombre de usuario) preparadas por el controlador.
- Si se necesita i18n, extraer textos hacia un arreglo de traducciones.

## Checklist de mejoras para esta vista
- [ ] Mover dependencias de JS al final del `body` cuando no sean críticas.
- [ ] Consolidar rutas en un helper `url()` para evitar concatenaciones manuales.
- [ ] Añadir `Content-Security-Policy` si el proyecto se expone públicamente.

## Ejemplo de inclusión
```php
require_once 'Vistas/Encabezado.php';
// ... vista específica ...
require_once 'Vistas/Pie.php';
```
