<?php
namespace Modelos;

use PDO;
use Exception;

class Basededatos {
    const servidor = "localhost";
    const usuariobd = "root";
    const contra ="";
    const nombredb = "bd_intercambio_libros";

    public static function Conectar(){
        try {
            $conexion =new PDO("mysql:host=".self::servidor.";dbname=".self::nombredb.";charset=utf8",
            self::usuariobd,self::contra);
            //Crear una nueva conexión PDO

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Establecer el modo de error de PDO a excepción
            return $conexion; //Devolver la conexión establecida

        }catch (PDOException $e){
            return "fallo ". $e->getMessage();   
            //Capturar cualquier excepción PDO y devolver el mensaje de error
        }   
    }
}