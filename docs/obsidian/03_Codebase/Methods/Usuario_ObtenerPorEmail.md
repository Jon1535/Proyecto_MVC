# Método: Usuario::ObtenerPorEmail($email)

**Definición (archivo):** `Modelos/Usuario.php`

## Propósito
Recuperar un usuario por su correo electrónico. Utilizado para verificar duplicados y para el inicio de sesión.

## Firma aproximada
- `public function ObtenerPorEmail($email)`

## Parámetros
- `$email` (string) — correo electrónico a buscar.

## Retorno
- `object|null` — objeto usuario si existe, `null`/`false` si no.

## Uso / llamadas detectadas
- `Controladores/usuario.controlador.php` — en `Registrar()` para comprobar si el email ya existe: `$exists = $this->modelo->ObtenerPorEmail($email);`.
- `Controladores/usuario.controlador.php` / `Entrar()` indirectamente usa `VerificarLogin()` que internamente llama a `ObtenerPorEmail()` (según la implementación del modelo).

## Recomendaciones
- Asegurar que el email esté validado con `filter_var()` antes de llamar.
- No retornar la contraseña en texto; devolver solo los campos necesarios.

## Enlaces
- Modelo: `Modelos/Usuario.php`
- Controlador: `Controladores/usuario.controlador.php` / `docs/obsidian/03_Codebase/Controllers/UsuarioControlador.md`
