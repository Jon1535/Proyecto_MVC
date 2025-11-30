<?php
session_start();

class IntercambioControlador {
    private $libroModelo;

    public function __construct() {
        $this->libroModelo = new \Modelos\Libro();
    }

    // Página para seleccionar libros del intercambio
    // Parámetro: id del libro solicitado (de otra persona)
    public function Seleccionar() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }
        $idSolicitado = (int)$_GET['id'];
        $libroSolicitado = $this->libroModelo->Obtener($idSolicitado);
        if (!$libroSolicitado) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        // Validar estado permitido (solo PUBLICADO puede intercambiarse)
        $estadoSolicitado = strtoupper(trim($libroSolicitado->estado ?? ''));
        if ($estadoSolicitado !== 'PUBLICADO') {
            header('Location: index.php?c=Libro&a=MisLibros&error=estado_no_disponible');
            exit;
        }

        // Biblioteca del usuario autenticado para elegir el libro propio
        $idUsuario = $_SESSION['id_usuario'] ?? null;
        $misLibros = [];
        if ($idUsuario) {
            $misLibros = $this->libroModelo->ListarPorPropietario($idUsuario);
        }

        require_once "Vistas/Encabezado.php";
        // variables disponibles para la vista: $libroSolicitado, $misLibros
        require_once "Vistas/Intercambio/Seleccion.php";
        require_once "Vistas/Pie.php";
    }

    // Confirmar selección y crear notificación para el propietario del libro solicitado
    public function Confirmar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }
        $idSolicitado = isset($_POST['id_libro_solicitado']) ? (int)$_POST['id_libro_solicitado'] : 0;
        $idMiLibro = isset($_POST['id_libro_mio']) ? (int)$_POST['id_libro_mio'] : 0;
        if ($idSolicitado <= 0 || $idMiLibro <= 0) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        $libroSolicitado = $this->libroModelo->Obtener($idSolicitado);
        $miLibro = $this->libroModelo->Obtener($idMiLibro);
        if (!$libroSolicitado || !$miLibro) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        // Validar estados: ambos deben estar PUBLICADO
        $estadoSolicitado = strtoupper(trim($libroSolicitado->estado ?? ''));
        $estadoMiLibro = strtoupper(trim($miLibro->estado ?? ''));
        if ($estadoSolicitado !== 'PUBLICADO' || $estadoMiLibro !== 'PUBLICADO') {
            header('Location: index.php?c=Libro&a=MisLibros&error=estado_no_disponible');
            exit;
        }

        // Bloquear ambos libros mientras está pendiente: OBSERVADO
        $this->libroModelo->ActualizarEstado($miLibro->id_libro, 'OBSERVADO');
        $this->libroModelo->ActualizarEstado($libroSolicitado->id_libro, 'OBSERVADO');

        // Notificación para el propietario del libro solicitado
        $destinatarioId = isset($libroSolicitado->id_propietario) ? (int)$libroSolicitado->id_propietario : 0;
        $remitenteId = $_SESSION['id_usuario'] ?? 0;
        if ($destinatarioId <= 0 || $remitenteId <= 0) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        $notif = [
            'id' => uniqid('n', true),
            'destinatario' => $destinatarioId,
            'remitente' => $remitenteId,
            'tipo' => 'intercambio_solicitado',
            'titulo' => 'Solicitud de intercambio',
            'mensaje' => sprintf('El usuario %s quiere intercambiar su libro "%s" por tu "%s".', $_SESSION['nombre'] ?? 'Usuario', $miLibro->titulo ?? 'Mi libro', $libroSolicitado->titulo ?? 'Libro'),
            'fecha' => date('c'),
            'leida' => false,
            'libro_ofrecido' => (int)$miLibro->id_libro,
            'libro_solicitado' => (int)$libroSolicitado->id_libro,
        ];

        $storePath = __DIR__ . '/../storage/notifications.json';
        if (!file_exists(dirname($storePath))) {
            @mkdir(dirname($storePath), 0777, true);
        }
        $list = [];
        if (file_exists($storePath)) {
            $json = file_get_contents($storePath);
            $list = json_decode($json, true) ?: [];
        }
        $list[] = $notif;
        file_put_contents($storePath, json_encode($list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        header('Location: index.php?c=Intercambio&a=Seleccionar&id=' . $idSolicitado . '&success=1');
        exit;
    }

    // Revisar una solicitud desde la notificación
    public function Revisar() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }
        $idNotif = isset($_GET['id']) ? (string)$_GET['id'] : '';
        $storePath = __DIR__ . '/../storage/notifications.json';
        $notif = null; $list = [];
        if (file_exists($storePath)) {
            $list = json_decode(file_get_contents($storePath), true) ?: [];
            foreach ($list as &$n) {
                if (isset($n['id']) && $n['id'] === $idNotif && (int)$n['destinatario'] === (int)$_SESSION['id_usuario']) {
                    $notif = $n;
                    $n['leida'] = true; // marcar como leída
                    break;
                }
            }
            // persistir posible cambio de leída
            file_put_contents($storePath, json_encode($list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        }
        if (!$notif || ($notif['tipo'] ?? '') !== 'intercambio_solicitado') {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        // Validar que la notificación tenga los identificadores requeridos
            // Validar que la notificación tenga datos completos (compatibilidad con notifs antiguas)
            if (!isset($notif['libro_solicitado']) || !isset($notif['libro_ofrecido'])) {
            // Notificación antigua o incompleta: evitar errores y redirigir
            header('Location: index.php?c=Libro&a=MisLibros&error=notificacion_incompleta');
            exit;
        }

        // Cargar libros involucrados
        $libroSolicitado = $this->libroModelo->Obtener((int)$notif['libro_solicitado']); // tu libro
        $libroOfrecido   = $this->libroModelo->Obtener((int)$notif['libro_ofrecido']);   // libro del solicitante

        if (!$libroSolicitado || !$libroOfrecido) {
            // Alguno de los libros ya no existe
            header('Location: index.php?c=Libro&a=MisLibros&error=libro_no_disponible');
            exit;
        }

        require_once "Vistas/Encabezado.php";
        require_once "Vistas/Intercambio/Revisar.php"; // usa $libroSolicitado, $libroOfrecido, $notif
        require_once "Vistas/Pie.php";
    }

    // Aceptar solicitud: notificar al remitente
    public function Aceptar() {
        $this->resolverDecision('aceptado');
    }

    // Rechazar solicitud: notificar al remitente
    public function Rechazar() {
        $this->resolverDecision('rechazado');
    }

    private function resolverDecision(string $decision) {
        if (!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }
        $idNotif = isset($_POST['id_notif']) ? (string)$_POST['id_notif'] : '';
        $storePath = __DIR__ . '/../storage/notifications.json';
        $list = file_exists($storePath) ? (json_decode(file_get_contents($storePath), true) ?: []) : [];
        $target = null;
        foreach ($list as &$n) {
            if (($n['id'] ?? '') === $idNotif && (int)$n['destinatario'] === (int)$_SESSION['id_usuario']) {
                $target = &$n;
                $n['leida'] = true;
                $n['estado'] = $decision;
                break;
            }
        }
        if (!$target) {
            header('Location: index.php?c=Libro&a=MisLibros&error=1');
            exit;
        }

        // Si se acepta, pasar ambos libros a ACORDADO; si se rechaza, devolver a PUBLICADO
        $nuevoEstado = $decision === 'aceptado' ? 'ACORDADO' : 'PUBLICADO';
        if (isset($target['libro_solicitado'])) {
            $this->libroModelo->ActualizarEstado((int)$target['libro_solicitado'], $nuevoEstado);
        }
        if (isset($target['libro_ofrecido'])) {
            $this->libroModelo->ActualizarEstado((int)$target['libro_ofrecido'], $nuevoEstado);
        }

        // Crear notificación de respuesta para el solicitante
        $libroSolicitado = null;
        $libroOfrecido = null;
        if (isset($target['libro_solicitado'])) {
            $tmp = $this->libroModelo->Obtener((int)$target['libro_solicitado']);
            if ($tmp) { $libroSolicitado = $tmp; }
        }
        if (isset($target['libro_ofrecido'])) {
            $tmp = $this->libroModelo->Obtener((int)$target['libro_ofrecido']);
            if ($tmp) { $libroOfrecido = $tmp; }
        }

        $tituloOfrecido = $libroOfrecido ? ($libroOfrecido->titulo ?? 'Tu libro') : 'Tu libro';
        $tituloSolicitado = $libroSolicitado ? ($libroSolicitado->titulo ?? 'Su libro') : 'Su libro';
        $notifRespuesta = [
            'id' => uniqid('n', true),
            'destinatario' => (int)$target['remitente'],
            'remitente' => (int)$_SESSION['id_usuario'],
            'tipo' => $decision === 'aceptado' ? 'intercambio_aceptado' : 'intercambio_rechazado',
            'titulo' => $decision === 'aceptado' ? 'Intercambio aceptado' : 'Intercambio rechazado',
            'mensaje' => $decision === 'aceptado'
                ? sprintf('Tu intercambio fue ACEPTADO: "%s" por "%s".', $tituloOfrecido, $tituloSolicitado)
                : sprintf('Tu intercambio fue RECHAZADO: "%s" por "%s".', $tituloOfrecido, $tituloSolicitado),
            'fecha' => date('c'),
            'leida' => false,
        ];
        $list[] = $notifRespuesta;
        file_put_contents($storePath, json_encode($list, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        header('Location: index.php?c=usuario&a=Perfil&success=1');
        exit;
    }
}
