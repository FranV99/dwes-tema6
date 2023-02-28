<?php
namespace dwesgram\modelo;

use dwesgram\utilidades\BaseDatos;
use dwesgram\modelo\Megusta;

class MegustaBd
{
    use BaseDatos;

    public static function insertar(Megusta $megusta): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("
            insert into 
                megusta (usuario, entrada)
            values (?, ?)
            ");
            
            $usuario = $megusta->getUsuario();
            $entrada = $megusta->getEntrada();
            $sentencia->bind_param("ii", $usuario, $entrada);
            $resultado = $sentencia->execute();

            if ($resultado) {
                return $conexion->insert_id;

            } else {
                return null;
            }
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }


    //Obtienemos los usuarios que han dado megusta por su id
    public static function getUsuarios(int $idEntrada): array
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("
            select 
                usuario 
            from 
                megusta 
            where 
                entrada = ?");
            $sentencia->bind_param('i', $idEntrada);
            $sentencia->execute();
            $resultado = $sentencia->get_result();

            $usuariosId = [];

            while (($fila = $resultado->fetch_assoc()) !== null) {
                $usuariosId[] = $fila['usuario'];
            }
            return $usuariosId;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}
