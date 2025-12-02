<main class="app-content">
  <div class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 640px;">
      <div class="card-body p-4">
        <h4 class="fw-bold text-primary mb-3">Dejar una valoración</h4>

        <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Por favor elige una puntuación válida (1 a 5) y verifica los datos.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php?c=Valoracion&a=Guardar">
          <input type="hidden" name="intercambio_id" value="<?= htmlspecialchars($intercambioId) ?>">
          <input type="hidden" name="calificado_id" value="<?= htmlspecialchars($calificadoId) ?>">

          <div class="mb-3">
            <label class="form-label">Puntuación</label>
            <select name="puntuacion" class="form-select" required>
              <option value="">Selecciona…</option>
              <option value="5">5 - Excelente</option>
              <option value="4">4 - Muy bueno</option>
              <option value="3">3 - Bueno</option>
              <option value="2">2 - Regular</option>
              <option value="1">1 - Malo</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Comentario (opcional)</label>
            <textarea name="comentario" class="form-control" rows="3" placeholder="Comparte brevemente tu experiencia"></textarea>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="index.php?c=Libro&a=Biblioteca">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar valoración</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
