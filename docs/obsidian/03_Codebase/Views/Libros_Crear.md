# Vista: Libros - Crear

**Archivo:** `Vistas/Libros/Crear.php`

## Propósito
Formulario para crear un nuevo libro. Incluye campos para título, autor, categoría, descripción, imagen y condición/estado.

## Campos del formulario
- `titulo` (text, required)
- `autor` (text, required)
- `id_categoria` (select)
- `descripcion` (textarea)
- `imagen` (file upload, aceptando imágenes)
- `condicion` / `estado` (select/radio)

## Dependencias (JS)
- `Assets/js/image-preview.js` — para mostrar vista previa de la imagen antes de subirla.
- `Assets/js/jquery-3.7.0.min.js`

## Validaciones esperadas
- Validación cliente: `required` y tamaño/mimetype básico en el input file.
- Validación servidor en `Controladores/Libro.controlador.php`:
  - Tamaño máximo y tipos MIME permitidos.
  - Validación de campos obligatorios y longitud.

## Flujo de subida de imagen (resumen)
1. El usuario selecciona un archivo en el input `imagen`.
2. `image-preview.js` muestra la miniatura en la interfaz.
3. Al enviar, el controlador valida y mueve el archivo a `Assets/images/uploads/` (o similar) y guarda la `imagen_url` en la BD.

## Recomendaciones
- Probar la vista con permisos de usuario diferentes (propietario vs visitante).
- Separar la lógica de validación de archivos en una función reutilizable.
- Sanear y limitar el nombre de archivos o generar un UUID/nombre único para evitar colisiones.

## Checklist
- [ ] Añadir feedback visual de validación.
- [ ] Control de tamaño y tipo en el cliente (JS) y siempre verificar en servidor.
- [ ] Guardar metadatos de imagen (mimetype, tamaño) si es relevante.

