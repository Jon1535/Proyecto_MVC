<?php

session_start();

require_once "Modelos/Libro.php";

/**
 * ESQUEMA DE CÓDIGOS DE RESULTADO UNIFICADO
 * ==========================================
 * Todos los formularios (Crear, Editar, Eliminar) redirigen a 'Crear' con parámetros GET.
 * La vista Crear.php interpreta y muestra mensajes Bootstrap correspondientes.
 *
 * Parámetros GET soportados:
 * - success=1       → Operación exitosa (crear o editar libro)
 * - deleted=1       → Libro eliminado exitosamente
 * - error=1         → Recurso no encontrado / ID inválido / Acceso no autorizado a recurso
 * - error=2         → Error al guardar/actualizar en BD (excepción)
 * - error=3         → Sin permisos (no es propietario)
 * - error=4         → No se pudo eliminar el libro
 * - error=5         → Error general no categorizado
 *
 * Notas importantes:
 * - error=1 se usa SOLO para: ID inválido, recurso no existe, manipulación de datos (ej: id_libro modificado)
 * - Los campos obligatorios están protegidos por atributo HTML "required", así que no llegan al servidor vacíos
 * - Si alguien desactiva validación HTML, el servidor valida con empty() y redirige con error=1
 *
 * Flujo típico:
 * 1. Usuario rellena formulario en Crear.php o Editar.php
 * 2. Envía POST a Guardar() o GET a Eliminar()
 * 3. Controlador valida y realiza la acción
 * 4. Redirige a Crear con parámetro de resultado
 * 5. Crear.php muestra alerta Bootstrap correspondiente
 */

class LibroControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Libro;
    }
    
    public function Inicio() {
        require_once "Vistas/Encabezado.php"; // Incluir el encabezado
        require_once "Vistas/Libros/Index.php"; // Incluir la vista
        require_once "Vistas/Pie.php"; // Incluir el pie de página  
    }

    public function MisLibros() {
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }
        
        // Obtener la biblioteca completa (todos los libros de todos los usuarios)
        $biblioteca = $this->modelo->Listar();
        
        // Si viene parámetro de búsqueda, filtrar
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        if (!empty($buscar)) {
            $biblioteca = array_filter($biblioteca, function($libro) use ($buscar) {
                return stripos($libro->titulo, $buscar) !== false || stripos($libro->autor, $buscar) !== false;
            });
        }
        
        require_once "Vistas/Encabezado.php"; // Incluir el encabezado
        require_once "Vistas/Libros/MisLibros.php"; // Incluir la vista de "Biblioteca"
        require_once "Vistas/Pie.php"; // Incluir el pie de página  
    }
    
    public function FormCrear() {
        // Obtener libros del usuario autenticado si está logueado
        $libros = [];
        if (isset($_SESSION['id_usuario'])) {
            $libros = $this->modelo->ListarPorPropietario($_SESSION['id_usuario']);
        }
        
        require_once "Vistas/Encabezado.php"; // Incluir el encabezado
        require_once "Vistas/Libros/Crear.php"; // Incluir la vista de creación
        require_once "Vistas/Pie.php"; // Incluir el pie de página  
    }

    // Alias para compatibilidad con enlaces que usan ?a=Crear
    public function Crear() {
        $this->FormCrear();
    }

    public function Editar() {
        // Esperamos id por GET: ?c=Libro&a=Editar&id=123
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            // ID inválido → error=1 (recurso no encontrado o acceso inválido)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        // Debe estar autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }

        // Obtener el libro
        $libro = $this->modelo->Obtener($id);
        if (!$libro) {
            // Libro no encontrado → error=1 (recurso no encontrado)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        // Verificar que sea propietario
        if (intval($libro->id_propietario) !== intval($_SESSION['id_usuario'])) {
            // Sin permisos → error=3 (no es propietario)
            header('Location: index.php?c=Libro&a=Crear&error=3');
            exit;
        }

        // Renderizar formulario de edición
        require_once "Vistas/Encabezado.php";
        require_once "Vistas/Libros/Editar.php";
        require_once "Vistas/Pie.php";
    }
    
    public function Guardar() {

        // Asegurar petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=Libro&a=Crear');
            exit;
        }

        // Usuario autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }

        // Recoger campos del formulario con valores por defecto
        $id_libro = !empty($_POST['id_libro']) ? intval($_POST['id_libro']) : 0;
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $id_categoria = $_POST['id_categoria'] ?? '';
        $descripcion = trim($_POST['descripcion'] ?? '');
        $condicion = $_POST['condicion'] ?? '';
        $id_propietario = intval($_POST['id_propietario'] ?? 0);

        // Si se proporcionó id_propietario en el formulario, aseguramos que coincida con la sesión
        if ($id_propietario !== intval($_SESSION['id_usuario'])) {
            $id_propietario = intval($_SESSION['id_usuario']);
        }

        // Validación básica de campos obligatorios. Esto ya lo hace required
        if (empty($titulo) || empty($autor) || empty($id_categoria) || empty($condicion)) {
            // Campos vacíos (validación servidor como respaldo, aunque HTML requiere campos)
            // Redirige a Crear con error=1 (acceso/datos inválidos)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        // Si es edición, verificar que el usuario sea propietario
        if ($id_libro > 0) {
            $libroExistente = $this->modelo->Obtener($id_libro);
            if (!$libroExistente || intval($libroExistente->id_propietario) !== intval($_SESSION['id_usuario'])) {
                // Libro no existe o no es propietario → error=1 (acceso inválido)
                header('Location: index.php?c=Libro&a=Crear&error=1');
                exit;
            }
        }

        // Manejo de subida de fotos: guardar todas las subidas y tomar la primera como portada
        $savedPaths = [];
        $imagen_url = null;
        
        if (!empty($_FILES['fotos']) && !empty($_FILES['fotos']['tmp_name'][0])) {
            $uploadDir = __DIR__ . '/../Assets/images/uploads/';
            // Asegurar que la carpeta exista
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $countFiles = count($_FILES['fotos']['tmp_name']);
            // Configuración de validación de archivos
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];
            $maxBytes = 5 * 1024 * 1024; // 5 MB por archivo

            // Mantener lista de archivos movidos para limpieza en caso de error
            $movedFiles = [];

            for ($i = 0; $i < $countFiles; $i++) {
                if (empty($_FILES['fotos']['tmp_name'][$i])) continue;
                $tmpName = $_FILES['fotos']['tmp_name'][$i];
                if (!is_uploaded_file($tmpName)) continue;

                // Comprobar errores de subida PHP
                $uploadErr = $_FILES['fotos']['error'][$i] ?? UPLOAD_ERR_OK;
                if ($uploadErr !== UPLOAD_ERR_OK) {
                    // Saltar este archivo (no obligatorio detener todo el proceso)
                    continue;
                }

                // Validar tamaño
                $size = $_FILES['fotos']['size'][$i] ?? 0;
                if ($size > $maxBytes) {
                    // Limpiar archivos ya movidos
                    foreach ($movedFiles as $p) { if (file_exists($p)) @unlink($p); }
                    header('Location: index.php?c=Libro&a=Crear&error=6');
                    exit;
                }

                // Validar MIME real con finfo
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = $finfo ? finfo_file($finfo, $tmpName) : null;
                if ($finfo) finfo_close($finfo);
                if (!in_array($mime, $allowedMimes, true)) {
                    // Tipo no permitido -> limpiar y redirigir con error específico
                    foreach ($movedFiles as $p) { if (file_exists($p)) @unlink($p); }
                    header('Location: index.php?c=Libro&a=Crear&error=6');
                    exit;
                }

                // Comprobar extensión (por consistencia)
                $origName = basename($_FILES['fotos']['name'][$i]);
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExts, true)) {
                    foreach ($movedFiles as $p) { if (file_exists($p)) @unlink($p); }
                    header('Location: index.php?c=Libro&a=Crear&error=6');
                    exit;
                }

                // Generar nombre seguro
                try {
                    $safeName = time() . '_' . bin2hex(random_bytes(4)) . '_' . $i . '.' . $ext;
                } catch (Exception $ex) {
                    $safeName = time() . '_' . uniqid() . '_' . $i . '.' . $ext;
                }
                $destPath = $uploadDir . $safeName;

                if (move_uploaded_file($tmpName, $destPath)) {
                    $movedFiles[] = $destPath; // ruta absoluta para limpieza si hace falta
                    $rel = 'Assets/images/uploads/' . $safeName;
                    $savedPaths[] = $rel;
                    // La primera imagen será la portada
                    if ($imagen_url === null) {
                        $imagen_url = $rel;
                    }
                }
            }
        }

        // Al crear, las fotos son obligatorias
        if ($id_libro <= 0 && empty($savedPaths)) {
            // Sin fotos en creación → error=1 (validación fallida, tratado como acceso inválido)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        // Al editar, si no se suben fotos nuevas, mantener portada actual
        if ($id_libro > 0 && empty($imagen_url)) {
            $libroExistente = $this->modelo->Obtener($id_libro);
            if ($libroExistente && !empty($libroExistente->imagen_url)) {
                $imagen_url = $libroExistente->imagen_url;
            }
        }

        // Crear objeto Libro y setear propiedades
        $libro = new Libro();
        if ($id_libro > 0) {
            $libro->setId($id_libro);
        }
        $libro->setTitulo($titulo);
        $libro->setAutor($autor);
        $libro->setCategoria($id_categoria);
        $libro->setDescripcion($descripcion);
        $libro->setImagenUrl($imagen_url);
        $libro->setCondicion($condicion);
        $libro->setPropietario($id_propietario);
        $libro->setEstado('PUBLICADO');
        $libro->setFecha(date('Y-m-d H:i:s'));

        // Insertar o actualizar en la base de datos
        try {
            if ($id_libro > 0) {
                // Edición: actualizar registro existente
                $this->modelo->Actualizar($libro);
            } else {
                // Creación: insertar nuevo registro
                $this->modelo->Insertar($libro);
            }

            // Éxito → success=1 (según esquema unificado)
            // Redirige siempre a Crear para mostrar alerta de éxito
            header('Location: index.php?c=Libro&a=Crear&success=1');
            exit;
        } catch (Exception $e) {
            // Error al guardar → error=2 (según esquema unificado)
            // Redirige a Crear (no redirige a Editar por consistencia)
            header('Location: index.php?c=Libro&a=Crear&error=2');
            exit;
        }
    }

    public function Eliminar() {
        // Esperamos id por GET: ?c=Libro&a=Eliminar&id=123
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            // ID inválido → error=1 (recurso no encontrado)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        // Debe estar autenticado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?c=usuario&a=Login');
            exit;
        }

        // Verificar propiedad: sólo el propietario puede borrar
        $libro = $this->modelo->Obtener($id);
        if (!$libro) {
            // Libro no encontrado → error=1 (recurso no encontrado)
            header('Location: index.php?c=Libro&a=Crear&error=1');
            exit;
        }

        if (intval($libro->id_propietario) !== intval($_SESSION['id_usuario'])) {
            // No es propietario → error=3 (sin permisos)
            header('Location: index.php?c=Libro&a=Crear&error=3');
            exit;
        }

        // Intentar eliminar
        try {
            $ok = $this->modelo->Eliminar($id);
            if ($ok) {
                // Eliminación exitosa → deleted=1
                header('Location: index.php?c=Libro&a=Crear&deleted=1');
                exit;
            } else {
                // No se pudo eliminar → error=4
                header('Location: index.php?c=Libro&a=Crear&error=4');
                exit;
            }
        } catch (Exception $e) {
            // Error general → error=5
            header('Location: index.php?c=Libro&a=Crear&error=5');
            exit;
        }
    }
}