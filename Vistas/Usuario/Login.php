<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="Assets/css/main.css">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Login - ProyectoMVC</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>ProyectoMVC</h1>
      </div>
      <div class="login-box">
        <?php if (isset($_GET['registered'])): ?>
          <div class="alert alert-success">Registro exitoso. Ya puedes iniciar sesión.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">Credenciales inválidas, inténtalo de nuevo.</div>
        <?php endif; ?>
        <form class="login-form" method="POST" action="index.php?c=usuario&a=Entrar">
          <h3 class="login-head"><i class="bi bi-person me-2"></i>INICIAR SESIÓN</h3>
          <div class="mb-3">
            <label class="form-label">EMAIL</label>
            <input class="form-control" type="email" name="email" placeholder="Email" autofocus required>
          </div>
          <div class="mb-3">
            <label class="form-label">CONTRASEÑA</label>
            <input class="form-control" type="password" name="password" placeholder="Password" required>
          </div>
          <div class="mb-3">
            <div class="utility d-flex justify-content-between align-items-center">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="remember"> <span class="label-text">Mantener sesión</span>
                </label>
              </div>
              <p class="semibold-text mb-0"><a href="#" data-toggle="flip">¿Olvidaste la contraseña?</a></p>
            </div>
          </div>
          <div class="mb-3 btn-container d-grid">
            <button class="btn btn-primary btn-block"><i class="bi bi-box-arrow-in-right me-2 fs-5"></i>INICIAR SESIÓN</button>
          </div>
          <p class="text-center mt-3">¿No tienes cuenta? <a href="index.php?c=usuario&a=Registro">Regístrate</a></p>
        </form>
        <form class="forget-form" action="#">
          <h3 class="login-head"><i class="bi bi-person-lock me-2"></i>Recuperar contraseña</h3>
          <div class="mb-3">
            <label class="form-label">EMAIL</label>
            <input class="form-control" type="email" placeholder="Email">
          </div>
          <div class="mb-3 btn-container d-grid">
            <button class="btn btn-primary btn-block"><i class="bi bi-unlock me-2 fs-5"></i>REINICIAR</button>
          </div>
          <div class="mb-3 mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="bi bi-chevron-left me-1"></i> Volver al login</a></p>
          </div>
        </form>
      </div>
    </section>
