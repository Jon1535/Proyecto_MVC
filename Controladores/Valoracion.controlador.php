<?php
session_start();

class ValoracionControlador
{
    private function rutaNotificaciones()
    {
        return dirname(__DIR__) . '/almacenamiento/notificaciones.json';
    }

    public function Crear()
    {
        if (!isset($_GET['intercambio'])) {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }
        $intercambioId = (int)$_GET['intercambio'];
        $uid = $_SESSION['id_usuario'] ?? null;
        if (!$uid) { header('Location: index.php?c=usuario&a=Login'); exit; }
        $interModel = new \\Modelos\\Intercambio();
        $row = $interModel->Obtener($intercambioId);
        if (!$row) { header('Location: index.php?c=Libro&a=Biblioteca&error=1'); exit; }

        // Validar participación y estado (al menos una confirmación)
        $participa = ((int)$uid === (int)$row['id_usuario_a']) || ((int)$uid === (int)$row['id_usuario_b']);
        $confirmada = ((int)($row['confirmacion_a'] ?? 0) === 1) || ((int)($row['confirmacion_b'] ?? 0) === 1);
        if (!$participa || !$confirmada) {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }

        $calificadoId = ((int)$uid === (int)$row['id_usuario_a']) ? (int)$row['id_usuario_b'] : (int)$row['id_usuario_a'];

        // Evitar doble valoración por intercambio
        $valorModel = new \\Modelos\\Valoracion();
        if ($valorModel->ExisteParaUsuario($intercambioId, (int)$uid)) {
            header('Location: index.php?c=Libro&a=Biblioteca&success=valoracion_existente');
            exit;
        }

        require_once 'Vistas/Encabezado.php';
        // Variables: $intercambioId, $calificadoId
        require_once 'Vistas/Valoracion/Crear.php';
        require_once 'Vistas/Pie.php';
    }

    public function Guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }
        $uid = $_SESSION['id_usuario'] ?? null;
        if (!$uid) { header('Location: index.php?c=usuario&a=Login'); exit; }

        $intercambioId = (int)($_POST['intercambio_id'] ?? 0);
        $calificadoId = (int)($_POST['calificado_id'] ?? 0);
        $puntuacion = (int)($_POST['puntuacion'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');

        if ($intercambioId <= 0 || $calificadoId <= 0 || $puntuacion < 1 || $puntuacion > 5) {
            header('Location: index.php?c=Valoracion&a=Crear&intercambio=' . urlencode($intercambioId) . '&error=1');
            exit;
        }

        $interModel = new \\Modelos\\Intercambio();
        $row = $interModel->Obtener($intercambioId);
        if (!$row) { header('Location: index.php?c=Libro&a=Biblioteca&error=1'); exit; }
        $participa = ((int)$uid === (int)$row['id_usuario_a']) || ((int)$uid === (int)$row['id_usuario_b']);
        $confirmada = ((int)($row['confirmacion_a'] ?? 0) === 1) || ((int)($row['confirmacion_b'] ?? 0) === 1);
        if (!$participa || !$confirmada) {
            header('Location: index.php?c=Libro&a=Biblioteca&error=1');
            exit;
        }

        // Crear calificación en tabla `calificacion`
        $valorModel = new \Modelos\Valoracion();
        if ($valorModel->ExisteParaUsuario($intercambioId, (int)$uid)) {
            header('Location: index.php?c=Libro&a=Biblioteca&success=valoracion_existente');
            exit;
        }
        $valorModel->Crear([
            'intercambio_id' => $intercambioId,
            'evaluador_id' => (int)$uid,
            'evaluado_id' => $calificadoId,
            'puntuacion' => $puntuacion,
            'comentario' => $comentario,
        ]);

        // Recalcular promedio y actualizar reputación en usuario
        $promedio = $valorModel->PromedioPorUsuario($calificadoId);
        $usuarioModel = new \Modelos\Usuario();
        $usuarioModel->ActualizarReputacion($calificadoId, number_format($promedio, 2, '.', ''));

        // Notificar a la persona calificada
        $notifs = json_decode(@file_get_contents($this->rutaNotificaciones()), true) ?: [];
        $notifs[] = [
            'id' => uniqid('n', true),
            'destinatario' => $calificadoId,
            'remitente' => (int)$uid,
            'tipo' => 'valoracion_recibida',
            'titulo' => 'Has recibido una valoración',
            'mensaje' => 'Te han dejado una valoración sobre un intercambio.',
            'fecha' => date('c'),
            'leida' => false,
            'intercambio_id' => $intercambioId,
        ];
        file_put_contents($this->rutaNotificaciones(), json_encode($notifs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        header('Location: index.php?c=Libro&a=Biblioteca&success=valoracion_guardada');
        exit;
    }
}
