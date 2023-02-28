<?php
namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Entrada;
use dwesgram\utilidades\Ficheros;
use dwesgram\modelo\ComentarioBd;

class EntradaControlador extends Controlador
{
    //Listar las entradas
    public function lista(): array
    {
        $this->vista = 'entrada/lista';
        return ['entradas' => EntradaBd::getEntradas(), 'busqueda' => ''];
    }

    //Mostrar una entrada en concreto
    public function detalle(): Entrada|null
    {
        $this->vista = 'entrada/detalle';

        $id = $_GET && isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
        //Si hay id
        if ($id !== null) {
            //Saca el id de la entrada
            $entrada = EntradaBd::getEntrada($id);
            //Si no hay entrada devuelve null
            if ($entrada === null) {
                return null;
            }
            //Saca los comentarios por el id del usuario
            $comentarios = ComentarioBd::getComentarios($id);

            //Añade el comentario indicado dentro de los comentarios
            foreach ($comentarios as $comentario) {
                $entrada->addComentario($comentario);
            }

            return $entrada;
        } else {
            return null;
        }
    }

    //Crear una entrada nueva
    public function nuevo(): Entrada|null
    {
        //Si no esta logueado lo mandamos fuera
        if ($this->denegar()) {
            return null;
        }

        //Si no hay post mostramos la vista de nuevo
        if (!$_POST) {
            $this->vista = 'entrada/nuevo';
            return null;
        }

        //Si hay post creamos la entrada con los datos que vienen por el post y files
        $entrada = Entrada::nuevaEntradaDesdePost($_POST, $_FILES);

        //Si la entrada no es valida, volvemos al formulario
        if (!$entrada->esValida()) {
            $this->vista = 'entrada/nuevo';
            return $entrada;
        }

        //Si la entrada es válida, subimos la imagen
        if ($entrada->getImagen()) {
            $resultado = Ficheros::subirImagen($_FILES['imagen'], $entrada->getImagen());

            //Si no se puede subir la imagen, mostramos el error y volvmeos a la vista
            if ($resultado !== null) {

                $entrada->setError('imagen', $resultado);

                $this->vista = 'entrada/nuevo';

                return $entrada;
            }
        }

        //Si todo ha ido bien vamos a la vista de la entrada creada
        $this->vista = 'entrada/detalle';
        //la insertamos en la base de datos
        $idEntrada = EntradaBd::insertar($entrada);
        //le damos un id
        $entrada->setId($idEntrada);

        return $entrada;
    }

    //Eliminar una entrada
    public function eliminar(): bool|null
    {
        //Si no está logueado no puede eliminar por lo que lo tiramos
        if ($this->denegar()) {
            return null;
        }

        //Si esta logueado lo mandamos a la vista para eliminar
        $this->vista = 'entrada/eliminado';

        //Si no hay get o no hay id no hay nada que eliminar
        if (!$_GET || !isset($_GET['id'])) {
            return false;
        }

        //Saneamos el id y llamamos a la funcion de la base de datos para que lo elimine
        return EntradaBd::eliminar(htmlspecialchars($_GET['id']));
    }

    //Para practicar hacemos el buscador
    public function buscar(): array|null
    {
        $this->vista = 'entrada/lista';

        if (!$_POST) {
            $this->vista = 'entrada/lista';
            return null;
        }
        
        $busqueda = htmlspecialchars($_POST['busqueda']);
        $res =  EntradaBd::buscarPostPorNombre($busqueda);
        return ['entradas' => $res, 'busqueda' => $busqueda];
    }

}
