<?php
namespace dwesgram\modelo;

use dwesgram\utilidades\BaseDatos;
use dwesgram\modelo\Usuario;

class UsuarioBd
{
    use BaseDatos;

    public static function getUsuarioPorId(int $id): Usuario|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("
            select 
                id, nombre, clave, email, avatar, registrado 
            from 
                usuario 
            where 
                id = ?");
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            
            if ($resultado === false) {
                echo "No se ha encontrado el usuario de la base de datos con id: $id";
                return null;
            }

            $fila = $resultado->fetch_assoc();
            if ($fila === false || $fila === null) {
                echo "No se ha encontrado la fila del usuario con id: $id";
                return null;
            }

            return new Usuario(
                id: $fila['id'],
                nombre: $fila['nombre'],
                email: $fila['email'],
                clave: $fila['clave'],
                avatar: $fila['avatar'],
                registrado: $fila['registrado']
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function getUsuarioPorNombre(string $nombre): Usuario|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("
            select 
                id, nombre, clave, email, avatar, registrado 
            from 
                usuario 
            where 
                nombre = ?");

            $sentencia->bind_param("s", $nombre);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            
            if ($resultado === false) {
                echo "No se ha encontrado el usuario con nombre: $nombre en la base de datos";
                return null;
            }

            $fila = $resultado->fetch_assoc();
            if ($fila === false || $fila === null) {
                return null;
            }

            return new Usuario(
                id: $fila['id'],
                nombre: $fila['nombre'],
                email: $fila['email'],
                clave: $fila['clave'],
                avatar: $fila['avatar'],
                registrado: $fila['registrado']
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function insertar(Usuario $usuario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("
            insert into 
                usuario (nombre, email, clave, avatar)
            values 
                (?, ?, ?, ?)
            ");
            $nombre = $usuario->getNombre();
            $email = $usuario->getEmail();
            $clave = $usuario->getClaveCifrada();
            $avatar = $usuario->getAvatar();
            $sentencia->bind_param("ssss", $nombre, $email, $clave, $avatar);
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

    public static function buscarUsuarioPorNombre(string $textoBusqueda): array
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare(" 
            select
                id,
                nombre,
                avatar
            from
                usuario
            where 
                nombre 
            like ?");

            $like = '%' . $textoBusqueda . '%';
            $sentencia->bind_param('s',$like);
            $sentencia->execute();
            $resultado = $sentencia->get_result();

            $usuarios = [];

            while (($fila = $resultado->fetch_assoc()) !== null) {
                $usuario = new Usuario(
                    id: $fila['id'],
                    nombre: $fila['nombre'],
                    avatar: $fila['avatar']
                );
                $usuarios[] = $usuario;
            }
            
            return $usuarios;

        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}
