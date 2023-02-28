<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DWESgram</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">DWESgram</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controlador=estadisticas&accion=lista">Ver estadísticas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?controlador=sugerencias&accion=lista">Ver sugerencias</a>
        </li>
      <?php
      if ($sesion->haySesion()) {
        echo <<<END
          <li class="nav-item">
            <a class="nav-link" href="index.php?controlador=entrada&accion=nuevo">Crear entrada</a>
          </li>
          <li>
            <a class="nav-link" href="index.php?controlador=sugerencias&accion=nuevo">Crear sugerencia</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?controlador=busqueda&accion=buscar">Buscar Usuario</a>
          </li>
          </ul>
          <div class='d-flex me-2 my-2'>
            <img class="rounded float-start me-2" width="32px" src="{$sesion->getAvatar()}">
            <a href="index.php?controlador=usuario&accion=logout">
              Cerrar sesión ({$sesion->getNombre()})
            </a>
          </div>
        END;
      } else {
        echo <<<END
          </ul>
          <form action='index.php' method='get' class='d-flex me-2 my-2'>
            <input type="hidden" name="controlador" value="usuario">
            <input type="hidden" name="accion" value="login">
            <input class="btn btn-outline-success" type="submit" value="Inicia sesión">
          </form>
          <form action='index.php' method='get' class='d-flex'>
          <input type="hidden" name="controlador" value="usuario">
          <input type="hidden" name="accion" value="registro">
            <input class="btn btn-outline-success" type="submit" value="Crea una cuenta">
          </form>
        END;
      }
      ?>     
    </div>
  </div>
</nav>
