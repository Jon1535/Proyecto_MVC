# Controlador: UsuarioControlador

**Archivo:** `Controladores/usuario.controlador.php`

## Propósito
Gestiona la autenticación y registro de usuarios: login, registro, logout, y manejo de sesiones básicas.

## Acciones públicas
- `Login()` — Muestra la vista `Vistas/Usuario/Login.php`.
- `Entrar()` — Procesa `POST` de login (`email`, `password`) y setea variables de `$_SESSION`.
- `Logout()` — Destruye la sesión y cookies asociadas.
- `Registro()` — Muestra el formulario de registro `Vistas/Usuario/Registro.php`.
- `Registrar()` — Procesa `POST` de registro y llama a `Usuario->Registrar()`.

## Flujo y validaciones
- `Entrar()` verifica método POST; llama a `VerificarLogin()` del modelo; si OK, setea `$_SESSION` con `id_usuario`, `nombre`, `email` y redirige al inicio.
- `Registrar()` valida campos básicos, compara contraseñas y evita duplicados llamando a `ObtenerPorEmail()` antes de `Registrar()`.

## Seguridad y mejoras sugeridas
- Llamar a `session_regenerate_id(true)` al autenticar para prevenir session fixation.
- Limitar intentos de login por IP o por cuenta para prevenir fuerza bruta.
- Sanitizar y validar emails con `filter_var()`.
- No incluir detalles en mensajes de error que indiquen si el email existe.
- Almacenar solo lo mínimo necesario en la sesión; si se requieren roles/permissions, considerar un esquema de permisos separado.
- Hacer `Registro()` y `Entrar()` resistentes a CSRF (tokens en formularios).

## Testing
- Testear `Entrar()` con credenciales válidas y no válidas usando un PDO en memoria o fixtures de usuario.
- Testear `Registrar()` para asegurar que no se permiten usuarios duplicados y que la contraseña se guarda hasheada (no en texto).

## Notas operativas
- El controlador usa `session_start()` — centralizar en `index.php`.
- `Usuario->Registrar()` actualmente espera un array de datos y devuelve id o false; documentar esa API en `Modelos/Usuario.php` para mantener contrato claro.
