<main class="app-content">
  <div class="container py-4">
    <div class="card shadow-sm mx-auto" style="max-width: 760px;">
      <div class="card-body p-4">
        <h4 class="fw-bold text-primary mb-3">Detalle de entrega</h4>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operación realizada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between"><span><strong>Tipo</strong></span><span><?= htmlspecialchars($entrega['tipo']) ?></span></li>
          <?php if (($entrega['tipo'] ?? '') === 'PUNTO_SEGURO'): ?>
            <li class="list-group-item"><strong>Punto sugerido:</strong> <?= htmlspecialchars($entrega['punto_direccion'] ?? '-') ?></li>
            <li class="list-group-item"><strong>Fecha y hora:</strong> <?= htmlspecialchars($entrega['fecha_hora'] ?? '-') ?></li>
          <?php endif; ?>
          <li class="list-group-item"><strong>Notas:</strong> <?= nl2br(htmlspecialchars($entrega['notas'] ?? '')) ?></li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><strong>Estado</strong></span>
            <?php 
              $estado = strtoupper($entrega['estado'] ?? '');
              $cls = 'bg-secondary';
              if ($estado === 'PENDIENTE_CONFIRMACION') $cls = 'bg-warning text-dark';
              if ($estado === 'CONFIRMADA') $cls = 'bg-success';
              if ($estado === 'RECHAZADA') $cls = 'bg-danger';
            ?>
            <span class="badge <?= $cls ?>"><?= htmlspecialchars($estado) ?></span>
          </li>
        </ul>

        <?php if (!empty($esReceptor) && strtoupper($entrega['estado']) === 'PENDIENTE_CONFIRMACION'): ?>
          <div class="d-flex justify-content-end gap-2">
            <form method="POST" action="index.php?c=Entrega&a=Confirmar">
              <input type="hidden" name="id_entrega" value="<?= htmlspecialchars($entrega['id']) ?>">
              <button class="btn btn-success" type="submit">Confirmar</button>
            </form>
            <form method="POST" action="index.php?c=Entrega&a=Rechazar" onsubmit="return confirm('¿Rechazar propuesta de entrega?');">
              <input type="hidden" name="id_entrega" value="<?= htmlspecialchars($entrega['id']) ?>">
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
