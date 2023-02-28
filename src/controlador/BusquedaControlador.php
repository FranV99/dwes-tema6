<?php
namespace dwesgram\controlador;

use dwesgram\modelo\UsuarioBd;

class BusquedaControlador extends Controlador
{
    //Para practicar hacemos el buscador de usuarios por nombre
    public function buscar(): array|null
    {
        //Si no ha iniciado sesion lo tiramos
        if ($this->denegar()) {
            return null;
        }

        $this->vista = 'buscar/busqueda';

        if (!$_POST) {
            $this->vista = 'buscar/busqueda';
            return null;
        }

        $textoBusqueda = $_POST && isset($_POST['busqueda']) ? htmlspecialchars(trim($_POST['busqueda'])) : '';
        $res =  UsuarioBd::buscarUsuarioPorNombre($textoBusqueda);
        return ['usuarios' => $res, 'busqueda' => $textoBusqueda];
    }
}

