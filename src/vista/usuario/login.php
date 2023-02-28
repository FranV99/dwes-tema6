<?php
$datos = $datosParaVista['datos'];
$error = $datos && isset($datos['error']) ? $datos['error'] : null;
$nombre = $datos && isset($datos['nombre']) ? $datos['nombre'] : '';
?>

<div class="container">
    <h1>Inicia sesión</h1>

    <?php
    if ($error) {
        echo <<<END
            <div class="alert alert-danger" role="alert">
                $error
            </div>
        END;
    }
    ?>

    <form action="index.php?controlador=usuario&accion=login" method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de usuario</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre ?>">
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label><br>
            <input type="password" id="clave" name="clave">
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
