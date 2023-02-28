<?php
$entrada = $datosParaVista['datos'];

if (!$sesion->haySesion() || 
    $sesion->mismoUsuario($entrada->getUsuario()->getId()) || 
    $entrada->usuarioDioMegusta($sesion->getId())) {
    $megustaHtml = <<<END
        <i class="bi bi-heart-fill card-link"></i>
        ({$entrada->getNumeroMegusta()})
    END;
} else {
    $megustaHtml = <<<END
        <a href="index.php?controlador=megusta&accion=megusta&entrada={$entrada->getId()}&usuario={$sesion->getId()}&volver=detalle" class="card-link">
            <i class="bi bi-heart"></i>
        </a>
        ({$entrada->getNumeroMegusta()})
    END;
}

$eliminarHtml = '';

if ($sesion->haySesion() && $sesion->mismoUsuario($entrada->getUsuario()->getId())) {
    $eliminarHtml = <<<END
        <a href="index.php?controlador=entrada&accion=eliminar&id={$entrada->getId()}" class="btn btn-danger">Eliminar</a>
    END;
}

echo '<div class="container"><div class="row">';
if (!$entrada || $entrada->getId() === null) {
    echo <<<END
        <div class="alert alert-danger" role="alert">
            No hay entrada que mostrar
        </div>
    END;
} else {
    echo <<<END
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3>{$entrada->getUsuario()->getNombre()} escribió</h3>
            </div>
            <div class="col-md-4 text-end">
                $eliminarHtml
                $megustaHtml
            </div>
        </div>
        <div class="row mt-2 justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-primary" role="alert">
                    {$entrada->getTexto()}
                </div>
            </div>
        </div>
    END;

    if ($entrada->getImagen()) {
        echo <<<END
            <div class="row mt-2 justify-content-center">
                <div class="col-md-8">
                    <div class="text-center">
                        <img src="{$entrada->getImagen()}" class="rounded" width="100%" alt="Imagen de la entrada">
                    </div>
                </div>
            </div>
        END;
    }
}

//formulario para comentar
if ($sesion->haySesion()) {
    echo <<<END
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-8">
                <h2>Comentarios</h2>
                <form action="index.php?controlador=comentario&accion=nuevo&entrada={$entrada->getId()}&usuario={$sesion->getId()}" method="post">
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Escribe tu comentario:</label>
                        <textarea class="form-control" id="comentario" name="comentario"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </form>
            </div>
        </div class="row">
    END;

    echo <<<END
        <div class="row justify-content-center mt-4">
            <div class="col-8">
                <div class="list-group">
    END;
    $numComentario = 1;
    foreach ($entrada->getComentarios() as $comentario) {
        $usuario = $comentario->getUsuario();
        echo <<<END
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{$usuario->getNombre()} comentó:</h5>
                    <small>#$numComentario</small>
                </div>
                <p class="mb-1">{$comentario->getComentario()}</p>
            </div>
        END;
        $numComentario++;
    }
    echo '</div></div></div>';
} else {
    echo '</div>';
}

echo '</div>';
