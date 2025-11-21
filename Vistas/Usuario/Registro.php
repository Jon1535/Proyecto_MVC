<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="Assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Registro - ProyectoMVC</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo"><h1>ProyectoMVC</h1></div>
      <div class="login-box">
        <div class="card" style="max-width: 550px; margin: 0 auto; min-height: auto; overflow: visible; width: 100%;">
          <div class="card-body" style="padding: 2.5rem;">
            <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger">
                <?php
                  $e = (int)$_GET['error'];
                  if ($e === 1) echo 'Completa todos los campos requeridos.';
                  elseif ($e === 2) echo 'Las contraseñas no coinciden.';
                  elseif ($e === 3) echo 'Ya existe un usuario con ese correo.';
                  else echo 'Error al registrar. Intenta nuevamente.';
                ?>
              </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="index.php?c=usuario&a=Registrar">
              <h3 class="login-head text-center mb-4"><i class="bi bi-person-plus me-2"></i>REGISTRARSE</h3>
              <div class="mb-3">
                <label class="form-label">NOMBRE</label>
                <input class="form-control" type="text" name="nombre" placeholder="Tu nombre" required>
              </div>
              <div class="mb-3">
                <label class="form-label">EMAIL</label>
                <input class="form-control" type="email" name="email" placeholder="correo@ejemplo.com" required>
              </div>
              <div class="mb-3">
                <label class="form-label">CONTRASEÑA</label>
                <input class="form-control" type="password" name="password" placeholder="Password" required>
              </div>
              <div class="mb-3">
                <label class="form-label">CONFIRMAR CONTRASEÑA</label>
                <input class="form-control" type="password" name="password2" placeholder="Repite la contraseña" required>
              </div>
              <div class="mb-3 btn-container d-grid">
                <button class="btn btn-primary btn-block"><i class="bi bi-person-check me-2"></i>CREAR CUENTA</button>
              </div>
              <p class="text-center mt-3">¿Ya tienes cuenta? <a href="index.php?c=usuario&a=Login">Inicia sesión</a></p>
            </form>
          </div>
        </div>
        </div>
      </div>
    </section>