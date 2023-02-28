<?php
$sugerencia = $datosParaVista['datos'];
$errores = $sugerencia !== null ? $sugerencia->getErrores() : null;
$texto = $sugerencia ? $sugerencia->getTexto() : '';
?>

<div class="container">
    <h1>Nueva sugerencia</h1>
    <form action="index.php?controlador=sugerencias&accion=nuevo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="usuario" id="usuario" value="<?= $sesion->getId() ?>">
        <div class="mb-3">
            <label for="texto" class="form-label">
                Escribe tu sugerencia
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
        <button type="submit" class="btn btn-primary">Enviar sugerencia</button>
    </form>
</div>
