# Método: Basededatos::Conectar()

**Definición (archivo):** `Modelos/Basededatos.php`

## Propósito
Crear y devolver una instancia de `PDO` conectada a la base de datos MySQL del proyecto.

## Firma aproximada
- `public static function Conectar(): PDO` (en la práctica el proyecto actual devuelve `PDO` o una cadena de error en casos de fallo).

## Parámetros
Ninguno.

## Retorno
- `PDO` en caso de éxito.
- Actualmente el código puede devolver una cadena con mensaje de error en caso de error (recomendado cambiar a lanzar excepciones).

## Uso / llamadas detectadas
- `Modelos/Libro.php` — constructor: inicializa `$this->pdo = Basededatos::Conectar()`.
  - Archivo: `Modelos/Libro.php` (línea: inicialización en constructor).
- `Modelos/Usuario.php` — constructor: inicializa `$this->pdo = Basededatos::Conectar()`.
  - Archivo: `Modelos/Usuario.php` (línea: inicialización en constructor).
- Documentación: `docs/obsidian/03_Codebase/Models/Basededatos.md` (menciona el método y recomendaciones).

## Recomendaciones
- Cambiar la función para que lance `Exception` en errores en lugar de devolver una cadena.
- Permitir inyección de `PDO` (parámetro opcional en constructores de modelos) para facilitar tests.
- Mover credenciales a variables de entorno (`.env`) y no mantenerlas en el repo.

## Enlaces
- Modelo: `Modelos/Basededatos.php`
- Docs modelo: `docs/obsidian/03_Codebase/Models/Basededatos.md`
