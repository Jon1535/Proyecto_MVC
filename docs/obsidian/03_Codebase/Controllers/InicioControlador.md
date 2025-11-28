# Controlador: InicioControlador

**Archivo:** `Controladores/Inicio.controlador.php`

## Propósito
Controlador mínimo que prepara y muestra la vista principal del sitio (dashboard / página de inicio). Actúa como punto de entrada para la vista `Vistas/Inicio/Principal.php`.

## Acciones públicas
- `Inicio()` — Carga las vistas: `Vistas/Encabezado.php`, `Vistas/Inicio/Principal.php`, `Vistas/Pie.php`.

## Dependencias
- `Modelos/Libro.php` — la clase se instancia en el constructor aunque actualmente no se utiliza en la acción `Inicio`.
- Vistas: `Encabezado`, `Inicio/Principal`, `Pie`.

## Rutas/Invocación
- Front controller (`index.php`) llama a `?c=Inicio&a=Inicio` por defecto o cuando `c=Inicio`.

## Notas y recomendaciones
- Actualmente el constructor instancia `Libro` pero no se usa en la acción `Inicio`. Puedes eliminar la creación del modelo si no se requiere, o aprovecharlo para mostrar estadísticas en la vista (p. ej. contar libros).
- Mantener mínima la lógica en este controlador; preparar datos y pasarlos a la vista si la vista lo necesita.
- Asegurar que `session_start()` se gestione de forma centralizada (en `index.php`) para evitar múltiples llamadas.

## Testing
- Testear que `Inicio()` incluye las vistas correctas puede realizarse con pruebas de integración que verifiquen salida HTML o con tests de controlador que simulen la inclusión y verifiquen la presencia de strings esperados.
