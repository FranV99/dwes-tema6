<?php

namespace dwesgram\controlador;

use dwesgram\modelo\Estadisticas;
use dwesgram\modelo\EstadisticasBd;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\MegustaBd;
use dwesgram\modelo\UsuarioBd;

class EstadisticasControlador extends Controlador
{
    public function lista(): array
    {
        // Creamos un array vacío para almacenar las estadísticas de las tres entradas más populares.
        $topTres = [];

        // Obtenemos los IDs de las tres entradas más populares.
        $entradasId = EstadisticasBd::getTopTresEntradasMasMeGusta();

        // Iteramos sobre cada uno de los IDs de entrada para obtener la información correspondiente.
        foreach ($entradasId as $entradaId) {
            // Obtenemos la entrada correspondiente
            $entrada = EntradaBd::getEntrada($entradaId);

            // Si la entrada no existe, saltamos a la siguiente iteración.
            if (!$entrada) {
                continue;
            }

            // Obtenemos los IDs de los usuarios que dieron "Me gusta".
            $usuariosMeGusta = MegustaBd::getUsuarios($entradaId);

            // Creamos un array vacío para almacenar los objetos de usuario correspondientes a cada uno de los IDs de usuario obtenidos.
            $usuarios = array_map(function ($usuarioId) {
                // Obtenemos el usuario correspondiente al ID utilizando el método getUsuarioPorId() de la clase UsuarioBd.
                return UsuarioBd::getUsuarioPorId($usuarioId);
            }, $usuariosMeGusta);

            // Creamos un objeto de estadísticas utilizando la entrada, la cantidad de "Me gusta" y la lista de usuarios que dieron "Me gusta".
            $estadisticas = new Estadisticas(
                entrada: $entrada,
                numMeGusta: $entrada->getNumeroMegusta(),
                usuariosMeGusta: $usuarios
            );

            // Agregamos el objeto de estadísticas al array de las tres entradas más populares.
            array_push($topTres, $estadisticas);
        }

        // Establecemos la vista a 'estadisticas/mejorEntrada'.
        $this->vista = 'estadisticas/mejorEntrada';

        // Devolvemos el array de las tres entradas más populares.
        return $topTres;
    }
}
