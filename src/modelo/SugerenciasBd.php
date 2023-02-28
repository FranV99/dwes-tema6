<?php
namespace dwesgram\modelo;

use dwesgram\utilidades\BaseDatos;
use dwesgram\modelo\Sugerencias;
use dwesgram\modelo\Usuario;


class SugerenciasBd
{
    use BaseDatos;

    //Devuelve una sugerencia por su id
    public static function getSugerencia(int $id): Sugerencias|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("
            select
                e.id sugerencias_id,
                e.texto sugerencias_texto,
                u.id usuario_id,
                u.nombre usuario_nombre,
                u.avatar usuario_avatar
            from
                sugerencias e, usuario u
            where
                e.id = ? and
                e.autor = u.id
            ");

            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            if ($resultado === false) {
                return null;
            }

            $fila = $resultado->fetch_assoc();
            $usuario = new Usuario(
                id: $fila['usuario_id'],
                nombre: $fila['usuario_nombre'],
                avatar: $fila['usuario_avatar']
            );
            $sugerencias = new Sugerencias(
                id: $fila['sugerencias_id'],
                texto: $fila['sugerencias_texto'],
                usuario: $usuario
            );
            return $sugerencias;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //Devuelve todas las sugerencias
    public static function getSugerencias(): array
    {
        try {
            $conexion = BaseDatos::getConexion();

            $resultado = $conexion->query("
            select * 
            from 
                sugerencias 
            order by 
                creado desc
            ");
            $sugerenciasArray = [];
            while (($fila = $resultado->fetch_assoc()) !== null) {
                $sugerencias = new Sugerencias(
                    id: $fila['id'],
                    texto: $fila['texto'],
                    usuario: UsuarioBd::getUsuarioPorId($fila['autor'])
                );
                $sugerenciasArray[] = $sugerencias;
            }
            return $sugerenciasArray;

        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }

    //Inserta las sugerencias en la base de datos
    public static function insertar(Sugerencias $sugerencias): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("
            insert into 
                sugerencias (texto, autor)
            values 
                (?, ?)");
                
            $texto = substr($sugerencias->getTexto(), 0, 128);
            $usuario = $sugerencias->getUsuario() !== null ? $sugerencias->getUsuario()->getId() : null;
            $sentencia->bind_param("si", $texto, $usuario);
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

    

    //Elimina una sugerencia
    public static function eliminar(int $id): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("
            delete from 
                sugerencias 
            where 
                id = ?");
                
            $sentencia->bind_param("i", $id);
            return $sentencia->execute();
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
