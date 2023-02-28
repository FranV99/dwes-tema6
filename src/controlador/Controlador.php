<?php
namespace dwesgram\controlador;

use dwesgram\utilidades\Sesion;

abstract class Controlador
{
    protected string|null $vista = null;

    //Obtener la vista
    public function getVista(): string
    {
        //Si la vista es diferente de null
        if ($this->vista !== null) {
            //La devuelve
            return $this->vista;

          //Si no manda un error
        } else {
            return "error/500.php?msg=vista-no-existe";
        }
    }

    //Si no se ha iniciado sesión devuelve una pagina de error
    public function denegar(): bool
    {
        $sesion = new Sesion();
        if (!$sesion->haySesion()) {
            $this->vista = 'errores/403';
            return true;
        }
        return false;
    }
}
