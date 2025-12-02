<?php
namespace Modelos;

use PDO;
use Exception;

class Intercambio {
    private $pdo;

    public function __construct() {
        $this->pdo = Basededatos::Conectar();
    }

    public function Crear($idSolicitud = 0, $fechaAcuerdo = null, $usuarioA = null, $usuarioB = null) {
        try {
            $fecha = $fechaAcuerdo ?: date('Y-m-d H:i:s');
            $sql = "INSERT INTO intercambio (id_solicitud, fecha_acuerdo, fecha_entrega, confirmacion_a, confirmacion_b, id_usuario_a, id_usuario_b, tipo, punto_direccion, notas)
                    VALUES (?, ?, NULL, 0, 0, ?, ?, 'PUNTO_SEGURO', NULL, NULL)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([(int)$idSolicitud, $fecha, $usuarioA, $usuarioB]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM intercambio WHERE id_intercambio = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Programar($id, $tipo, $direccion, $fechaHora, $notas = null) {
        try {
            $stmt = $this->pdo->prepare("UPDATE intercambio SET tipo = ?, punto_direccion = ?, fecha_entrega = ?, notas = ? WHERE id_intercambio = ?");
            return $stmt->execute([$tipo, $direccion, $fechaHora, $notas, $id]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Confirmar($id, $usuarioId) {
        try {
            $row = $this->Obtener($id);
            if (!$row) return false;
            $campo = ((int)$row['id_usuario_a'] === (int)$usuarioId) ? 'confirmacion_a' : 'confirmacion_b';
            $stmt = $this->pdo->prepare("UPDATE intercambio SET $campo = 1 WHERE id_intercambio = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Rechazar($id, $usuarioId) {
        try {
            $row = $this->Obtener($id);
            if (!$row) return false;
            $campo = ((int)$row['id_usuario_a'] === (int)$usuarioId) ? 'confirmacion_a' : 'confirmacion_b';
            $stmt = $this->pdo->prepare("UPDATE intercambio SET $campo = 0 WHERE id_intercambio = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
