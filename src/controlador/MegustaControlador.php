<?php
namespace dwesgram\controlador;


use dwesgram\controlador\Controlador;
use dwesgram\modelo\Megusta;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\MegustaBd;

class MegustaControlador extends Controlador
{
    public function meGusta(): array|Entrada
    {
        //Si no estas logueado muestra la lista de entradas
        if (!$this->permitido()) {
            $this->vista = 'entrada/lista';
            return ['entradas' => EntradaBd::getEntradas(), 'busqueda' => ''];
        }

        //Crea el objeto megusta desde el GET
        $meGusta = Megusta::nuevoMegustaDesdeGet($_GET);

        //Si no es valido devuelve la lista de entradas
        if (!$meGusta->esValido()) {
            $this->vista = 'entrada/lista';
            return ['entradas' => EntradaBd::getEntradas(), 'busqueda' => ''];
        }

        //Inserta el objeto y obtiene el id
        $id = MegustaBd::insertar($meGusta);
        //Si hay id
        if ($id !== null) {
            $meGusta->setId($id);
        }

        // Carga una vista u otra en función de la variable 'volver' en el GET
        $volver = $_GET && isset($_GET['volver']) ? htmlspecialchars($_GET['volver']) : 'lista';
        switch ($volver) {
            case 'detalle':
                $this->vista = 'entrada/detalle';
                return EntradaBd::getEntrada($meGusta->getEntrada());
            default:
                $this->vista = 'entrada/lista';
                return ['entradas' => EntradaBd::getEntradas(), 'busqueda' => ''];
        }
    }

    //Funcion para dar me gusta solo a usuarios logueados y a posts que no sean del mismo usuario
    private function permitido(): bool
    {
        //Si no ha iniciado sesion pafuera
        if ($this->denegar()) {
            return false;
        }

        //Sacamos el usuario
        $usuarioId = $_GET && isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : null;

        //Sascamos la entrada de la base de datos
        $entrada = EntradaBd::getEntrada($_GET && isset($_GET['entrada']) ? htmlspecialchars($_GET['entrada']) : null);

        //Si no hay id o entrada no esta permirido
        if ($usuarioId == null || $entrada == null) {
            return false;
        }

        //Permire darle megusta a las entradas que tengan un id diferente al del usuario
        return $usuarioId != $entrada->getUsuario()->getId();
    }
}