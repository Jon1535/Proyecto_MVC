<main class="app-content">
  <div class="container py-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4 text-center">
        <h3 class="fw-bold text-primary mb-0">¿Aceptar intercambio?</h3>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <!-- Tu libro (solicitado) -->
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body p-4 text-center">
            <h5 class="text-success fw-bold mb-3">Libro de tu colección</h5>
            <?php 
              $root = dirname(__DIR__, 2);
              $pathA = !empty($libroSolicitado->imagen_url) ? $root . '/' . ltrim($libroSolicitado->imagen_url, '/') : '';
              $imgA = ($pathA && file_exists($pathA)) ? $libroSolicitado->imagen_url : 'Assets/images/placeholder.jpg';
            ?>
            <img src="<?= htmlspecialchars($imgA) ?>" alt="Tu libro" class="rounded shadow-sm mb-3" style="width: 220px; height: 300px; object-fit: cover;">
            <h5 class="fw-bold mb-0"><?= htmlspecialchars($libroSolicitado->titulo ?? 'Tu libro') ?></h5>
          </div>
        </div>
      </div>

      <!-- Libro ofrecido por el otro usuario -->
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body p-4 text-center">
            <h5 class="text-success fw-bold mb-3">Libro ofrecido</h5>
            <?php 
              $pathB = !empty($libroOfrecido->imagen_url) ? $root . '/' . ltrim($libroOfrecido->imagen_url, '/') : '';
              $imgB = ($pathB && file_exists($pathB)) ? $libroOfrecido->imagen_url : 'Assets/images/placeholder.jpg';
            ?>
            <img src="<?= htmlspecialchars($imgB) ?>" alt="Libro ofrecido" class="rounded shadow-sm mb-3" style="width: 220px; height: 300px; object-fit: cover;">
            <h5 class="fw-bold mb-0"><?= htmlspecialchars($libroOfrecido->titulo ?? 'Libro ofrecido') ?></h5>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mt-4">
      <form method="POST" action="index.php?c=Intercambio&a=Aceptar">
        <input type="hidden" name="id_notif" value="<?= htmlspecialchars($notif['id']) ?>">
        <button class="btn btn-success btn-lg"><i class="bi bi-check2-circle me-2"></i>Aceptar</button>
      </form>
      <form method="POST" action="index.php?c=Intercambio&a=Rechazar">
        <input type="hidden" name="id_notif" value="<?= htmlspecialchars($notif['id']) ?>">
        <button class="btn btn-outline-danger btn-lg"><i class="bi bi-x-circle me-2"></i>Rechazar</button>
      </form>
    </div>
  </div>
</main>
