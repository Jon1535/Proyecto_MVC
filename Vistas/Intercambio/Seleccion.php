<main class="app-content">
  <div class="container py-4">
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        Solicitud de intercambio enviada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h3 class="fw-bold text-primary mb-0">Selecciona los libros para el intercambio</h3>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <!-- Libro de tu colección -->
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body p-4">
            <h5 class="text-success fw-bold mb-3">Libro de tu colección</h5>
            <div class="p-4 bg-light border rounded d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
              <div id="miLibroPreview" class="text-center">
                <img id="miLibroImagen" src="Assets/images/placeholder.jpg" alt="Mi libro" class="rounded shadow-sm" style="width: 220px; height: 300px; object-fit: cover; display: none;">
                <div id="miLibroPlaceholder" class="text-muted" style="display: block;">
                  <i class="bi bi-image fs-1"></i>
                  <p class="mt-2">Selecciona un libro de tu biblioteca</p>
                </div>
                <h5 id="miLibroTitulo" class="mt-3 fw-bold" style="display: none;"></h5>
              </div>
              <div class="mt-4 w-100">
                <label class="form-label">Elige un libro</label>
                <select id="miLibroSelect" class="form-select">
                  <option value="">-- Selecciona --</option>
                  <?php foreach ($misLibros as $l): ?>
                    <option value="<?= htmlspecialchars($l->id_libro) ?>" data-img="<?= htmlspecialchars($l->imagen_url ?? '') ?>" data-titulo="<?= htmlspecialchars($l->titulo) ?>">
                      <?= htmlspecialchars($l->titulo) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Libro solicitado -->
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body p-4">
            <h5 class="text-success fw-bold mb-3">Libro solicitado</h5>
            <div class="p-4 bg-light border rounded d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
              <div class="text-center">
                <?php 
                  $root = dirname(__DIR__, 2); // proyecto
                  $imgPath = !empty($libroSolicitado->imagen_url) ? $root . '/' . ltrim($libroSolicitado->imagen_url, '/'): '';
                  $img = ($imgPath && file_exists($imgPath)) ? $libroSolicitado->imagen_url : 'Assets/images/placeholder.jpg';
                ?>
                <img src="<?= htmlspecialchars($img) ?>" alt="Libro solicitado" class="rounded shadow-sm" style="width: 220px; height: 300px; object-fit: cover;">
                <h5 class="mt-3 fw-bold"><?= htmlspecialchars($libroSolicitado->titulo) ?></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="card shadow-sm mt-4">
      <div class="card-body p-4 d-flex justify-content-between">
        <a href="index.php?c=Libro&a=MisLibros" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a>
        <form method="POST" action="index.php?c=Intercambio&a=Confirmar" class="d-flex gap-2">
          <input type="hidden" name="id_libro_mio" id="id_libro_mio" value="">
          <input type="hidden" name="id_libro_solicitado" value="<?= htmlspecialchars($libroSolicitado->id_libro) ?>">
          <button type="submit" class="btn btn-primary" disabled id="btnContinuar"><i class="bi bi-arrow-right me-2"></i>Continuar</button>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
  const select = document.getElementById('miLibroSelect');
  const imgEl = document.getElementById('miLibroImagen');
  const titleEl = document.getElementById('miLibroTitulo');
  const placeholder = document.getElementById('miLibroPlaceholder');
  const idHidden = document.getElementById('id_libro_mio');
  const btnContinuar = document.getElementById('btnContinuar');

  select.addEventListener('change', function(){
    const id = this.value;
    const option = this.selectedOptions[0];
    const src = option ? option.getAttribute('data-img') : '';
    const titulo = option ? option.getAttribute('data-titulo') : '';

    if(id){
      idHidden.value = id;
      btnContinuar.disabled = false;
      placeholder.style.display = 'none';
      imgEl.style.display = 'block';
      imgEl.src = src && src.length > 0 ? src : 'Assets/images/placeholder.jpg';
      titleEl.style.display = 'block';
      titleEl.textContent = titulo || 'Mi libro';
    } else {
      idHidden.value = '';
      btnContinuar.disabled = true;
      placeholder.style.display = 'block';
      imgEl.style.display = 'none';
      titleEl.style.display = 'none';
      titleEl.textContent = '';
    }
  });
</script>
