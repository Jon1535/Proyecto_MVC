# Vista: Pie

**Archivo:** `Vistas/Pie.php`

## Propósito
Plantilla de pie de página común para todas las vistas del panel. Incluye scripts finales, pie informativo y cierre de contenedores HTML.

## Elementos principales
- Inclusión de JS al final: `Assets/js/bootstrap.min.js`, `Assets/js/main.js`, `Assets/js/login-flip.js`.
- Footer con año, crédito y enlace a documentación interna (si aplica).
- Scripts para plugins (Chart.js, DataTables) cuando se usan en páginas específicas.

## Dependencias (JS)
- `Assets/js/jquery-3.7.0.min.js` (requerido por plugins)
- `Assets/js/bootstrap.min.js`
- `Assets/js/main.js`
- `Assets/js/plugins/chart.js` (solo si se usan gráficas)
- `Assets/js/plugins/jquery.dataTables.min.js` (para tablas con DataTables)

## Recomendaciones
- Cargar condicionalmente los plugins pesados solo en las vistas que los necesitan (p. ej. DataTables solo en index de libros).
- Añadir atributos `defer` o `async` a scripts estáticos cuando sea seguro.

## Checklist
- [ ] Verificar que no hay duplicación de inclusión de jQuery.
- [ ] Minimizar y combinar scripts en producción (`main.min.js`).

