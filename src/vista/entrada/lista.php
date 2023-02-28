<?php
$datos = $datosParaVista['datos'];

$entradas = $datos !== null ? $datos['entradas'] : [];
$busqueda = $datos !== null ? $datos['busqueda'] : '';

echo "<div class='container'>";
if (count($entradas) == 0 && !$busqueda) {
    echo <<<END
    <div class="alert alert-primary" role="alert">
        No hay entradas publicadas
    </div>
    END;

    } else if(count($entradas) == 0 && $busqueda) {
        echo "<div class='alert alert-primary' role='alert'>";
        echo "No hay publicaciones con ese nombre";
        echo "</div>"; 
    }else {
        //Buscador de entradas
        echo "<h4>Buscar entrada</h4>";
        echo <<<END
        <form action="index.php?controlador=entrada&accion=buscar" method="POST">
            <input type="text" id="busqueda" name="busqueda" value="{$busqueda}"/>
            <button type="submit" class="btn btn-outline-success my-2 my-sm-0" >Buscar</button>
        </form>
        END;

    echo "<div class='row'>";
    foreach ($entradas as $entrada) {
        $eliminarHtml = '';
        if ($sesion->haySesion() && $sesion->mismoUsuario($entrada->getUsuario()->getId())) {
            $eliminarHtml = <<<END
                <a href="index.php?controlador=entrada&accion=eliminar&id={$entrada->getId()}" class="btn btn-danger">Eliminar</a>
            END;
        }

        $megustaHtml = '';
        if (!$sesion->haySesion() || $sesion->mismoUsuario($entrada->getUsuario()->getId()) || $entrada->usuarioDioMegusta($sesion->getId())) {
            $megustaHtml = <<<END
                <i class="bi bi-heart-fill card-link"></i>
                ({$entrada->getNumeroMegusta()})
            END;
        } else {
            $megustaHtml = <<<END
                <a href="index.php?controlador=megusta&accion=megusta&entrada={$entrada->getId()}&usuario={$sesion->getId()}&volver=lista" class="card-link">
                    <i class="bi bi-heart"></i>
                </a>
                ({$entrada->getNumeroMegusta()})
            END;
        }

        echo "<div class='col-sm-3'>";
            echo "<div class='card'>";
            if ($entrada->getImagen()) {
                echo "<img src='{$entrada->getImagen()}' class='card-img-top' alt='Imagen de la entrada'>";
            } else {
                echo <<<END
                    <svg class="card-img-top" 
                         width="100%"
                         xmlns="http://www.w3.org/2000/svg"
                         role="img"
                         preserveAspectRatio="xMidYMid slice"
                         focusable="false">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                    </svg>
                END;
            }
            echo <<<END
                    <div class="card-body">
                        <h5 class="card-title">{$entrada->getUsuario()->getNombre()} escribió</h5>
                        <p class="card-text">{$entrada->getTexto()}</p>
                        <a href="index.php?controlador=entrada&accion=detalle&id={$entrada->getId()}" class="btn btn-primary">Detalles</a>
                        $eliminarHtml
                        $megustaHtml
                    </div>
                </div>
            END;
        echo "</div>";
    }
    echo "</div>";
}
echo "</div>";

