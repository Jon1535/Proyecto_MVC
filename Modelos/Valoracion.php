<?php
namespace Modelos;

use PDO;
use Exception;

class Valoracion {
    private $pdo;

    public function __construct() {
        $this->pdo = Basededatos::Conectar();
    }

    // Inserta en la tabla `calificacion`
    public function Crear(array $data) {
        try {
            $sql = "INSERT INTO calificacion (id_intercambio, id_evaluador, id_evaluado, puntuacion, comentario, fecha_calificacion) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['intercambio_id'],
                $data['evaluador_id'],
                $data['evaluado_id'],
                $data['puntuacion'],
                $data['comentario'] ?? null,
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Evita duplicar calificación del mismo evaluador en el mismo intercambio
    public function ExisteParaUsuario($intercambioId, $evaluadorId) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_calificacion FROM calificacion WHERE id_intercambio = ? AND id_evaluador = ? LIMIT 1");
            $stmt->execute([$intercambioId, $evaluadorId]);
            return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function ObtenerPorCalificado($usuarioId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM calificacion WHERE id_evaluado = ? ORDER BY fecha_calificacion DESC");
            $stmt->execute([$usuarioId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function PromedioPorUsuario($usuarioId) {
        try {
            $stmt = $this->pdo->prepare("SELECT AVG(puntuacion) AS promedio FROM calificacion WHERE id_evaluado = ?");
            $stmt->execute([$usuarioId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row && $row['promedio'] !== null ? (float)$row['promedio'] : 0.0;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
