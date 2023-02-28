<?php
namespace dwesgram\modelo;

use dwesgram\utilidades\BaseDatos;

class EstadisticasBd
{
    use BaseDatos;

    public static function getTopTresEntradasMasMeGusta(): array
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("
            select 
                entrada, count(*) as num_megusta
            from 
                megusta
            group by 
                entrada
            order by 
                num_megusta desc
            limit 3
            ");
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $entradasId = [];
            while (($fila = $resultado->fetch_assoc()) !== null) {
                $entradasId[] = $fila['entrada'];
            }
            return $entradasId;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}