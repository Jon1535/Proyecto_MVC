# Método: Libro::Actualizar(Libro $l)

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Actualizar los datos de un libro existente identificado por `id_libro`.

## Firma aproximada
- `public function Actualizar($libro)`

## Parámetros
- `$libro` — objeto con `id_libro` y campos a actualizar (`titulo`, `autor`, `descripcion`, `imagen_url`, `condicion`, etc.).

## Retorno
- `bool` — `true` si la actualización tuvo éxito, `false` o lanzar `Exception` en caso de fallo.

## Uso / llamadas detectadas
- `Controladores/Libro.controlador.php` — en `Guardar()` al editar un libro: `$this->modelo->Actualizar($libro);`.
  - Archivo: `Controladores/Libro.controlador.php` (call site visible en `Guardar()`).

## Consideraciones
- Verificar que el `id_libro` del objeto exista en la BD antes de actualizar.
- Validar que el `id_propietario` coincida con el usuario de la sesión si se llama desde controladores.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador que llama: `Controladores/Libro.controlador.php` / `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`
