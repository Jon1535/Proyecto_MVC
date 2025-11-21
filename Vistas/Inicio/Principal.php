  <main class="app-content">
    <!-- Inicio replicado desde frontend-relee (carrusel + banners + carousel de libros) -->
    <div class="container py-4">

      <!-- Carrusel principal -->
      <div id="carouselHome" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <picture>
              <source media="(min-width: 768px)" srcset="Assets/images/imagen1.1.png">
              <source media="(max-width: 767px)" srcset="Assets/images/imagen1.jpg">
              <img src="Assets/images/imagen-grande.jpg" class="d-block w-100" alt="Imagen 1">
            </picture>
          </div>
          <div class="carousel-item">
            <picture>
              <source media="(min-width: 768px)" srcset="Assets/images/imagen2.1.png">
              <source media="(max-width: 767px)" srcset="Assets/images/imagen2.jpg">
              <img src="Assets/images/imagen-grande2.jpg" class="d-block w-100" alt="Imagen 2">
            </picture>
          </div>
          <div class="carousel-item">
            <picture>
              <source media="(min-width: 768px)" srcset="Assets/images/imagen3.1.png">
              <source media="(max-width: 767px)" srcset="Assets/images/imagen3.jpg">
              <img src="Assets/images/imagen-grande3.jpg" class="d-block w-100" alt="Imagen 3">
            </picture>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
        <div class="carousel-indicators mt-3">
          <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
      </div>

      <!-- Banners (visible en md+) -->
      <div class="d-none d-md-flex banners-container justify-content-center gap-3 mt-4">
        <div class="banner" style="background-image: url('Assets/images/baner1.jpg');"></div>
        <div class="banner" style="background-image: url('Assets/images/baner2.jpg');"></div>
        <div class="banner" style="background-image: url('Assets/images/baner3.jpg');"></div>
      </div>

      <!-- Carrusel de banners para pantallas pequeñas -->
      <div id="bannersCarousel" class="carousel slide d-md-none mt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="banner" style="background-image: url('Assets/images/baner1.jpg'); height:220px;"></div>
          </div>
          <div class="carousel-item">
            <div class="banner" style="background-image: url('Assets/images/baner2.jpg'); height:220px;"></div>
          </div>
          <div class="carousel-item">
            <div class="banner" style="background-image: url('Assets/images/baner3.jpg'); height:220px;"></div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannersCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannersCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

      <!-- Carrusel de libros (componente sustituido por cards) -->
      <div class="mt-5">
        <h5 class="mb-3">Recomendados</h5>
        <div id="booksCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="card">
                    <img src="Assets/images/book1.jpg" class="card-img-top" alt="Libro 1" style="height:220px; object-fit:cover">
                    <div class="card-body">
                      <h6 class="card-title">Libro A</h6>
                      <p class="card-text text-muted small">Autor A</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <img src="Assets/images/book2.jpg" class="card-img-top" alt="Libro 2" style="height:220px; object-fit:cover">
                    <div class="card-body">
                      <h6 class="card-title">Libro B</h6>
                      <p class="card-text text-muted small">Autor B</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <img src="Assets/images/book3.jpg" class="card-img-top" alt="Libro 3" style="height:220px; object-fit:cover">
                    <div class="card-body">
                      <h6 class="card-title">Libro C</h6>
                      <p class="card-text text-muted small">Autor C</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Más slides si se desea -->
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#booksCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#booksCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

    </div>

  </main>
