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
            
            <!-- 👋 Bienvenida -->
            <div class="card shadow-sm mb-4">
              <div class="card-body p-4">
                <h4 class="fw-bold text-primary mb-2">Agregar nuevo libro</h4>
                <p class="text-muted mb-0">Aquí puedes añadir tus libros a la biblioteca personal.</p>
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

              <!-- 📚 Formulario para agregar libro -->
            <div class="card shadow-sm p-4 mb-4">
              <h5 class="fw-bold mb-4">📚 Información del libro</h5>
              
              <form method="POST" action="index.php?c=Libro&a=Guardar" enctype="multipart/form-data">
                <div class="row g-3">
                  
                  <!-- Título -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="titulo" placeholder="Ej: Harry Potter" required>
                  </div>

                  <!-- Autor -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Autor <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="autor" placeholder="Ej: J.K. Rowling" required>
                  </div>

                  <!-- Categoría -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_categoria" required>
                      <option value="">-- Selecciona una categoría --</option>
                      <option value="1">Novela</option>
                      <option value="2">Ciencia Ficción</option>
                      <option value="3">Educativo</option>
                      <option value="4">Infantil</option>
                    </select>
                  </div>

                  <!-- Condición -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold">Condición del libro <span class="text-danger">*</span></label>
                    <select class="form-select" name="condicion" required>
                      <option value="">-- Selecciona condición --</option>
                      <option value="NUEVO">Nuevo</option>
                      <option value="COMO NUEVO">Como nuevo</option>
                      <option value="USADO">Usado</option>
                      <option value="DETERIORADO">Deteriorado</option>
                    </select>
                  </div>

                  <!-- Descripción -->
                  <div class="col-12">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="4" placeholder="Cuéntanos sobre el libro..."></textarea>
                  </div>

                  <!-- Fotos del libro -->
                  <div class="col-12">
                    <label class="form-label fw-bold">Fotos del libro <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="fotos[]" multiple accept="image/*" required>
                    <small class="text-muted d-block mt-2">Puedes subir múltiples fotos (PNG, JPG, JPEG). La primera será la portada.</small>
                  </div>

                  <!-- Miniatura de fotos -->
                  <div class="col-12" id="fotosContainer" style="display: none;">
                    <label class="form-label fw-bold">Vista previa de fotos:</label>
                    <div class="d-flex flex-wrap gap-2" id="miniaturasContainer"></div>
                  </div>

                  <!-- Campo oculto para id_propietario (se rellena con PHP) -->
                  <input type="hidden" name="id_propietario" value="<?php echo $_SESSION['id_usuario'] ?? 1; ?>">

                  <!-- Botones de acción -->
                  <div class="col-12 text-end pt-3 border-top">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                      <i class="bi bi-arrow-counterclockwise me-2"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-success">
                      <i class="bi bi-check-circle me-2"></i> Publicar libro
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- 📘 Libros agregados recientemente -->
            <div class="card shadow-sm p-4">
              <h5 class="fw-bold mb-4">📘 Mis libros</h5>

              <?php if (empty($libros)): ?>
                <div class="alert alert-info">
                  <p>Aún no has agregado libros. ¡Crea uno arriba para empezar!</p>
                </div>
              <?php else: ?>
                <div class="row g-3">
                  <?php foreach ($libros as $libro): ?>
                    <!-- Tarjeta de libro dinámico -->
                    <div class="col-sm-6 col-lg-4">
                      <div class="card h-100 shadow-sm text-center">
                        <?php if (!empty($libro->imagen_url) && file_exists(__DIR__ . '/../../' . $libro->imagen_url)): ?>
                          <img src="<?php echo htmlspecialchars($libro->imagen_url); ?>" alt="Portada" class="card-img-top" style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                          <img src="Assets/images/placeholder.jpg" alt="Sin portada" class="card-img-top" style="height: 250px; object-fit: cover; background-color: #f0f0f0;">
                        <?php endif; ?>
                        <div class="card-body">
                          <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($libro->titulo); ?></h6>
                          <p class="text-muted small mb-2"><?php echo htmlspecialchars($libro->autor); ?></p>
                          
                            <!-- Badges de condición y estado -->
                            <?php 
                            $badgeCond = 'bg-success';
                            if ($libro->condicion === 'COMO NUEVO') {
                              $badgeCond = 'bg-success';
                            } elseif ($libro->condicion === 'USADO') {
                              $badgeCond = 'bg-warning text-dark';
                            } elseif ($libro->condicion === 'DETERIORADO') {
                              $badgeCond = 'bg-danger';
                            }
                            $estado = strtoupper(trim($libro->estado ?? 'PUBLICADO'));
                            $badgeEstado = 'bg-success';
                            if ($estado === 'OBSERVADO') {
                              $badgeEstado = 'bg-warning text-dark';
                            } elseif ($estado === 'ACORDADO') {
                              $badgeEstado = 'bg-info text-dark';
                            }
                            ?>
                            <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                            <span class="badge <?php echo $badgeCond; ?>"><?php echo htmlspecialchars($libro->condicion); ?></span>
                            <span class="badge <?php echo $badgeEstado; ?>"><?php echo htmlspecialchars($estado); ?></span>
                            </div>
                          <br>
                          <div class="d-flex gap-2 mt-3">
                            <a href="?c=Libro&a=Editar&id=<?php echo htmlspecialchars($libro->id_libro); ?>" class="btn btn-outline-primary btn-sm w-100">
                              <i class="bi bi-pencil me-2"></i> Editar
                            </a>
                            <a href="?c=Libro&a=Eliminar&id=<?php echo htmlspecialchars($libro->id_libro); ?>" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('¿Eliminar este libro? Esta acción no se puede deshacer.');">
                              <i class="bi bi-trash me-2"></i> Borrar
                            </a>
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

      <!-- Script para preview de fotos -->
      <script>
        document.querySelector('input[name="fotos[]"]').addEventListener('change', function(e) {
          const container = document.getElementById('fotosContainer');
          const miniaturasContainer = document.getElementById('miniaturasContainer');
          miniaturasContainer.innerHTML = '';
          
          Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
              const img = document.createElement('img');
              img.src = event.target.result;
              img.className = 'img-thumbnail';
              img.style.width = '100px';
              img.style.height = '100px';
              img.style.objectFit = 'cover';
              miniaturasContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
          });
          
          container.style.display = Array.from(e.target.files).length > 0 ? 'block' : 'none';
        });
      </script>
   </main>