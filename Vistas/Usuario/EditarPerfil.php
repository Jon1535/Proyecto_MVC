<main class="app-content">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-3"><i class="bi bi-person-gear me-2"></i>Editar Perfil</h4>
            <p class="text-muted">Actualiza tu información. La contraseña es opcional: sólo rellénala si quieres cambiarla.</p>

            <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger">
                <?php $e=(int)$_GET['error'];
                  if($e===1) echo 'Acceso inválido.';
                  elseif($e===2) echo 'Completa nombre y email.';
                  elseif($e===3) echo 'Las contraseñas no coinciden.';
                  elseif($e===4) echo 'El email ya está registrado.';
                  elseif($e===5) echo 'No se pudo guardar los cambios.';
                  else echo 'Error desconocido.'; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="index.php?c=usuario&a=ActualizarPerfil" novalidate class="needs-validation">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario->nombre); ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario->email); ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Nueva contraseña</label>
                  <input type="password" class="form-control" name="password" placeholder="Opcional">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Confirmar contraseña</label>
                  <input type="password" class="form-control" name="password2" placeholder="Repite si cambias">
                </div>
                <div class="col-12 text-end pt-3 border-top">
                  <a href="index.php?c=usuario&a=Perfil" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-left me-2"></i>Cancelar</a>
                  <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar cambios</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>