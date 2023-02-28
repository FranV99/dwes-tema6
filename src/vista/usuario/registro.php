<?php
$usuario = $datosParaVista['datos'];
$errores = $usuario ? $usuario->getErrores() : null;
$nombre = $usuario ? $usuario->getNombre() : '';
$email = $usuario ? $usuario->getEmail() : '';
?>

<div class="container">
    <h1>Regístrate</h1>

    <form action="index.php?controlador=usuario&accion=registro" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de usuario</label><br>
            <?php
            if ($errores && isset($errores['nombre']) && $errores['nombre'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['nombre']}
                    </div>
                END;
            }
            ?>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label><br>
            <?php
            if ($errores && isset($errores['email']) && $errores['email'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['email']}
                    </div>
                END;
            }
            ?>
            <input type="email" id="email" name="email" value="<?= $email ?>">
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label><br>
            <?php
            if ($errores && isset($errores['clave']) && $errores['clave'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['clave']}
                    </div>
                END;
            }
            ?>
            <input type="password" id="clave" name="clave">
        </div>
        <div class="mb-3">
            <label for="repiteclave" class="form-label">Repite la contraseña</label><br>
            <input type="password" id="repiteclave" name="repiteclave">
        </div>
        <div class="mb-3">
            <label for="avatar">Puedes elegir un avatar</label><br>
            <?php
            if ($errores && isset($errores['avatar']) && $errores['avatar'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['avatar']}
                    </div>
                END;
            }
            ?>
            <input class="form-control" type="file" name="avatar" id="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Crear cuenta</button>
    </form>
</div>
