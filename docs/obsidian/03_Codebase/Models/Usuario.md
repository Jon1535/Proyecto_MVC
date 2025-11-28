# Modelo: Usuario

**Archivo:** `Modelos/Usuario.php`

## Propósito
Gestionar la persistencia y lógica de usuarios: registro, autenticación, recuperación por email/id y CRUD básico.

## Campos esperados en la tabla `usuario` (según uso en el modelo)
- `id_usuario` (PK)
- `nombre`
- `email` (único)
- `password` (hash)
- `rol` (opcional)
- `fecha_registro`

## Métodos principales
- `__construct()` — Inicializa `$this->pdo = Basededatos::Conectar();`
- `Obtener($id)` — Devuelve usuario por id.
- `ObtenerPorEmail($email)` — Devuelve usuario por email (utilizado en login/registro).
- `Registrar(Usuario $u)` — Inserta un usuario; antes aplica `password_hash()`.
- `VerificarLogin($email, $password)` — Recupera por email y verifica `password_verify()`.
- `Actualizar(Usuario $u)` — Actualiza datos del usuario.
- `Eliminar($id_usuario)` — Elimina el usuario.

## Flujo de registro/login (controlador)
- `Registrar` en `usuario.controlador.php` recibe `$_POST`, valida y llama a `Usuario->Registrar()`.
- `Entrar` recupera por email y llama a `VerificarLogin`, si OK inicia `$_SESSION['usuario']`.

## Observaciones de seguridad
- Uso correcto de `password_hash()` y `password_verify()` — excelente.
- El código actual guarda credenciales de la DB en `Modelos/Basededatos.php` como texto plano. Mover a variables de entorno (`.env`) evita subir credenciales al repo.
- Los errores en el modelo usan `die()` en catch — mejor lanzar excepciones para que el controlador maneje respuestas amigables.

## Recomendaciones educativas
- Añadir verificación de fuerza de contraseña (mínimo 8 caracteres, mezcla de tipos) en el controlador de registro.
- Normalizar el email con `filter_var($email, FILTER_VALIDATE_EMAIL)` antes de usarlo.
- Evitar exponer mensajes de error SQL al usuario final; registrar errores en logs.

## Notas para testing
- Inyectar un `PDO` de test (`sqlite::memory:`) permite probar `Registrar`, `VerificarLogin`, `ObtenerPorEmail` sin tocar la DB real.

