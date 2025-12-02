<main class="app-content">
  <div class="container py-4">
    <div class="row g-4">
      
      <!-- 🧍 Panel lateral - Usuario -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center p-4">
            <div class="mb-3">
              <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Usuario" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <h5 class="fw-bold mb-1"><?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?></h5>
            <p class="text-muted small mb-3"><?php echo isset($_SESSION['fecha_registro']) ? 'Miembro desde: '.date('d/m/Y', strtotime($_SESSION['fecha_registro'])) : 'Miembro desde: -'; ?></p>
            
            <div class="d-grid gap-2">
              <a href="index.php?c=usuario&a=Editar" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil-square me-2"></i> Editar perfil
              </a>
              <a href="index.php?c=usuario&a=Logout" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- 🏠 Contenido principal -->
      <div class="col-md-9">
        
        <!-- 👋 Encabezado -->
        <div class="card shadow-sm mb-4">
          <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-2">📚 Biblioteca</h4>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'estado_no_disponible'): ?>
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                El libro seleccionado no está disponible para intercambio.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] === 'solicitud'): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                Solicitud de intercambio enviada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <?php 
            $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
            if (!empty($buscar)): 
            ?>
              <p class="text-muted mb-0">Resultados para: <strong>"<?= htmlspecialchars($buscar) ?>"</strong> <a href="?c=Libro&a=Biblioteca" class="ms-2">Ver todo</a></p>
            <?php else: ?>
              <p class="text-muted mb-0">Explora todos los libros disponibles e intercambia con otros usuarios.</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- 📚 Biblioteca -->
        <div class="card shadow-sm p-4">
          <?php 
            // Ocultar libros propios para evitar intercambiar con uno mismo
            $userId = $_SESSION['id_usuario'] ?? null;
            $bibliotecaFiltrada = [];
            if (!empty($biblioteca)) {
              foreach ($biblioteca as $item) {
                if ($userId && isset($item->id_propietario) && (int)$item->id_propietario === (int)$userId) {
                  continue; // skip propios
                }
                $bibliotecaFiltrada[] = $item;
              }
            }
          ?>
          <?php if (empty($bibliotecaFiltrada)): ?>
            <div class="alert alert-info">
              <?php if (!empty($buscar)): ?>
                No se encontraron resultados para "<strong><?= htmlspecialchars($buscar) ?></strong>". <a href="?c=Libro&a=Biblioteca">Ver toda la biblioteca</a>
              <?php else: ?>
                No hay libros disponibles de otros usuarios por ahora.
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="row g-3">
              <?php foreach ($bibliotecaFiltrada as $libro): ?>
                <div class="col-sm-6 col-lg-6">
                  <div class="card h-100 shadow-sm">
                    <div class="card-img-top-container" style="height: 250px; overflow: hidden; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                      <?php if (!empty($libro->imagen_url) && file_exists(__DIR__ . '/../../' . $libro->imagen_url)): ?>
                        <img src="<?= htmlspecialchars($libro->imagen_url) ?>" class="card-img-top" alt="Portada" style="width: 100%; height: 100%; object-fit: cover;">
                      <?php else: ?>
                        <img src="Assets/images/placeholder.jpg" class="card-img-top" alt="Sin portada" style="width: 100%; height: 100%; object-fit: cover;">
                      <?php endif; ?>
                    </div>

                    <div class="card-body d-flex flex-column">
                      <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($libro->titulo) ?></h6>
                      <p class="text-muted small mb-2"><?= htmlspecialchars($libro->autor) ?></p>
                      
                      <!-- Estado badge -->
                      <div class="mb-2">
                        <?php 
                        $estadoClass = 'bg-success';
                        if (isset($libro->estado) && $libro->estado === 'OBSERVADO') {
                            $estadoClass = 'bg-warning';
                        } elseif (isset($libro->estado) && $libro->estado === 'ACORDADO') {
                            $estadoClass = 'bg-info';
                        }
                        ?>
                        <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($libro->estado ?? 'PUBLICADO') ?></span>
                      </div>

                      <!-- Condición -->
                      <p class="card-text text-muted small mb-2">
                        <strong>Condición:</strong> <?= htmlspecialchars($libro->condicion) ?>
                      </p>

                      <!-- Espaciador para empujar botones al fondo -->
                      <div class="flex-grow-1"></div>

                      <!-- Botón de acción -->
                      <div class="d-grid mt-3">
                        <?php $estadoLibro = strtoupper(trim($libro->estado ?? 'PUBLICADO')); ?>
                        <?php if ($estadoLibro === 'PUBLICADO'): ?>
                          <a href="?c=Intercambio&a=Seleccionar&id=<?= htmlspecialchars($libro->id_libro) ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-arrow-left-right me-2"></i> Intercambiar
                          </a>
                        <?php else: ?>
                          <button class="btn btn-secondary btn-sm" type="button" disabled title="No disponible para intercambio">
                            <i class="bi bi-slash-circle me-2"></i> No disponible
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</main>

<style>
.card {
    border-radius: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
}

.card-img-top-container {
    position: relative;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.btn-group {
    gap: 0.5rem;
}

.btn-outline-primary:hover, .btn-outline-danger:hover {
    transform: scale(1.05);
}
</style>
