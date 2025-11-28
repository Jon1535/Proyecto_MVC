# Resumen general de la aplicación — Proyecto_MVC

## 1) Propósito

Es una aplicación web (PHP) para intercambio/publicación de libros entre usuarios. Permite que los usuarios se registren, suban libros con fotos, los editen, los eliminen y los busquen mediante autocompletado.

## 2) Funcionalidad principal

- Registro y login de usuarios (hash de contraseña, sesiones).
- CRUD de libros:
  - Crear libro: formulario con título, autor, categoría, condición, descripción y subida de imágenes (múltiples; la primera sirve de portada).
  - Editar libro: validación de propietario antes de permitir cambios.
  - Eliminar libro: valida propietario y elimina la imagen asociada.
  - Listar libros: vista pública y lista por propietario (MisLibros).
- Búsqueda/autocompletado: endpoint JSON para sugerencias (hasta 10 resultados).
- Interfaz (UI): plantillas PHP con Bootstrap (tema Vali Admin).
- Tests: suite mínima con PHPUnit (tests básicos en `tests/LibroTest.php`).

## 3) Estructura del proyecto

- `index.php` — Front controller: enruta por `?c=CONTROLADOR&a=ACCION`.
- `Controladores/` — `Inicio.controlador.php`, `Libro.controlador.php`, `usuario.controlador.php`, `Busqueda.controlador.php`.
- `Modelos/` — `Basededatos.php`, `Libro.php`, `Usuario.php`.
- `Vistas/` — `Encabezado.php`, `Pie.php`, y subcarpetas `Inicio/`, `Libros/`, `Usuario/`.
- `Assets/` — `css/`, `js/`, `images/` (incluye uploads en `Assets/images/uploads/`).
- `tests/` — PHPUnit tests.
- `composer.json` — PSR-4 autoload configurado para `Modelos\` y `Controladores\`.

## 4) Estado técnico actual

- La aplicación sigue el patrón MVC y funciona como una app PHP convencional.
- `Modelos/Basededatos.php` usa PDO con credenciales hardcoded (host `localhost`, user `root`, db `bd_intercambio_libros`).
- En varios modelos se usa `die()` dentro de bloques `catch`, lo que dificulta el manejo de errores y las pruebas.
- Tests actuales: básicos y de baja cobertura.

## 5) Puntos que pueden complicar el aprendizaje (para un principiante)

- Tema Vali Admin y muchos assets: distraen del aprendizaje de la lógica PHP.
- Enrutamiento dinámico sin whitelist en `index.php`: confuso y potencialmente inseguro.
- Credenciales de BD en el código y uso de `die()` impiden pruebas limpias.
- Repetición de `session_start()` en varios controladores en lugar de centralizar en `index.php`.
- Subida de múltiples imágenes con limpieza y validaciones complejas: correcto pero puede simplificarse.

## 6) Recomendaciones (pasos pequeños y seguros)

1. Centralizar arranque en `index.php`: agregar `session_start();` y `require 'vendor/autoload.php';` al inicio.
2. Añadir una whitelist de controladores en `index.php` para evitar inclusiones inesperadas.
3. Crear un `.env.example` y modificar `Modelos/Basededatos.php` para leer variables de entorno (evita credenciales en el repo).
4. Reemplazar `die()` por `throw` o `error_log()` en los `catch` para permitir manejo centralizado de errores.
5. Si el tema visual resulta abrumador, usar una plantilla más simple basada en Bootstrap o reducir el CSS/JS hasta entender la lógica.
6. Para tests, usar SQLite en memoria o mocks de PDO para no tocar la BD real y facilitar pruebas de integración.

## 7) Primeros cambios sugeridos (recomendado empezar aquí)

- A) Centralizar `session_start()` y `vendor/autoload.php` en `index.php` (cambio pequeño y reversible).
- B) Añadir whitelist de controladores permitidos en `index.php`.

Si quieres, aplico A+B ahora y te muestro exactamente los cambios realizados. También puedo crear la nota en Obsidian con enlaces a los archivos relevantes (ya está creada en esta ruta).