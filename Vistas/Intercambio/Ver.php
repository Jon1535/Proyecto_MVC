<main class="app-content">
  <div class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 760px;">
      <div class="card-body p-4">
        <h4 class="fw-bold text-primary mb-3">Detalle del intercambio</h4>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operación realizada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between"><span><strong>Tipo</strong></span><span><?= htmlspecialchars($row['tipo'] ?? 'PUNTO_SEGURO') ?></span></li>
          <li class="list-group-item"><strong>Punto sugerido:</strong> <?= htmlspecialchars($row['punto_direccion'] ?? '-') ?></li>
          <li class="list-group-item"><strong>Fecha y hora:</strong> <?= htmlspecialchars($row['fecha_entrega'] ? date('d/m/Y H:i', strtotime($row['fecha_entrega'])) : '-') ?></li>
          <li class="list-group-item"><strong>Notas:</strong> <?= nl2br(htmlspecialchars($row['notas'] ?? '')) ?></li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><strong>Confirmaciones</strong></span>
            <span>
              A: <span class="badge <?= ((int)($row['confirmacion_a'] ?? 0) === 1) ? 'bg-success' : 'bg-secondary' ?>"><?= ((int)($row['confirmacion_a'] ?? 0) === 1) ? 'OK' : 'Pendiente' ?></span>
              B: <span class="badge <?= ((int)($row['confirmacion_b'] ?? 0) === 1) ? 'bg-success' : 'bg-secondary' ?>"><?= ((int)($row['confirmacion_b'] ?? 0) === 1) ? 'OK' : 'Pendiente' ?></span>
            </span>
          </li>
        </ul>

        <?php if ((int)$_SESSION['id_usuario'] === (int)$row['id_usuario_a'] || (int)$_SESSION['id_usuario'] === (int)$row['id_usuario_b']): ?>
          <div class="d-flex justify-content-end gap-2">
            <form method="POST" action="index.php?c=Intercambio&a=ConfirmarEntrega">
              <input type="hidden" name="intercambio_id" value="<?= htmlspecialchars($row['id_intercambio']) ?>">
              <button class="btn btn-success" type="submit">Confirmar</button>
            </form>
            <form method="POST" action="index.php?c=Intercambio&a=RechazarEntrega" onsubmit="return confirm('¿Rechazar programación del intercambio?');">
              <input type="hidden" name="intercambio_id" value="<?= htmlspecialchars($row['id_intercambio']) ?>">
              <button class="btn btn-outline-danger" type="submit">Rechazar</button>
            </form>
          </div>
        <?php else: ?>
          <div class="text-end">
            <a href="index.php?c=Libro&a=Biblioteca" class="btn btn-outline-secondary">Volver</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
