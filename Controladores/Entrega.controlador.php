<?php
// Controlador obsoleto: la lógica de entrega se movió a Intercambio.
session_start();

class EntregaControlador
{
    private function rutaNotificaciones()
    {
        return dirname(__DIR__) . '/almacenamiento/notificaciones.json';
    }

    private function cargarSolicitud($idNotif)
    {
        if (!file_exists($this->rutaNotificaciones())) return null;
        $list = json_decode(@file_get_contents($this->rutaNotificaciones()), true) ?: [];
        foreach ($list as $n) {
            if (($n['id'] ?? null) === $idNotif && ($n['tipo'] ?? '') === 'intercambio_solicitado') {
                return $n;
            }
        }
        return null;
    }

    private function assertAutorizadoSolicitud($notif)
    {
        if (!isset($_SESSION['id_usuario'])) return false;
        $uid = (int)$_SESSION['id_usuario'];
        return $notif && (($uid === (int)$notif['destinatario']) || ($uid === (int)$notif['remitente']));
    }

    public function Crear()
    {
        header('Location: index.php?c=Intercambio&a=Programar');
        exit;
    }

    public function Guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }
        $idNotif = (string)($_POST['solicitud_id'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $notif = $this->cargarSolicitud($idNotif);
        if (!$notif || ($notif['estado'] ?? '') !== 'aceptado' || !$this->assertAutorizadoSolicitud($notif)) {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }

        // Validación mínima por tipo
        $payload = [
            'id' => uniqid('e', true),
            'solicitud_id' => $idNotif,
            'solicitante_id' => (int)$notif['remitente'],
            'destinatario_id' => (int)$notif['destinatario'],
            'creador_id' => (int)$_SESSION['id_usuario'],
            'tipo' => $tipo,
            'estado' => 'pendiente_confirmacion',
            'fecha' => date('c'),
            'notas' => trim($_POST['notas'] ?? '')
        ];

        if ($tipo === 'PUNTO_SEGURO') {
            $payload['punto_direccion'] = trim($_POST['punto_direccion'] ?? '');
            $payload['fecha_hora'] = trim($_POST['fecha_hora'] ?? '');
            if ($payload['punto_direccion'] === '' || $payload['fecha_hora'] === '') {
                header('Location: index.php?c=Entrega&a=Crear&solicitud=' . urlencode($idNotif) . '&error=1');
                exit;
            }
        } else {
            header('Location: index.php?c=Entrega&a=Crear&solicitud=' . urlencode($idNotif) . '&error=1');
            exit;
        }

        header('Location: index.php?c=Intercambio&a=Programar');
        exit;
    }

    public function Ver()
    {
        header('Location: index.php?c=Intercambio&a=Ver');
        exit;
    }

    public function Confirmar()
    {
        $this->resolver('confirmada');
    }

    public function Rechazar()
    {
        $this->resolver('rechazada');
    }

    private function resolver($nuevoEstado)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }
        header('Location: index.php?c=Intercambio&a=Ver');
        exit;
    }
}
