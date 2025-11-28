# Método: Usuario::Registrar($data)

**Definición (archivo):** `Modelos/Usuario.php`

## Propósito
Registrar un nuevo usuario en la base de datos aplicando `password_hash()` al campo contraseña.

## Firma aproximada
- `public function Registrar($data)` — recibe array o entidad con `nombre`, `email`, `password`, `estado`.

## Parámetros
- `$data` (array) — datos del usuario a crear. Debe contener al menos `nombre`, `email`, `password`.

## Retorno
- `int|false` — id del usuario insertado o `false` en caso de fallo.

## Uso / llamadas detectadas
- `Controladores/usuario.controlador.php` — en `Registrar()` se llama a `$this->modelo->Registrar($data);`.
  - Archivo: `Controladores/usuario.controlador.php` (calls shown in controller).
- Documentación referenciada en `docs/obsidian/03_Codebase/Models/Usuario.md`.

## Seguridad
- El método aplica `password_hash()` — buena práctica.
- Verificar duplicidad de email antes de llamar a este método (lo hace el controlador en el flujo actual con `ObtenerPorEmail`).

## Recomendaciones
- Validar y sanitizar `$data` dentro del método o documentar claramente que debe validarse antes de llamar.
- Considerar transacciones si se agregan más entidades relacionadas (p. ej. perfiles).

## Enlaces
- Modelo: `Modelos/Usuario.php`
- Controlador: `Controladores/usuario.controlador.php` / `docs/obsidian/03_Codebase/Controllers/UsuarioControlador.md`
