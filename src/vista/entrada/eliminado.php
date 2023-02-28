<?php
$esEliminado = $datosParaVista['datos'];

echo "<div class='container'>";
if ($esEliminado) {
    echo <<<END
        <div class="alert alert-primary" role="alert">
            Entrada eliminada correctamente
        </div>
    END;
} else {
    echo <<<END
    <div class="alert alert-danger" role="alert">
        La entrada no se ha podido eliminar
    </div>
    END;
}
echo "</div>";
