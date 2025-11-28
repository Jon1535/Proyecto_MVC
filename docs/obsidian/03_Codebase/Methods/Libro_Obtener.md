# Método: Libro::Obtener($id)

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Recuperar un único registro `libro` por su `id_libro`.

## Firma aproximada
- `public function Obtener($id)`

## Parámetros
- `$id` (int) — id del libro a recuperar.

## Retorno
- `object` (PDO::FETCH_OBJ) con los campos del libro o `null`/`false` si no existe.

## Uso / llamadas detectadas
- `Controladores/Libro.controlador.php` — usado en `Editar()` para cargar los datos a editar: `$libro = $this->modelo->Obtener($id);`.
- `Controladores/Libro.controlador.php` — usado en `Guardar()` para mantener la `imagen_url` cuando no se suben nuevas imágenes.
- `Controladores/Libro.controlador.php` — usado en `Eliminar()` para verificar propiedad antes de borrar.
- `Modelos/Libro.php` — uso interno en `Eliminar()` (para obtener la imagen a borrar antes de eliminar el registro).

## Manejo de errores
- Debe retornar `null` o `false` si no se encuentra; controladores manejan este caso y redirigen con `error=1`.

## Recomendaciones
- Tipar el retorno (`?object`) y documentar los campos devueltos.
- Evitar devolver recursos de BD directamente a la vista sin escapado; las vistas deben aplicar `htmlspecialchars()`.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador: `Controladores/Libro.controlador.php` / `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`
