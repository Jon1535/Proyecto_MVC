# Controlador: BusquedaControlador

**Archivo:** `Controladores/Busqueda.controlador.php`

## Propósito
Proveer endpoints de búsqueda y sugerencias para la interfaz (autocomplete y redirección a resultados).

## Acciones públicas
- `Sugerencias()` — Endpoint JSON que, dado `GET q=termino`, devuelve hasta 10 coincidencias de título/autor en formato JSON. Ruta: `?c=Busqueda&a=Sugerencias&q=...`.
- `Buscar()` — Redirige a `?c=Libro&a=MisLibros&buscar=...` para mostrar resultados filtrados según `q`. Ruta: `?c=Busqueda&a=Buscar&q=...`.

## Comportamiento
- `Sugerencias()` responde con `Content-Type: application/json` y retorna `[]` si la consulta tiene menos de 2 caracteres.
- `Buscar()` valida que `q` no esté vacío y redirige correctamente.

## Dependencias
- `Modelos/Libro.php` — utiliza `Listar()` para obtener el conjunto de libros a filtrar.

## Recomendaciones
- Si la cantidad de libros crece, evitar `Listar()` completo y usar una consulta SQL con `LIKE` en el modelo para eficiencia y paginación.
- Normalizar y escapar la entrada `q` antes de usarla (aunque aquí no se ejecutan consultas SQL directas, es buena práctica).
- Añadir caching para sugerencias frecuentes.

## Notas para integración con UI
- `Vistas/Encabezado.php` o `Assets/js/main.js` deben consumir `?c=Busqueda&a=Sugerencias&q=...` para autocomplete.
- Formulario de búsqueda puede usar `Buscar()` para redirigir a `MisLibros` con el parámetro `buscar`.
