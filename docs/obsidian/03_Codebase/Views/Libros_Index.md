# Vista: Libros - Index

**Archivo:** `Vistas/Libros/Index.php`

## Propósito
Listado principal de libros. Muestra una tabla con los libros existentes, acciones (Editar, Eliminar, Ver) y filtros/búsqueda.

## Elementos del UI
- Tabla HTML con columnas: `ID`, `Título`, `Autor`, `Categoría`, `Propietario`, `Estado`, `Acciones`.
- Botón `Crear nuevo libro` que redirige a `?c=Libro&a=FormCrear` o similar.
- Paginación simple (si implementada) o DataTables para búsqueda/ordenación.
- Mensajes flash en la parte superior para confirmar acciones (creado, actualizado, eliminado).

## Dependencias (CSS/JS)
- `Assets/css/main.css`
- `Assets/js/plugins/jquery.dataTables.min.js` (si se usa DataTables)
- `Assets/js/main.js` (para acciones de UI y confirmaciones)

## Datos esperados desde el controlador
El controlador debe proveer una variable (por ejemplo `$libros`) que sea un array/objetos con los campos necesarios:
- `id_libro`, `titulo`, `autor`, `id_categoria` (o nombre de categoría), `imagen_url`, `id_propietario`, `estado`.

## Formularios/Acciones
- `Editar` → `?c=Libro&a=Editar&id=...`
- `Eliminar` → `?c=Libro&a=Eliminar&id=...` (normalmente por enlace GET o formulario POST con confirmación JS)
- `Ver` → vista de detalle (opcional)

## Recomendaciones y buenas prácticas
- Usar formularios POST para operaciones destructivas (Eliminar) con token CSRF si se añade seguridad.
- Escapar salidas con `htmlspecialchars()` al renderizar datos para prevenir XSS.
- Si se usa DataTables, inicializarlo con server-side processing si la tabla crece mucho.

## Checklist de mejoras
- [ ] Añadir confirmación modal antes de eliminar.
- [ ] Escapar todas las salidas de usuario.
- [ ] Cargar solo columnas necesarias para mejorar rendimiento.

