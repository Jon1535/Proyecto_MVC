<?php
require_once "Modelos/Libro.php";


class InicioControlador {
    private $modelo;

    public function __CONSTRUCT()  {
        $this->modelo = new Libro(); //Instancia del modelo Libro
    }    
    public function Inicio() {

        //$bd = Basededatos::Conectar();   
        // Lógica para la acción de inicio
        require_once "Vistas/Encabezado.php"; // Incluir el encabezado
        require_once "Vistas/Inicio/Principal.php"; // Incluir la vista
        require_once "Vistas/Pie.php"; // Incluir el pie de página  
    }

}