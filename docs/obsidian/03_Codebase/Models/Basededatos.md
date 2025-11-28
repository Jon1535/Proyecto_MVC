# Modelo: Basededatos

**Archivo:** `Modelos/Basededatos.php`

## Propósito
Proveer una función centralizada para obtener la conexión PDO a la base de datos.

## Implementación actual (resumen)
- Constantes con credenciales hardcoded:
  - `servidor = "localhost"`
  - `usuariobd = "root"`
  - `contra = ""`
  - `nombredb = "bd_intercambio_libros"`
- Método principal: `Conectar()` que crea y devuelve un objeto `PDO` configurado con `ATTR_ERRMODE => ERRMODE_EXCEPTION`.
- En caso de fallo actualmente captura `PDOException` y devuelve una cadena con el mensaje de error.

## Interfaz pública
- `Basededatos::Conectar()` → `PDO` (o actualmente una cadena en caso de error)

## Ejemplo de uso
```php
use Modelos\Basededatos;

$pdo = Basededatos::Conectar();
$stmt = $pdo->prepare("SELECT * FROM usuario");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
```

## Problemas observados
- Credenciales en el código fuente (riesgo de seguridad). Deberían leerse desde variables de entorno o un `.env` fuera del repo.
- `Conectar()` devuelve una cadena en caso de error: mezclar tipos (PDO|string) complica el manejo y puede producir errores en tiempo de ejecución.
- Recomendada lanzar la excepción en lugar de devolver cadena, así el llamador decide cómo manejarla.

## Recomendaciones/Refactor sugerido
1. Leer credenciales desde variables de entorno (ejemplo con `getenv()`), y mantener un `.env.example` en el repo:
```php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'bd_intercambio_libros';
```
2. Hacer que `Conectar()` lance la excepción en fallo:
```php
public static function Conectar(){
    try {
        $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        throw $e;
    }
}
```
3. Añadir tests que verifiquen la creación de la conexión usando SQLite en memoria para no depender de la BD real.

## Notas para testing
- En tests unitarios, en lugar de usar la BD MySQL real, se puede inyectar un PDO de SQLite en memoria. Para ello, las clases que usan `Basededatos::Conectar()` deberían permitir inyección de dependencia (p. ej. recibir el PDO en el constructor) o usar un método estático para reemplazar la conexión durante tests.

