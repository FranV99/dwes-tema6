<?php
$entrada = $datosParaVista['datos'];
$errores = $entrada !== null ? $entrada->getErrores() : null;
$texto = $entrada ? $entrada->getTexto() : '';
?>

<div class="container">
    <h1>Nueva entrada</h1>
    <form action="index.php?controlador=entrada&accion=nuevo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="usuario" id="usuario" value="<?= $sesion->getId() ?>">
        <div class="mb-3">
            <label for="texto" class="form-label">
                ¿En qué estás pensando? Tienes 128 caracteres para plasmarlo... el resto se ignorará
            </label>
            <?php
            if ($errores && isset($errores['texto']) && $errores['texto'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['texto']}
                    </div>
                END;
            }
            ?>
            <textarea 
                class="form-control"
                name="texto" 
                id="texto" 
                rows="3"
                placeholder="Escribe aquí el texto"><?= $texto ?></textarea>
        </div>
        <div class="mb-3">
            <label for="imagen">Selecciona una imagen para acompañar a tu entrada</label>
            <?php
            if ($errores && isset($errores['imagen']) && $errores['imagen'] !== null) {
                echo <<<END
                    <div class="alert alert-danger" role="alert">
                        {$errores['imagen']}
                    </div>
                END;
            }
            ?>
            <input class="form-control" type="file" name="imagen" id="imagen">
        </div>
        <button type="submit" class="btn btn-primary">Publicar</button>
    </form>
</div>
