<?php
/**
 * bootstrap.php - Configuración inicial para PHPUnit
 * 
 * Este archivo se carga antes de ejecutar cualquier test.
 * Aquí se incluyen todas las dependencias y configuraciones necesarias.
 */

// Definir constantes del proyecto
define('ROOT_DIR', dirname(__DIR__));
define('MODELS_DIR', ROOT_DIR . '/Modelos');
define('CONTROLLERS_DIR', ROOT_DIR . '/Controladores');
define('VIEWS_DIR', ROOT_DIR . '/Vistas');

// Incluir autoloader de Composer
require_once ROOT_DIR . '/vendor/autoload.php';

// Incluir modelos
require_once MODELS_DIR . '/Basededatos.php';
require_once MODELS_DIR . '/Libro.php';
require_once MODELS_DIR . '/Usuario.php';

// Incluir controladores (opcional, si los tests lo necesitan)
// require_once CONTROLLERS_DIR . '/Libro.controlador.php';
// require_once CONTROLLERS_DIR . '/Usuario.controlador.php';

// ============================================================================
// CONFIGURACIÓN DE BD PARA TESTS
// ============================================================================

// Para tests, usar BD de prueba (opcional)
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'proyecto_mvc_test');

// ============================================================================
// HELPER FUNCTIONS PARA TESTS
// ============================================================================

/**
 * Simula login de usuario en tests
 * 
 * @param int $id_usuario
 * @param string $nombre
 * @param string $email
 */
function loginTestUser($id_usuario, $nombre, $email)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['id_usuario'] = $id_usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['email'] = $email;
    $_SESSION['autenticado'] = true;
}

/**
 * Simula logout de usuario en tests
 */
function logoutTestUser()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    unset($_SESSION['id_usuario']);
    unset($_SESSION['nombre']);
    unset($_SESSION['email']);
    unset($_SESSION['autenticado']);
    
    if (session_id()) {
        session_destroy();
    }
}

/**
 * Limpia variables globales después de cada test
 */
function cleanupGlobals()
{
    $_POST = [];
    $_GET = [];
    $_FILES = [];
    $_SESSION = [];
}
