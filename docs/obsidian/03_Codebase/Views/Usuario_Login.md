# Vista: Usuario - Login

**Archivo:** `Vistas/Usuario/Login.php`

## Propósito
Formulario de acceso para usuarios (login). Permite introducir email y contraseña y redirigir al panel principal si las credenciales son válidas.

## Campos del formulario
- `email` (type=email, required)
- `password` (type=password, required)

## Dependencias (JS/CSS)
- `Assets/css/main.css`
- `Assets/js/jquery-3.7.0.min.js` (si hay validación cliente)
- `Assets/js/login-flip.js` (si la vista usa efecto flip entre login/registro)

## Flujo del login
1. Usuario envía `POST` a `?c=Usuario&a=Entrar`.
2. `usuario.controlador.php` llama a `Usuario->VerificarLogin($email, $password)`.
3. Si OK, se inicia sesión (`$_SESSION['usuario']`) y se redirige a `?c=Inicio`.
4. Si falla, se muestra mensaje de error sin indicar si el email existe (mejor por seguridad).

## Recomendaciones de seguridad
- Limitar intentos de login o añadir backoff para prevenir ataques de fuerza bruta.
- No mostrar mensajes que confirmen la existencia del email.
- Usar `session_regenerate_id(true)` al autenticar para prevenir fixation.
- Forzar HTTPS en producción.

## Checklist
- [ ] Añadir validación y sanitización del email con `filter_var()`.
- [ ] Implementar mensajes de error genéricos para autenticación fallida.
- [ ] Considerar integración con OAuth o 2FA si es un requisito futuro.

