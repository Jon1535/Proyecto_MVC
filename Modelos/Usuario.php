<?php
namespace Modelos;

use PDO;
use Exception;

class Usuario {
    private $pdo;

    private $id_usuario;
    private $nombre;
    private $email;
    private $password; // almacenará el hash
    private $fecha_registro;
    private $estado;

    public function __construct() {
        $this->pdo = Basededatos::Conectar();
    }

    // Getters / Setters
    public function getId() { return $this->id_usuario; }
    public function setId($id) { $this->id_usuario = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    // setPassword guarda el hash
    public function setPassword($password) {
        // Si ya parece un hash (por ejemplo viene desde la BD), se deja tal cual.
        // Asumimos que las contraseñas claras se envían aquí y deben hashearse.
        if (password_get_info($password)['algo'] === 0) {
            // no es hash
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
    }

    public function getPassword() { return $this->password; }

    public function getFecha() { return $this->fecha_registro; }
    public function setFecha($f) { $this->fecha_registro = $f; }

    public function getEstado() { return $this->estado; }
    public function setEstado($e) { $this->estado = $e; }

    // Listar todos los usuarios
    public function Listar() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_usuario, nombre, email, pass_hash, fecha_registro, estado, reputacion_promedio FROM usuario");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Obtener usuario por id
    public function Obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_usuario, nombre, email, pass_hash, fecha_registro, estado, reputacion_promedio FROM usuario WHERE id_usuario = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Obtener usuario por email
    public function ObtenerPorEmail($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_usuario, nombre, email, pass_hash, fecha_registro, estado, reputacion_promedio FROM usuario WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Registrar nuevo usuario. $data puede ser array con keys: nombre,email,password,rol,estado
    public function Registrar($data) {
        try {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            // Insert only columns that exist in the current DB schema. fecha_registro has a default.
            $stmt = $this->pdo->prepare("INSERT INTO usuario (nombre, email, pass_hash, estado) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $hash,
                $data['estado'] ?? 'ACTIVO'
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Actualizar usuario (no actualiza password a menos que venga en $data)
    public function Actualizar($id, $data) {
        try {
            $fields = [];
            $params = [];
            if (isset($data['nombre'])) { $fields[] = 'nombre = ?'; $params[] = $data['nombre']; }
            if (isset($data['email'])) { $fields[] = 'email = ?'; $params[] = $data['email']; }
            // 'rol' column not present in the DB; ignore if provided
            if (isset($data['estado'])) { $fields[] = 'estado = ?'; $params[] = $data['estado']; }
            if (isset($data['password']) && !empty($data['password'])) {
                $fields[] = 'pass_hash = ?';
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            if (count($fields) === 0) return false;
            $params[] = $id;
            $sql = "UPDATE usuario SET " . implode(', ', $fields) . " WHERE id_usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Eliminar usuario
    public function Eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id_usuario = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Verificar credenciales para login. Retorna objeto usuario (sin password) o false
    public function VerificarLogin($email, $password) {
        try {
            $user = $this->ObtenerPorEmail($email);
            if ($user && isset($user->pass_hash) && password_verify($password, $user->pass_hash)) {
                // eliminar password antes de devolver
                unset($user->pass_hash);
                return $user;
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Actualiza la reputación promedio del usuario (valor directo 1..5)
    public function ActualizarReputacion($idUsuario, $puntuacion) {
        try {
            $stmt = $this->pdo->prepare("UPDATE usuario SET reputacion_promedio = ? WHERE id_usuario = ?");
            return $stmt->execute([$puntuacion, $idUsuario]);
        } catch (Exception $e) {
            throw $e;
        }
    }

}
