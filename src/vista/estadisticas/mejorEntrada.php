<?php
$topEntradas = $datosParaVista['datos'];

//Si no hay entradas
echo "<div class='container'>";
if ($topEntradas == null) {
    echo <<<END
    <div class="alert alert-primary" role="alert">
        No hay estadísticas
    </div>
    END;
} else {
    echo '<h1>Top 3 Entradas</h1>';
    echo '<br>';
    foreach ($topEntradas as $top)
    {
        //Mostramso las entradas, los likes que tienen y los usuarios que dieron like
        echo '<h5>' . $top->getEntrada()->getTexto() . '</h5>';
        echo '<p>' . 'Likes: ' . $top->getNumMegusta() . '</p>';
        echo '<p> Usuarios que dieron like: </p>';
        foreach ($top->getUsuariosMeGusta() as $usuario){
            echo '<div>';
            echo $usuario->getNombre();
            //Mostramos el avatar del usuario, si no tiene le mostramos uno por defecto
            if ($usuario->getAvatar()) {
                echo '<img class="rounded float-start me-2" width="32px" src="' . $usuario->getAvatar . '" </img>';
            } else {
                echo '<img class="rounded float-start me-2" width="32px" src=".\assets\img\bender.png"></img>';
            }
            echo '</div>';
            echo '<br>';
        }
    }
}
echo "</div>";