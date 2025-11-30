<?php
require_once __DIR__ . '/../Modelos/Usuario.php';

class NotificacionesControlador
{
    private function getProjectRoot()
    {
        return dirname(__DIR__);
    }

    private function notificationsPath()
    {
        $root = $this->getProjectRoot();
        $dir = $root . '/storage';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        return $dir . '/notifications.json';
    }

    private function readNotifications()
    {
        $path = $this->notificationsPath();
        if (!file_exists($path)) {
            return [];
        }
        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function writeNotifications(array $notifications)
    {
        $path = $this->notificationsPath();
        $json = json_encode($notifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($path, $json);
    }

    public function Limpiar()
    {
        session_start();
        // En esta app el Encabezado.php usa $_SESSION['id_usuario'] directamente
        $usuarioId = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
        if ($usuarioId === null) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }

        $all = $this->readNotifications();
        $remaining = [];
        foreach ($all as $n) {
            // En el encabezado se filtra por clave 'destinatario'
            if (!isset($n['destinatario']) || (string)$n['destinatario'] !== (string)$usuarioId) {
                $remaining[] = $n;
            }
        }

        $this->writeNotifications($remaining);

        // Redirect back to the previous page or home
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php?c=inicio&a=Principal';
        // Append a success flag
        if (strpos($redirect, '?') === false) {
            $redirect .= '?notificaciones=limpiadas';
        } else {
            $redirect .= '&notificaciones=limpiadas';
        }
        header('Location: ' . $redirect);
        exit;
    }
}
