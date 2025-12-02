<?php
namespace Modelos;

use PDO;
use Exception;

class Entrega {
    private $pdo;

    public function __construct() {
        $this->pdo = Basededatos::Conectar();
    }

    public function Crear(array $data) {
        try {
            $sql = "INSERT INTO entrega (solicitud_id, intercambio_remitente_id, intercambio_destinatario_id, creador_id, tipo, punto_direccion, fecha_hora, notas, estado, fecha_creacion)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['solicitud_id'],
                $data['intercambio_remitente_id'],
                $data['intercambio_destinatario_id'],
                $data['creador_id'],
                $data['tipo'],
                $data['punto_direccion'],
                $data['fecha_hora'],
                $data['notas'] ?? null,
                $data['estado'] ?? 'PENDIENTE_CONFIRMACION',
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM entrega WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function ObtenerPorSolicitud($solicitudId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM entrega WHERE solicitud_id = ? LIMIT 1");
            $stmt->execute([$solicitudId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function ActualizarEstado($id, $estado) {
        try {
            $stmt = $this->pdo->prepare("UPDATE entrega SET estado = ? WHERE id = ?");
            return $stmt->execute([strtoupper($estado), $id]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
