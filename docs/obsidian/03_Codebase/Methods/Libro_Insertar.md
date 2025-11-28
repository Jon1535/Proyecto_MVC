# Método: Libro::Insertar(Libro $l)

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Insertar un nuevo registro en la tabla `libro` y devolver el id insertado (`lastInsertId`).

## Firma aproximada
- `public function Insertar($libro)`

## Parámetros
- `$libro` — objeto o entidad con propiedades: `titulo`, `autor`, `id_categoria`, `descripcion`, `imagen_url`, `id_propietario`, `condicion`, `estado`, `fecha`.

## Retorno
- `int` — id del nuevo registro (según implementación puede devolver `lastInsertId()` o `true/false`).

## Uso / llamadas detectadas
- `Controladores/Libro.controlador.php` — en `Guardar()` al crear un nuevo libro: `$this->modelo->Insertar($libro);`.
  - Archivo: `Controladores/Libro.controlador.php` (línea donde se llama a `Insertar`).
- `docs/obsidian/03_Codebase/Models/Libro.md` — ejemplo de uso en la sección de controladores.

## Manejo de errores
- El método debe lanzar `Exception` en caso de error SQL para que el controlador pueda redirigir con `error=2`.

## Recomendaciones
- Validar los datos antes de la inserción (idealmente en controlador) y usar consultas preparadas (PDO) para evitar inyección SQL.
- Retornar un valor consistente (por ejemplo `int` id o lanzar excepción) en lugar de mezclar `false`/`string`.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador: `Controladores/Libro.controlador.php` (ver `Guardar()` en `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`)
