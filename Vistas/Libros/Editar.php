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
            <p class="text-muted small mb-3"><?php echo isset($_SESSION['fecha_registro']) ? 'Miembro desde: '.date('Y', strtotime($_SESSION['fecha_registro'])) : 'Miembro desde: -'; ?></p>
            
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
        
        <!-- 👋 Bienvenida -->
        <div class="card shadow-sm mb-4">
          <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-2">Editar libro</h4>
            <p class="text-muted mb-0">Actualiza la información del libro.</p>
          </div>
        </div>

        <!-- Mensajes de resultado (éxito / error / borrado) -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
          <div class="alert alert-success">Operación realizada correctamente.</div>
        <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
          <div class="alert alert-success">Libro eliminado correctamente.</div>
          <?php elseif (isset($_GET['error'])): ?>
          <?php
            // Mapeo de códigos de error (esquema unificado)
            $err = intval($_GET['error']);
            if ($err === 1):
          ?>
            <div class="alert alert-warning">No se puede acceder a este contenido. Verifica que el enlace sea válido o intenta de nuevo.</div>
          <?php elseif ($err === 2): ?>
            <div class="alert alert-danger">Ocurrió un error al guardar el libro. Intenta nuevamente.</div>
          <?php elseif ($err === 3): ?>
            <div class="alert alert-danger">No tienes permiso para realizar esta acción.</div>
          <?php elseif ($err === 4): ?>
            <div class="alert alert-danger">No se pudo eliminar el libro. Intenta nuevamente.</div>
          <?php elseif ($err === 6): ?>
            <div class="alert alert-warning">Archivo no permitido o demasiado grande. Sube imágenes JPG/PNG/WEBP menores a 5 MB.</div>
          <?php else: ?>
            <div class="alert alert-danger">Ocurrió un error. Si el problema persiste, contacta al administrador.</div>
          <?php endif; ?>
        <?php endif; ?>

        <!-- 📚 Formulario para editar libro -->
        <div class="card shadow-sm p-4 mb-4">
          <h5 class="fw-bold mb-4">📚 Información del libro</h5>
          
          <form method="POST" action="index.php?c=Libro&a=Guardar" enctype="multipart/form-data">
            <div class="row g-3">
              
              <!-- ID del libro (oculto) -->
              <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($libro->id_libro); ?>">
              <input type="hidden" name="id_propietario" value="<?php echo htmlspecialchars($libro->id_propietario); ?>">

              <!-- Título -->
              <div class="col-md-6">
                <label class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="titulo" placeholder="Ej: Harry Potter" value="<?php echo htmlspecialchars($libro->titulo); ?>" required>
              </div>

              <!-- Autor -->
              <div class="col-md-6">
                <label class="form-label fw-bold">Autor <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="autor" placeholder="Ej: J.K. Rowling" value="<?php echo htmlspecialchars($libro->autor); ?>" required>
              </div>

              <!-- Categoría -->
              <div class="col-md-6">
                <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                <select class="form-select" name="id_categoria" required>
                  <option value="">-- Selecciona una categoría --</option>
                  <option value="1" <?php echo ($libro->id_categoria == 1) ? 'selected' : ''; ?>>Novela</option>
                  <option value="2" <?php echo ($libro->id_categoria == 2) ? 'selected' : ''; ?>>Ciencia Ficción</option>
                  <option value="3" <?php echo ($libro->id_categoria == 3) ? 'selected' : ''; ?>>Educativo</option>
                  <option value="4" <?php echo ($libro->id_categoria == 4) ? 'selected' : ''; ?>>Infantil</option>
                </select>
              </div>

              <!-- Condición -->
              <div class="col-md-6">
                <label class="form-label fw-bold">Condición del libro <span class="text-danger">*</span></label>
                <select class="form-select" name="condicion" required>
                  <option value="">-- Selecciona condición --</option>
                  <option value="NUEVO" <?php echo ($libro->condicion === 'NUEVO') ? 'selected' : ''; ?>>Nuevo</option>
                  <option value="COMO NUEVO" <?php echo ($libro->condicion === 'COMO NUEVO') ? 'selected' : ''; ?>>Como nuevo</option>
                  <option value="USADO" <?php echo ($libro->condicion === 'USADO') ? 'selected' : ''; ?>>Usado</option>
                  <option value="DETERIORADO" <?php echo ($libro->condicion === 'DETERIORADO') ? 'selected' : ''; ?>>Deteriorado</option>
                </select>
              </div>

              <!-- Descripción -->
              <div class="col-12">
                <label class="form-label fw-bold">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="4" placeholder="Cuéntanos sobre el libro..."><?php echo htmlspecialchars($libro->descripcion); ?></textarea>
              </div>

              <!-- Portada actual -->
              <?php if (!empty($libro->imagen_url) && file_exists(__DIR__ . '/../../' . $libro->imagen_url)): ?>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Portada actual</label>
                  <div style="border: 1px solid #ddd; border-radius: 4px; padding: 5px; max-width: 150px;">
                    <img src="<?php echo htmlspecialchars($libro->imagen_url); ?>" alt="Portada actual" style="width: 100%; height: auto; border-radius: 3px;">
                  </div>
                </div>
              <?php endif; ?>

              <!-- Fotos del libro -->
              <div class="col-12">
                <label class="form-label fw-bold">Nuevas fotos del libro (opcional)</label>
                <input type="file" class="form-control" name="fotos[]" multiple accept="image/*">
                <small class="text-muted d-block mt-2">Puedes subir múltiples fotos (PNG, JPG, JPEG). La primera será la portada.</small>
              </div>

              <!-- Miniatura de fotos -->
              <div class="col-12" id="fotosContainer" style="display: none;">
                <label class="form-label fw-bold">Vista previa de fotos:</label>
                <div class="d-flex flex-wrap gap-2" id="miniaturasContainer"></div>
              </div>

              <!-- Botones de acción -->
              <div class="col-12 text-end pt-3 border-top">
                <a href="index.php?c=Libro&a=Crear" class="btn btn-outline-secondary me-2">
                  <i class="bi bi-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                  <i class="bi bi-check-circle me-2"></i> Guardar cambios
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Script para preview de fotos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const inputFotos = document.querySelector('input[name="fotos[]"]');
  const fotosContainer = document.getElementById('fotosContainer');
  const miniaturasContainer = document.getElementById('miniaturasContainer');

  if (inputFotos) {
    inputFotos.addEventListener('change', function(e) {
      miniaturasContainer.innerHTML = '';
      const archivos = e.target.files;

      if (archivos.length > 0) {
        fotosContainer.style.display = 'block';
        Array.from(archivos).forEach((archivo, index) => {
          const reader = new FileReader();
          reader.onload = function(event) {
            const div = document.createElement('div');
            div.style.position = 'relative';
            div.style.width = '100px';
            div.style.height = '150px';
            div.style.border = '1px solid #ddd';
            div.style.borderRadius = '4px';
            div.style.overflow = 'hidden';

            const img = document.createElement('img');
            img.src = event.target.result;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';

            div.appendChild(img);
            miniaturasContainer.appendChild(div);
          };
          reader.readAsDataURL(archivo);
        });
      } else {
        fotosContainer.style.display = 'none';
      }
    });
  }
});
</script>
