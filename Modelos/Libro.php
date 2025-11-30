<?php 
namespace Modelos;

use PDO;
use Exception;

class Libro {
    private $pdo; //Objeto de conexión a la base de datos

    private $id_libro; //Identificador del libro
    private $titulo; //Título del libro
    private $autor; //Autor del libro
    private $id_categoria; //Identificador de la categoría del libro
    private $descripcion; //Descripción del libro
    private $imagen_url; //URL de la imagen del libro
    private $id_propietario; //Identificador del propietario del libro
    private $estado; //Estado del libro (disponible, prestado, etc.)
    private $condicion; //Condición del libro (nuevo, usado, etc.)
    private $fecha_publicacion; //Fecha de publicación del libro

    public function __construct() {
        $this->pdo = Basededatos::Conectar(); //Inicializa la conexión a la base de datos   
    }

    public function getId() { 
        return $this->id_libro; 
    }

    public function setId($id) { 
        $this->id_libro = $id;
    }

    public function getTitulo() { 
        return $this->titulo; 
    }

    public function setTitulo($titulo) { 
        $this->titulo = $titulo; 
    }

    public function getAutor() {
        return $this->autor; 
    }

    public function setAutor($autor) { 
        $this->autor = $autor; 
    }

    public function getCategoria() { 
        return $this->id_categoria; 
    }

    public function setCategoria($id_categoria) { 
        $this->id_categoria = $id_categoria; 
    }
    public function getDescripcion() {
        return $this->descripcion; 
    }

    public function setDescripcion($descripcion) { 
        $this->descripcion = $descripcion; 
    }

    public function getImagenUrl() { 
        return $this->imagen_url; 
    }

    public function setImagenUrl($imagen_url) { 
        $this->imagen_url = $imagen_url; 
    }

    public function getPropietario() { 
        return $this->id_propietario; 
    }
    
    public function setPropietario($id_propietario) { 
        $this->id_propietario = $id_propietario; 
    }

    public function getEstado() { 
        return $this->estado; 
    }

    public function setEstado($estado) { 
        $this->estado = $estado; 
    }

    public function getCondicion() { 
        return $this->condicion; 
    }

    public function setCondicion($condicion) { 
        $this->condicion = $condicion; 
    }

    public function getFecha() {
        return $this->fecha_publicacion;
    }
    
    public function setFecha($fecha) {
        $this->fecha_publicacion = $fecha;
    }
 
    public function CantidadLibros() { //Función para obtener la cantidad total de libros en la base de datos
        try {
            $consulta = $this->pdo->prepare("SELECT 
            COUNT(*) AS cantidad FROM libro");
            $consulta->execute();
            return $resultado = $consulta->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar(){ //Función para listar todos los libros en la base de datos
        try {
            $consulta = $this->pdo->prepare("SELECT * FROM libro");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarPorPropietario($id_propietario) { //Función para listar libros de un propietario específico
        try {
            $consulta = $this->pdo->prepare("SELECT * FROM libro WHERE id_propietario = ? ORDER BY fecha_publicacion DESC");
            $consulta->execute(array($id_propietario));
            return $consulta->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Insertar(Libro $l) {  //Función para insertar un nuevo libro en la base de datos
        try {
            $consulta = "INSERT INTO libro (titulo, autor, id_categoria, descripcion, imagen_url, 
            id_propietario, estado, condicion, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        $this->pdo->prepare($consulta)->execute(array(
            $l->getTitulo(),
            $l->getAutor(),
            $l->getCategoria(),
            $l->getDescripcion(),
            $l->getImagenUrl(),
            $l->getPropietario(),
            $l->getEstado(),
            $l->getCondicion(),
            $l->getFecha()
        ));
        
        // Devolver id insertado para poder relacionar fotos u otras tablas
        $lastId = $this->pdo->lastInsertId();
        return $lastId;

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Actualizar(Libro $l) {  //Función para actualizar un libro existente
        try {
            $consulta = "UPDATE libro SET titulo = ?, autor = ?, id_categoria = ?, descripcion = ?, imagen_url = ?, 
            estado = ?, condicion = ? WHERE id_libro = ? AND id_propietario = ?"; 
            
            $this->pdo->prepare($consulta)->execute(array(
                $l->getTitulo(),
                $l->getAutor(),
                $l->getCategoria(),
                $l->getDescripcion(),
                $l->getImagenUrl(),
                $l->getEstado(),
                $l->getCondicion(),
                $l->getId(),
                $l->getPropietario()
            ));
            
            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id) { //Función para obtener un libro por su ID   
        try {
            $consulta = $this->pdo->prepare("SELECT * FROM libro WHERE id_libro = ? LIMIT 1");
            $consulta->execute(array($id));
            return $consulta->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id_libro) { //Función para eliminar un libro
        try {
            // Borrar la imagen/portada del libro si existe
            $libro = $this->Obtener($id_libro);
            if ($libro && $libro->imagen_url) {
                $fullPath = __DIR__ . '/../' . $libro->imagen_url;
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // Eliminar el libro de la base de datos
            $del = $this->pdo->prepare("DELETE FROM libro WHERE id_libro = ?");
            return $del->execute(array($id_libro));

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Actualizar únicamente el estado de un libro por su ID
    public function ActualizarEstado($id_libro, $estado) {
        try {
            $estado = strtoupper(trim($estado));
            $stmt = $this->pdo->prepare("UPDATE libro SET estado = ? WHERE id_libro = ?");
            return $stmt->execute([$estado, $id_libro]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}