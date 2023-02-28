<?php
namespace dwesgram\modelo;

use dwesgram\utilidades\BaseDatos;
use dwesgram\modelo\Comentario;

class ComentarioBd
{
    use BaseDatos;

    public static function insertar(Comentario $comentario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
                insert into comentario (comentario, entrada, usuario)
                values (?, ?, ?)
            END;
            $sentencia = $conexion->prepare($query);
            $texto = substr($comentario->getComentario(), 0, 128);
            $entrada = $comentario->getIdEntrada();
            $usuario = $comentario->getIdUsuario();
            $sentencia->bind_param("sii", $texto, $entrada, $usuario);
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

    public static function getComentarios(int $entradaId): array
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("
            select
                c.id, c.comentario, c.entrada, c.usuario
            from
                comentario c
            where
                c.entrada = ?
            ");

            $sentencia->bind_param("i",$entradaId);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
                
            $comentarios = [];

            if ($resultado !== false) {
              while (($fila = $resultado->fetch_assoc()) !== null) {

                    $comentario = new Comentario(
                        id: $fila['id'],
                        comentario: $fila['comentario'],
                        entrada: $fila['entrada'],
                        usuario: $fila['usuario']
                    );
                    $comentarios[] = $comentario;
                }  
            }

            
            return $comentarios;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}

