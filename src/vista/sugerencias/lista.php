<?php
$sugerencias = $datosParaVista['datos'];
echo "<div class='container'>";

if ($sugerencias == null) {
    echo <<<END
    <div class="alert alert-primary" role="alert">
        No hay sugerencias
    </div>
    END;
}

    echo "<div class='row'>";
    foreach ($sugerencias as $sugerencia) {
        $eliminarHtml = '';
        if ($sesion->haySesion() && $sesion->mismoUsuario($sugerencia->getUsuario()->getId())) {
            $eliminarHtml = <<<END
                <a href="index.php?controlador=sugerencias&accion=eliminar&id={$sugerencia->getId()}" class="btn btn-danger">Eliminar</a>
            END;
        }

        echo "<div class='col-sm-3'>";
            echo "<div class='card'>";
            echo <<<END
                    <div class="card-body">
                        <h5 class="card-title">{$sugerencia->getUsuario()->getNombre()} escribió</h5>
                        <p class="card-text">{$sugerencia->getTexto()}</p>
                        $eliminarHtml
                    </div>
                </div>
            END;
        echo "</div>";
        }
    echo "</div>";
echo "</div>";

