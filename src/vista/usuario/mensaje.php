<?php
$datos = $datosParaVista['datos'];

echo <<<END
    <div class='container'>
    <p><a href="index.php">Volver a la página inicial</a></p>
END;

if ($datos['resultado'] === true) {
    echo <<<END
        <div class="alert alert-success" role="alert">
            {$datos['mensaje']}
        </div>
    END;
} else {
    echo <<<END
        <div class="alert alert-danger" role="alert">
            {$datos['mensaje']}
        </div>
    END;
}

echo "</div>";