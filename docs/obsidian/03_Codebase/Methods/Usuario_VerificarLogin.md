# Método: Usuario::VerificarLogin($email, $password)

**Definición (archivo):** `Modelos/Usuario.php`

## Propósito
Verificar las credenciales de un usuario: recuperar por email y comprobar `password_verify($password, $hash)`.

## Firma aproximada
- `public function VerificarLogin($email, $password)`

## Parámetros
- `$email` (string)
- `$password` (string) — contraseña en texto plano recibida por POST.

## Retorno
- `object|false` — objeto usuario si las credenciales son correctas, `false` en caso contrario.

## Uso / llamadas detectadas
- `Controladores/usuario.controlador.php` — en `Entrar()` se llama a `$user = $this->modelo->VerificarLogin($email, $password);`.
  - Archivo: `Controladores/usuario.controlador.php`.
- Documentación de la vista `Usuario_Login` menciona esta llamada (`docs/obsidian/03_Codebase/Views/Usuario_Login.md`).

## Seguridad y recomendaciones
- Usar `password_verify()` (ya implementado) y regenerar el id de sesión (`session_regenerate_id(true)`) después de un login exitoso.
- Limitar intentos de login y registrar intentos fallidos para detectar abuso.

## Enlaces
- Modelo: `Modelos/Usuario.php`
- Controlador: `Controladores/usuario.controlador.php` / `docs/obsidian/03_Codebase/Controllers/UsuarioControlador.md`
