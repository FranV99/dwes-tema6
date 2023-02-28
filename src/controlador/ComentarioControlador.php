<?php
namespace dwesgram\controlador;

use dwesgram\modelo\Comentario;
use dwesgram\modelo\ComentarioBd;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;

class ComentarioControlador extends Controlador
{
    public function nuevo(): Entrada|null
    {
        //Si no ha iniciado sesion lo tiramos
        if ($this->denegar()) {
            return null;
        }

        $this->vista = 'entrada/detalle';

        $idEntrada = $_GET && isset($_GET['entrada']) ? htmlspecialchars($_GET['entrada']) : null;
        $idUsuario = $_GET && isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : null;

        //Definimos la entrada
        $entrada = EntradaBd::getEntrada($idEntrada);

        //Si no hay entrada o usuario devolvemos null
        if ($entrada === null || $idUsuario === null) {
            return null;
        }

        // Si no hay post no creamos nada y volvemos al detalle de la entrada
        if (!$_POST || !isset($_POST['comentario'])) {
            return $entrada;
        }

        // Hacemos un foreach para recorrer los comentarios y cargarlos
        foreach (ComentarioBd::getComentarios($idEntrada) as $comentario) {
            $entrada->addComentario($comentario);
        }

        //Saneamos el comentario
        $texto = htmlspecialchars(trim($_POST['comentario']));
        
        //El objeto Comentario tendrá texto,la entrada y el usuario
        $comentario = new Comentario(

            comentario: $texto,
            entrada: $idEntrada,
            usuario: $idUsuario

        );
        //Comprobamos si es válido
        if ($comentario->esValido()) {
            //Insertamos el comentario a la base de datos
            $resultado = ComentarioBd::insertar($comentario);

            //Si no está vacio
            if ($resultado !== null) {
                
                //Le da un id al comentario
                $comentario->setId($resultado);

                //Lo añade a la entrada
                $entrada->addComentario($comentario);
            }
        }

        return $entrada;
    }
}
