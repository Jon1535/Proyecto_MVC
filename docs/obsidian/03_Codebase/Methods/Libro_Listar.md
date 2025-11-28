# Método: Libro::Listar()

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Recuperar todos los registros de la tabla `libro` (devuelve un arreglo de objetos PDO::FETCH_OBJ en la implementación actual).

## Firma aproximada
- `public function Listar()`

## Parámetros
Ninguno.

## Retorno
- `array` de objetos (cada objeto representa un libro con campos como `id_libro`, `titulo`, `autor`, `imagen_url`, `id_propietario`, etc.).

## Uso / llamadas detectadas
- `Vistas/Libros/Index.php` — iteración para mostrar la tabla de libros: `foreach ($this->modelo->Listar() as $r)`.
  - Archivo: `Vistas/Libros/Index.php` (línea encontrada en el repo).
- `Controladores/Busqueda.controlador.php` — obtiene el listado completo para filtrar en `Sugerencias()`.
  - Archivo: `Controladores/Busqueda.controlador.php` (llamada a `$this->modelo->Listar()`).
- `Controladores/Libro.controlador.php` — usado en `MisLibros()` para obtener `$biblioteca = $this->modelo->Listar()`.
  - Archivo: `Controladores/Libro.controlador.php`.

## Consideraciones de rendimiento
- Si la tabla `libro` crece, evite cargar todo con `Listar()`; implementar paginación o consultas con `LIMIT`/`OFFSET` o búsquedas con `LIKE`.
- Para `Sugerencias()`, una consulta SQL con `WHERE titulo LIKE :q OR autor LIKE :q` sería más eficiente que filtrar en PHP.

## Enlaces
- Modelo: `Modelos/Libro.php`
- Documentación modelo: `docs/obsidian/03_Codebase/Models/Libro.md`
- Uso en controladores: `docs/obsidian/03_Codebase/Controllers/BusquedaControlador.md`, `docs/obsidian/03_Codebase/Controllers/LibroControlador.md`
