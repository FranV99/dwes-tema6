<?php
namespace dwesgram\controlador;


use dwesgram\controlador\Controlador;
use dwesgram\modelo\SugerenciasBd;
use dwesgram\modelo\Sugerencias;

class SugerenciasControlador extends Controlador
{
    //Listar las entradas
    public function lista(): array
    {
        $this->vista = 'sugerencias/lista';
        return SugerenciasBd::getSugerencias();
    }

    //Crear una entrada nueva
    public function nuevo(): Sugerencias|null
    {
        //Si no esta logueado lo mandamos fuera
        if ($this->denegar()) {
            return null;
        }

        //Si no hay post mostramos la vista de nuevo
        if (!$_POST) {
            $this->vista = 'sugerencias/nuevo';
            return null;
        }

        //Si hay post creamos la entrada con los datos que vienen por el post
        $sugerencia = Sugerencias::nuevaSugerenciaDesdePost($_POST);

        //Si la entrada no es valida, volvemos al formulario
        if (!$sugerencia->esValida()) {
            $this->vista = 'sugerencias/nuevo';
            return $sugerencia;
        }

        //la insertamos en la base de datos
        $idSugerencia = SugerenciasBd::insertar($sugerencia);
        
        //le damos un id
        $sugerencia->setId($idSugerencia);
        
        $this->vista = 'sugerencias/lista';
        header('Location: index.php?controlador=sugerencias&accion=lista');
        
        return null;
    }

    //Eliminar una entrada
    public function eliminar(): bool|null
    {
        //Si no está logueado no puede eliminar por lo que lo tiramos
        if ($this->denegar()) {
            return null;
        }

        //Si esta logueado lo mandamos a la vista para eliminar
        $this->vista = 'sugerencias/eliminado';

        //Si no hay get o no hay id no hay nada que eliminar
        if (!$_GET || !isset($_GET['id'])) {
            return false;
        }

        //Saneamos el id y llamamos a la funcion de la base de datos para que lo elimine
        return SugerenciasBd::eliminar(htmlspecialchars($_GET['id']));
    }
}