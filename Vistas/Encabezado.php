<?php
// Asegurarnos de que la sesión está iniciada para poder leer datos de usuario
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Detectar controlador/acción actuales para marcar menú activo
$currentController = isset($_GET['c']) ? strtolower($_GET['c']) : 'inicio';
$currentAction = isset($_GET['a']) ? strtolower($_GET['a']) : 'principal';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 5, SASS and PUG.js. It's fully customizable and modular.">
  <!-- Twitter meta -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:site" content="@pratikborsadiya">
  <meta property="twitter:creator" content="@pratikborsadiya">
  <!-- Open Graph Meta -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Vali Admin">
  <meta property="og:title" content="Vali - Free Bootstrap 5 admin theme">
  <meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
  <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">
  <meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 5, SASS and PUG.js. It's fully customizable and modular.">
  <title>User Profile - Vali Admin</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS -->
  <link rel="stylesheet" type="text/css" href="Assets/css/main.css">
  <!-- Font-icon css -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="app sidebar-mini">
  <!-- Navbar-->
  <header class="app-header"><a class="app-header__logo" href="index.php"><img src="Assets/images/Logo.png" alt="Logo" width="50" height="50"></a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
      <li class="app-search" style="position: relative;">
        <input class="app-search__input" id="searchInput" type="search" placeholder="Buscador" autocomplete="off">
        <button class="app-search__button" id="searchBtn"><i class="bi bi-search"></i></button>
        <ul class="dropdown-menu" id="searchSuggestions" style="position: absolute; top: 100%; left: 0; width: 100%; display: none; max-height: 300px; overflow-y: auto; z-index: 1000;">
        </ul>
      </li>
      <!--Notification Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-bs-toggle="dropdown" aria-label="Show notifications"><i class="bi bi-bell fs-5"></i></a>
        <ul class="app-notification dropdown-menu dropdown-menu-right">
          <li class="app-notification__title">You have 4 new notifications.</li>
          <div class="app-notification__content">
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-envelope fs-4 text-primary"></i></span>
                <div>
                  <p class="app-notification__message">Lisa sent you a mail</p>
                  <p class="app-notification__meta">2 min ago</p>
                </div></a></li>
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-exclamation-triangle fs-4 text-warning"></i></span>
                <div>
                  <p class="app-notification__message">Mail server not working</p>
                  <p class="app-notification__meta">5 min ago</p>
                </div></a></li>
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-cash fs-4 text-success"></i></span>
                <div>
                  <p class="app-notification__message">Transaction complete</p>
                  <p class="app-notification__meta">2 days ago</p>
                </div></a></li>
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-envelope fs-4 text-primary"></i></span>
                <div>
                  <p class="app-notification__message">Lisa sent you a mail</p>
                  <p class="app-notification__meta">2 min ago</p>
                </div></a></li>
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-exclamation-triangle fs-4 text-warning"></i></span>
                <div>
                  <p class="app-notification__message">Mail server not working</p>
                  <p class="app-notification__meta">5 min ago</p>
                </div></a></li>
            <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><i class="bi bi-cash fs-4 text-success"></i></span>
                <div>
                  <p class="app-notification__message">Transaction complete</p>
                  <p class="app-notification__meta">2 days ago</p>
                </div></a></li>
          </div>
          <li class="app-notification__footer"><a href="#">See all notifications.</a></li>
        </ul>
      </li>
      <!-- User Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu"><i class="bi bi-person fs-4"></i></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="page-user.html"><i class="bi bi-gear me-2 fs-5"></i> Settings</a></li>
          <li><a class="dropdown-item" href="page-user.html"><i class="bi bi-person me-2 fs-5"></i> Profile</a></li>
         <li><a class="dropdown-item" href="index.php?c=usuario&a=Logout"><i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout</a></li>
        </ul>
      </li>
    </ul>
  </header>
  <!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://randomuser.me/api/portraits/men/1.jpg" alt="User Image">
      <div>
          <p class="app-sidebar__user-name"><?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?></p>
          <p class="app-sidebar__user-designation"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'No autenticado'; ?></p>
      </div>
    </div>
    <ul class="app-menu">
  <li><a class="app-menu__item <?php echo ($currentController === 'inicio') ? 'active' : ''; ?>" href="index.php"><i class="app-menu__icon bi bi-speedometer"></i><span class="app-menu__label">Inicio</span></a></li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon bi bi-laptop"></i><span class="app-menu__label">Libros</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="?c=Libro"><i class="icon bi bi-circle-fill"></i> Listar Todos</a></li>
          <li><a class="treeview-item" href="?c=Libro&a=MisLibros"><i class="icon bi bi-circle-fill"></i> Mis Libros</a></li>
          <li><a class="treeview-item" href="?c=Libro&a=Crear"><i class="icon bi bi-circle-fill"></i> Crear</a></li>
        </ul>
      </li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon bi bi-ui-checks"></i><span class="app-menu__label">Forms</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="?c=Libro&a=FormCrear"><i class="icon bi bi-circle-fill"></i> Form Crear</a></li>
          <li><a class="treeview-item" href="form-samples.html"><i class="icon bi bi-circle-fill"></i> Form Samples</a></li>
        </ul>
      </li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon bi bi-table"></i><span class="app-menu__label">Tables</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="table-basic.html"><i class="icon bi bi-circle-fill"></i> Basic Tables</a></li>
          <li><a class="treeview-item" href="table-data-table.html"><i class="icon bi bi-circle-fill"></i> Data Tables</a></li>
        </ul>
      </li>
      <li class="treeview <?php echo ($currentController === 'page' || $currentController === 'pages' || $currentController === 'usuario') ? 'is-expanded' : ''; ?>"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon bi bi-file-earmark"></i><span class="app-menu__label">Pages</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="blank-page.html"><i class="icon bi bi-circle-fill"></i> Blank Page</a></li>
          <li><a class="treeview-item" href="page-login.html"><i class="icon bi bi-circle-fill"></i> Login Page</a></li>
          <li><a class="treeview-item" href="page-lockscreen.html"><i class="icon bi bi-circle-fill"></i> Lockscreen Page</a></li>
          <li><a class="treeview-item <?php echo ($currentController === 'usuario' && $currentAction === 'perfil') ? 'active' : ''; ?>" href="page-user.html"><i class="icon bi bi-circle-fill"></i> User Page</a></li>
          <li><a class="treeview-item" href="page-invoice.html"><i class="icon bi bi-circle-fill"></i> Invoice Page</a></li>
          <li><a class="treeview-item" href="page-mailbox.html"><i class="icon bi bi-circle-fill"></i> Mailbox</a></li>
          <li><a class="treeview-item" href="page-error.html"><i class="icon bi bi-circle-fill"></i> Error Page</a></li>
        </ul>
      </li>
      <li><a class="app-menu__item" href="docs.html"><i class="app-menu__icon bi bi-code-square"></i><span class="app-menu__label">Docs</span></a></li>
    </ul>
  </aside>

  <!-- Script de búsqueda y autocomplete -->
  <script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchSuggestions = document.getElementById('searchSuggestions');
    let currentHighlight = -1;

    // Fetch sugerencias mientras el usuario escribe
    searchInput.addEventListener('input', async function(e) {
      const query = this.value.trim();
      currentHighlight = -1;

      if (query.length < 2) {
        searchSuggestions.style.display = 'none';
        return;
      }

      try {
        const response = await fetch('?c=Busqueda&a=Sugerencias&q=' + encodeURIComponent(query));
        const data = await response.json();

        if (data.length === 0) {
          searchSuggestions.style.display = 'none';
          return;
        }

        searchSuggestions.innerHTML = '';
        data.forEach((item, index) => {
          const li = document.createElement('li');
          li.className = 'dropdown-item';
          li.style.cursor = 'pointer';
          li.style.padding = '10px 15px';
          li.innerHTML = `<strong>${item.titulo}</strong> <br> <small class="text-muted">${item.autor}</small>`;
          li.setAttribute('data-id', item.id);
          
          li.addEventListener('click', function() {
            window.location.href = '?c=Libro&a=MisLibros&buscar=' + encodeURIComponent(item.titulo);
          });

          li.addEventListener('mouseenter', function() {
            document.querySelectorAll('#searchSuggestions .dropdown-item').forEach(el => el.style.backgroundColor = '');
            this.style.backgroundColor = '#f0f0f0';
            currentHighlight = index;
          });

          searchSuggestions.appendChild(li);
        });

        searchSuggestions.style.display = 'block';
      } catch (error) {
        console.error('Error fetching suggestions:', error);
      }
    });

    // Manejar teclas (Enter, Escape)
    searchInput.addEventListener('keydown', function(e) {
      const items = document.querySelectorAll('#searchSuggestions .dropdown-item');
      
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        currentHighlight = (currentHighlight + 1) % items.length;
        items.forEach(el => el.style.backgroundColor = '');
        if (items[currentHighlight]) {
          items[currentHighlight].style.backgroundColor = '#f0f0f0';
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        currentHighlight = (currentHighlight - 1 + items.length) % items.length;
        items.forEach(el => el.style.backgroundColor = '');
        if (items[currentHighlight]) {
          items[currentHighlight].style.backgroundColor = '#f0f0f0';
        }
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (currentHighlight >= 0 && items[currentHighlight]) {
          items[currentHighlight].click();
        } else if (this.value.trim().length > 0) {
          window.location.href = '?c=Busqueda&a=Buscar&q=' + encodeURIComponent(this.value.trim());
        }
      } else if (e.key === 'Escape') {
        searchSuggestions.style.display = 'none';
      }
    });

    // Botón de búsqueda
    searchBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const query = searchInput.value.trim();
      if (query.length > 0) {
        window.location.href = '?c=Busqueda&a=Buscar&q=' + encodeURIComponent(query);
      }
    });

    // Cerrar sugerencias al hacer click fuera
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.app-search')) {
        searchSuggestions.style.display = 'none';
      }
    });
  </script>
