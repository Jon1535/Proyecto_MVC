# Método: Libro::ListarPorPropietario($id_propietario)

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Recuperar los libros cuyo `id_propietario` coincida con el valor suministrado.

## Firma aproximada
- `public function ListarPorPropietario($id_propietario)`

## Parámetros
- `$id_propietario` (int) — id del usuario propietario.

## Retorno
- `array` de objetos con los libros del propietario.

## Uso / llamadas detectadas
- `Controladores/Libro.controlador.php` — en `FormCrear()` para obtener los libros del usuario autenticado: `$libros = $this->modelo->ListarPorPropietario($_SESSION['id_usuario']);`.
  - Archivo: `Controladores/Libro.controlador.php`.

## Notas
- Verificar que `$id_propietario` sea entero y > 0 antes de ejecutarla.
- Si se espera paginación, añadir parámetros opcionales para `limit`/`offset`.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador que lo consume: `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`
