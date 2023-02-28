<?php
$esEliminado = $datosParaVista['datos'];

echo "<div class='container'>";
if ($esEliminado) {
    echo <<<END
        <div class="alert alert-primary" role="alert">
            Sugerencia eliminada correctamente
        </div>
    END;
} else {
    echo <<<END
    <div class="alert alert-danger" role="alert">
        La sugerencia no se ha podido eliminar
    </div>
    END;
}
echo "</div>";
