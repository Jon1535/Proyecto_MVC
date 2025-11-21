    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="bi bi-table"></i> Libros</h1>
          <p>Libros de la Base de Datos</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active"><a href="#">Data Table</a></li>
        </ul>

        
        <div class="btn-group" role="group">
          <a class="btn btn-primary" href="?c=Libro&a=Crear" title="Agregar"><i class="bi bi-plus-lg"></i></a>

          <a class="btn btn-info" href="#" title="Editar"><i class="bi bi-arrow-repeat"></i></a>

          <a class="btn btn-warning" href="#" title="Eliminar"><i class="bi bi-trash"></i></a>
        </div>
      </div>
      

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th>ID_Libro</th>
                      <th>Titulo</th>
                      <th>Autor</th>
                      <th>ID_Categoría</th>
                      <th>Descripcion</th>
                      <th>Imagen_url</th>
                      <th>ID_Propietario</th>
                      <th>Estado</th>
                      <th>Condicion</th>
                      <th>Fecha_publicacion</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($this->modelo->Listar() as $r):?>
                    <tr>
                        <td><?= $r->id_libro?></td>
                        <td><?= $r->titulo?></td>
                        <td><?= $r->autor?></td>
                        <td><?= $r->id_categoria?></td>
                        <td><?= $r->descripcion?></td>
                        <td><?= $r->imagen_url?></td>
                        <td><?= $r->id_propietario?></td>
                        <td><?= $r->estado?></td>
                        <td><?= $r->condicion?></td>
                        <td><?= $r->fecha_publicacion?></td>
                        <td>
                          <a href="?c=Libro&a=Editar&id=<?= $r->id_libro?>">Editar</a> |
                          <a href="?c=Libro&a=Eliminar&id=<?= $r->id_libro?>" onclick="return confirm('¿Eliminar este libro? Esta acción no se puede deshacer.');">Eliminar</a>
                        </td>
                    </tr>
                      <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>