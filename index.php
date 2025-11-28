<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar el autoloader de Composer para aprovechar PSR-4 y namespaces
require_once __DIR__ . '/vendor/autoload.php';

//var_dump($_GET['controlador']);  

/*
if(!isset($_GET['c'])){ //No existe
    echo "Inicio"; //Mensaje por defecto
}else{ //Existe
    echo $_GET['c'];   //Muestra el valor del parámetro c
}
*/


// Las clases `Modelos\` se cargan automáticamente vía Composer/PSR-4

if(!isset($_GET['c'])){ //No existe
    //isset — Determina si una variable está definida y no es NULL
    require_once "Controladores/Inicio.controlador.php"; //Cargar el controlador por defecto
    $controlador = new InicioControlador(); //Crear el objeto del controlador
    call_user_func(array($controlador, "Inicio")); //Llamar al método por defecto
    //call_user_func()  — Llama a una función de usuario dada por el nombre de la función

}else{ //Existe
    $controlador = $_GET['c'];   //Muestra el valor del parámetro c
    require_once "Controladores/$controlador.controlador.php"; //Cargar el controlador dinámicamente
    //Concatenar cadenas en PHP con el operador .
    $controlador = ucwords($controlador)."Controlador"; 
    //ucwords — Convierte a mayúsculas el primer carácter de cada palabra de una cadena
    $controlador = new $controlador(); //Crear el objeto del controlador
    $accion = isset($_GET['a']) ? $_GET['a'] : "Inicio"; 
    // Operador ternario: Si existe el parámetro a, lo asigna a $accion, si no, asigna 'Inicio'
    call_user_func(array($controlador, $accion)); //Llamar al método dinámicamente
}