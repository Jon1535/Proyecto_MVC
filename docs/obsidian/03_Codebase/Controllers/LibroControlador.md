# Controlador: LibroControlador

**Archivo:** `Controladores/Libro.controlador.php`

## Propósito
Gestión completa de la entidad `Libro`: listados, CRUD, subida de imágenes y filtros de búsqueda. Implementa reglas de negocio básicas sobre propiedad y permisos (sólo el propietario puede editar/eliminar sus libros).

## Acciones públicas y rutas
- `Inicio()` → `Vistas/Libros/Index.php` (ruta: `?c=Libro&a=Inicio`).
- `MisLibros()` → `Vistas/Libros/MisLibros.php` (ruta: `?c=Libro&a=MisLibros`). Muestra listado global o filtrado por `buscar` en GET.
- `FormCrear()` / `Crear()` → `Vistas/Libros/Crear.php` (ruta: `?c=Libro&a=FormCrear` o `?c=Libro&a=Crear`). Muestra formulario y los libros del propietario.
- `Editar()` → `Vistas/Libros/Editar.php` (ruta: `?c=Libro&a=Editar&id={id}`) — valida propiedad antes de permitir edición.
- `Guardar()` → Procesa POST para crear o actualizar libros (ruta: `?c=Libro&a=Guardar`, método POST).
- `Eliminar()` → Elimina libro si el usuario es propietario (ruta: `?c=Libro&a=Eliminar&id={id}`).

## Parámetros y respuestas unificadas
El controlador utiliza un esquema de parámetros GET para indicar resultados (`success=1`, `deleted=1`, `error=1..6`), y redirige a la vista `Crear` para mostrar alertas.

## Entradas esperadas
- `Guardar()` espera `POST` con: `titulo`, `autor`, `id_categoria`, `descripcion`, `condicion`, `id_propietario`, opcionalmente `id_libro` para edición y `fotos[]` para subir imágenes.
- `Editar()` y `Eliminar()` esperan `GET` con `id` válido.

## Seguridad y validaciones implementadas
- Comprueba `$_SESSION['id_usuario']` para acciones protegidas.
- Verifica que el `id_propietario` del libro coincide con la sesión antes de permitir editar/eliminar.
- Validación de archivos: tamaño máximo (5 MB), verificación de MIME con `finfo`, validación de extensiones permitidas (`jpg,jpeg,png,webp`), generación de nombres seguros para evitar colisiones.
- Validación básica de campos obligatorios (backup del `required` HTML).

## Puntos débiles / mejoras recomendadas
- Usa `session_start()` dentro del controlador — mejor centralizarlo en `index.php`.
- Las redirecciones con códigos `error` numéricos no son descriptivas — considerar mensajes flash almacenados en `$_SESSION` para más claridad.
- Operaciones destructivas (`Eliminar`) usan GET — cambiar a POST con token CSRF para mayor seguridad.
- Manejo de errores en la capa de modelo: actualmente el controlador captura `Exception`, pero sería ideal que el modelo lance excepciones bien tipadas y que el controlador las maneje con mensajes amigables.
- Si la aplicación escala, pasar a manejo de archivos asíncrono o almacenamiento en S3/servicio externo.

## Recomendaciones para tests
- Probar `Guardar()` con `$_FILES` simulados y un `PDO` en memoria para verificar inserciones y actualizaciones.
- Testear validaciones de propiedad para `Editar()` y `Eliminar()`.

## Notas de mantenimiento
- La carpeta de subida es `Assets/images/uploads/` (relativa al proyecto). Asegurar que `www-data` o el usuario del servidor tenga permisos adecuados.
- Considerar mover la lógica de subida a una clase helper `Uploader` para reutilizar y simplificar el controlador.
