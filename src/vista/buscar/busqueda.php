<?php
$usuarios = $datosParaVista['datos'] ? $datosParaVista['datos']['usuarios'] : [];
$busqueda = $datosParaVista['datos'] ? $datosParaVista['datos']['busqueda'] : '';

echo "<div class='container'>";
//Buscador de entradas
echo "<h4>Buscar usuario</h4>";
echo <<<END
<form action="index.php?controlador=busqueda&accion=buscar" method="POST">
    <input type="text" id="busqueda" name="busqueda" value="$busqueda"/>
    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Buscar</button>
</form>
END;

if(empty($usuarios) && $busqueda) {
    echo "<div class='alert alert-primary' role='alert'>No hay usuarios con ese nombre</div>"; 
} else if (!empty($usuarios)) {
    echo "<div class='row'>";
    echo "<div>Usuarios encontrados con el patrón ($busqueda)</div>";
    
    foreach ($usuarios as $usuario) {
        $avatar = $usuario->getAvatar() ?? 'assets/img/bender.png';
        
        echo "<div class='col-sm-3'>";
        echo "<div class='card'>";
        echo "<img src='{$avatar}' class='card-img-top' alt='Avatar del usuario'>";
        echo "<div class='card-body'><h5 class='card-title'>{$usuario->getNombre()}</h5></div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}
echo "</div>";
