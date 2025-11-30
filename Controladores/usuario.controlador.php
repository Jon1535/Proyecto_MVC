<?php
session_start();

class UsuarioControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new \Modelos\Usuario();
    }

    // Mostrar formulario de login
    public function Login() {
        // Vista independiente (sin encabezado/sidebars)
        require_once "Vistas/Usuario/Login.php";
        require_once "Vistas/Pie.php";
    }

    // Procesar POST de login
    public function Entrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $user = $this->modelo->VerificarLogin($email, $password);
        if ($user) {
            // Setear variables de sesión (asegúrate de regenerar id si es necesario)
            $_SESSION['id_usuario'] = $user->id_usuario;
            $_SESSION['nombre'] = $user->nombre;
            $_SESSION['email'] = $user->email;
            $_SESSION['fecha_registro'] = $user->fecha_registro ?? null;$_SESSION['fecha_registro'] = $user->fecha_registro ?? null;
            // redirigir al index raíz (mostrará Inicio)
            header('Location: index.php');
            exit;
        } else {
            // login fallido
            header('Location: index.php?c=usuario&a=Login&error=1');
            exit;
        }
    }

    // Logout
    public function Logout() {
        // destruir sesión
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php?c=usuario&a=Login');
        exit;
    }

    // Mostrar formulario de registro
    public function Registro() {
        require_once "Vistas/Usuario/Registro.php";
        require_once "Vistas/Pie.php";
    }

    // Procesar POST de registro
    public function Registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=usuario&a=Registro');
            exit;
        }

        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

        // validaciones básicas
        if (empty($nombre) || empty($email) || empty($password)) {
            header('Location: index.php?c=usuario&a=Registro&error=1');
            exit;
        }
        if ($password !== $password2) {
            header('Location: index.php?c=usuario&a=Registro&error=2');
            exit;
        }

        // evitar duplicados
        $exists = $this->modelo->ObtenerPorEmail($email);
        if ($exists) {
            header('Location: index.php?c=usuario&a=Registro&error=3');
            exit;
        }

        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'estado' => 'ACTIVO'
        ];

        $id = $this->modelo->Registrar($data);
        if ($id) {
            // redirigir al login con success
            header('Location: index.php?c=usuario&a=Login&registered=1');
            exit;
        } else {
            header('Location: index.php?c=usuario&a=Registro&error=4');
            exit;
        }
    }

    // Página principal del perfil de usuario (requiere sesión)
    public function Perfil() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }
        // Datos básicos del usuario desde la sesión
        $usuario = (object) [
            'id_usuario' => $_SESSION['id_usuario'] ?? null,
            'nombre' => $_SESSION['nombre'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'fecha_registro' => $_SESSION['fecha_registro'] ?? null,
        ];
        require_once "Vistas/Encabezado.php";
        require_once "Vistas/Usuario/Perfil.php";
        require_once "Vistas/Pie.php";
    }

    // Mostrar formulario de edición de perfil
    public function Editar() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }
        $usuario = (object) [
            'id_usuario' => $_SESSION['id_usuario'] ?? null,
            'nombre' => $_SESSION['nombre'] ?? '',
            'email' => $_SESSION['email'] ?? '',
        ];
        require_once "Vistas/Encabezado.php";
        require_once "Vistas/Usuario/EditarPerfil.php";
        require_once "Vistas/Pie.php";
    }

    // Procesar actualización de perfil
    public function ActualizarPerfil() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Perfil&error=1'); // acceso inválido
            exit;
        }

        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

        // Validaciones básicas
        if ($nombre === '' || $email === '') {
            header('Location: index.php?c=usuario&a=Editar&error=2'); // faltan campos
            exit;
        }
        if ($password !== '' && $password !== $password2) {
            header('Location: index.php?c=usuario&a=Editar&error=3'); // pass no coincide
            exit;
        }

        // Verificar duplicado de email si cambió
        $emailActual = $_SESSION['email'] ?? '';
        if ($email !== $emailActual) {
            $existe = $this->modelo->ObtenerPorEmail($email);
            if ($existe && $existe->id_usuario != $_SESSION['id_usuario']) {
                header('Location: index.php?c=usuario&a=Editar&error=4'); // email duplicado
                exit;
            }
        }

        $data = [
            'nombre' => $nombre,
            'email' => $email,
        ];
        if ($password !== '') {
            $data['password'] = $password;
        }

        $ok = $this->modelo->Actualizar($_SESSION['id_usuario'], $data);
        if ($ok) {
            // Actualizar sesión para reflejar cambios
            $_SESSION['nombre'] = $nombre;
            $_SESSION['email'] = $email;
            header('Location: index.php?c=usuario&a=Perfil&success=1');
            exit;
        } else {
            header('Location: index.php?c=usuario&a=Editar&error=5'); // fallo update
            exit;
        }
    }
}
