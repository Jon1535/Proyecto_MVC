<?php

session_start();


class BusquedaControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new \Modelos\Libro;
    }

    /**
     * Devuelve sugerencias de títulos en formato JSON para autocomplete
     * GET: ?c=Busqueda&a=Sugerencias&q=termino
     */
    public function Sugerencias() {
        header('Content-Type: application/json');

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }

        try {
            $libros = $this->modelo->Listar();
            $resultados = [];
            
            foreach ($libros as $libro) {
                // Buscar coincidencias en título o autor (case-insensitive)
                if (stripos($libro->titulo, $q) !== false || stripos($libro->autor, $q) !== false) {
                    $resultados[] = [
                        'id' => $libro->id_libro,
                        'titulo' => $libro->titulo,
                        'autor' => $libro->autor
                    ];
                }
                // Limitar a 10 resultados
                if (count($resultados) >= 10) {
                    break;
                }
            }

            echo json_encode($resultados);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Redirige a la biblioteca con filtro de búsqueda
     * GET: ?c=Busqueda&a=Buscar&q=termino
     */
    public function Buscar() {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($q)) {
            header('Location: index.php?c=Libro&a=Biblioteca');
            exit;
        }

        // Redirigir a biblioteca con parámetro de búsqueda
        header('Location: index.php?c=Libro&a=Biblioteca&buscar=' . urlencode($q));
        exit;
    }
}
?>
