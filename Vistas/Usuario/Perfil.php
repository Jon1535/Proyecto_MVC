<main class="app-content">
  <div class="container py-4">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center p-4">
            <div class="mb-3">
              <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
            </div>
            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($usuario->nombre); ?></h5>
            <p class="text-muted small mb-2"><?php echo htmlspecialchars($usuario->email); ?></p>
            <p class="text-muted small mb-3">Miembro desde: <?php echo $usuario->fecha_registro ? date('d/m/Y', strtotime($usuario->fecha_registro)) : '-'; ?></p>
            <div class="d-grid gap-2">
              <a href="index.php?c=usuario&a=Editar" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square me-2"></i>Editar perfil</a>
              <a href="index.php?c=usuario&a=Logout" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card shadow-sm mb-4">
          <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-2">Perfil de Usuario</h4>
            <p class="text-muted mb-0">Esta es tu página principal. Desde aquí puedes revisar tus datos y acceder a tus libros.</p>
          </div>
        </div>
        <?php if (isset($_GET['success']) && $_GET['success']==1): ?>
          <div class="alert alert-success">Perfil actualizado correctamente.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">
            <?php $e=(int)$_GET['error'];
              if($e===1) echo 'Acceso inválido.';
              elseif($e===2) echo 'Campos obligatorios faltantes.';
              elseif($e===3) echo 'Las contraseñas no coinciden.';
              elseif($e===4) echo 'El email ya está en uso.';
              elseif($e===5) echo 'No se pudo actualizar el perfil.';
              else echo 'Error desconocido.'; ?>
          </div>
        <?php endif; ?>
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Resumen</h5>
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Nombre</span><span class="fw-semibold"><?php echo htmlspecialchars($usuario->nombre); ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Email</span><span class="fw-semibold"><?php echo htmlspecialchars($usuario->email); ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Fecha registro</span><span class="fw-semibold"><?php echo $usuario->fecha_registro ? date('d/m/Y H:i', strtotime($usuario->fecha_registro)) : '-'; ?></span>
              </li>
            </ul>
            <div class="text-end mt-4">
              <a href="index.php?c=usuario&a=Editar" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Editar Perfil</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>