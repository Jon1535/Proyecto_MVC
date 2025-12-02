<main class="app-content">
  <div class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 780px;">
      <div class="card-body p-4">
        <h4 class="fw-bold text-primary mb-3">Programar intercambio</h4>

        <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Completa dirección y fecha/hora.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php?c=Intercambio&a=GuardarProgramacion">
          <input type="hidden" name="intercambio_id" value="<?= htmlspecialchars($row['id_intercambio']) ?>">
          <input type="hidden" name="tipo" value="PUNTO_SEGURO">
          <div class="border rounded p-3 mb-3">
            <h6 class="text-success">Sugerir punto de encuentro seguro</h6>
            <div class="mb-2">
              <label class="form-label">Dirección sugerida:</label>
              <input type="text" class="form-control" name="punto_direccion" value="<?= htmlspecialchars($row['punto_direccion'] ?? '') ?>" placeholder="Ejemplo: Biblioteca central, cafetería...">
            </div>
            <div class="mb-2">
              <label class="form-label">Fecha y hora:</label>
              <input type="datetime-local" class="form-control" name="fecha_hora" value="<?= isset($row['fecha_entrega']) ? date('Y-m-d\TH:i', strtotime($row['fecha_entrega'])) : '' ?>">
            </div>
          </div>

        <div class="mb-3">
          <label class="form-label">Notas (opcional):</label>
          <textarea class="form-control" name="notas" rows="2" placeholder="Información adicional para coordinar..."><?= htmlspecialchars($row['notas'] ?? '') ?></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="index.php?c=Libro&a=Biblioteca" class="btn btn-outline-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">Guardar programación</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</main>
