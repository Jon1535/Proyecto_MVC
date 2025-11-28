# Modelo: Libro

**Archivo:** `Modelos/Libro.php`

## Propósito
Encapsular la lógica de persistencia y consulta para la entidad "Libro". Provee métodos CRUD y utilidades relacionadas con libros (listados, obtener por propietario, contar, etc.).

## Campos esperados en la tabla `libro` (según uso en el modelo)
- `id_libro` (PK)
- `titulo`
- `autor`
- `id_categoria`
- `descripcion`
- `imagen_url`
- `id_propietario`
- `estado`
- `condicion`
- `fecha_publicacion`

## Métodos principales
- `__construct()` — Inicializa `$this->pdo = Basededatos::Conectar();`
- `CantidadLibros()` — Retorna COUNT(*) como objeto.
- `Listar()` — Devuelve todos los libros (`SELECT * FROM libro`).
- `ListarPorPropietario($id_propietario)` — Libros filtrados por `id_propietario`.
- `Insertar(Libro $l)` — Inserta un libro y devuelve `lastInsertId()`.
- `Actualizar(Libro $l)` — Actualiza datos del libro (usa `id_libro` y `id_propietario` en WHERE para evitar modificar libros ajenos).
- `Obtener($id)` — Retorna un objeto `PDO::FETCH_OBJ` del libro con ese id.
- `Eliminar($id_libro)` — Borra registro y elimina archivo de imagen asociado en disco si existe.

## Ejemplos de uso (controlador)
```php
// Insertar nuevo libro (en LibroControlador::Guardar)
$libro = new Libro();
$libro->setTitulo('Mi Libro');
$libro->setAutor('Autor X');
// ... setear otras propiedades ...
$id = $this->modelo->Insertar($libro);
```

## Observaciones y puntos a mejorar
- El constructor llama directamente a `Basededatos::Conectar()`. Para facilitar tests sería preferible inyectar el PDO en el constructor:
```php
public function __construct(PDO $pdo = null){
    $this->pdo = $pdo ?: Basededatos::Conectar();
}
```
- Los `catch` actuales hacen `die($e->getMessage())`. Esto corta ejecución y no permite manejar errores en controladores ni hacer assertions en tests. Reemplazar `die()` por `throw` o `error_log()` y propagar la excepción.
- Las consultas usan `fetchAll(PDO::FETCH_OBJ)` lo cual está bien para vistas simples, pero en objetos más complejos se puede mapear a entidades.

## Recomendaciones para simplificar/educar
- Para aprender, puedes comentar o extraer la lógica de subida de imágenes fuera del modelo (el modelo no debe ocuparse de mover archivos), y dejar al controlador la responsabilidad de gestionar archivos y pasar solo la `imagen_url` al modelo.
- Añadir validaciones mínimas en el modelo (por ejemplo sanitizar campos breves) ayuda, pero dejar la validación principal en el controlador y en el HTML (atributo `required`).

## Notas para testing
- Con inyección de PDO, en tests puedes pasar un `new PDO('sqlite::memory:')`, crear la tabla `libro` en memoria y ejecutar `Insertar`, `Obtener`, `Listar` para asegurar que la lógica funciona.

