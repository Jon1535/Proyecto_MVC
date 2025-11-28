# Método: Libro::Eliminar($id_libro)

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Eliminar un registro de `libro` y, opcionalmente, borrar la imagen asociada del sistema de archivos.

## Firma aproximada
- `public function Eliminar($id_libro)`

## Parámetros
- `$id_libro` (int) — id del libro a eliminar.

## Retorno
- `bool` — `true` si la eliminación (registro y archivos asociados) fue exitosa, `false` en otro caso.

## Uso / llamadas detectadas
- `Controladores/Libro.controlador.php` — en `Eliminar()` se valida propiedad y se invoca `$this->modelo->Eliminar($id);`.
  - Archivo: `Controladores/Libro.controlador.php` (línea donde se llama a `Eliminar`).
- `Modelos/Libro.php` — la propia implementación llama internamente a `Obtener()` para localizar la `imagen_url` y borrarla del disco.

## Efectos secundarios
- Borrado de archivo físico (`Assets/images/uploads/<archivo>`) si existe.
- Asegurarse de manejar permisos y no intentar borrar archivos fuera del directorio esperado.

## Recomendaciones
- Implementar comprobación adicional para evitar path traversal (usar `basename()` o validar rutas).
- Considerar soft-delete (marcar como eliminado) si se desea conservar historial.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador: `Controladores/Libro.controlador.php` / `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`
