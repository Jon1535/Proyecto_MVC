# Método: Libro::CantidadLibros()

**Definición (archivo):** `Modelos/Libro.php`

## Propósito
Obtener el número total de registros en la tabla `libro` (COUNT(*)).

## Firma aproximada
- `public function CantidadLibros()`

## Parámetros
Ninguno.

## Retorno
- `int` — número total de libros.

## Uso / llamadas detectadas
- No se detectaron llamadas directas en el repositorio actual, aunque el método existe en el modelo y puede usarse para mostrar estadísticas en `Inicio`.

## Recomendaciones
- Usar en `InicioControlador` para mostrar indicadores de dashboard (p. ej. total de libros).

## Enlaces
- Modelo: `Modelos/Libro.php`
- Controlador sugerido para usarlo: `Controladores/Inicio.controlador.php` / `docs/obsidian/03_Codebase/Controllers/InicioControlador.md`
