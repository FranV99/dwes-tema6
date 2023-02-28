<?php
namespace dwesgram\utilidades;


use dwesgram\config\MIME_IMAGENES_PERMITIDOS;

class Ficheros
{
    /**
     * Intenta subir la imagen.
     * 
     * Si se puede subir devuelve null.
     * Si hay algún error devuelve el string describiendo el error que se ha producido.
     */
    public static function subirImagen(array $fichero, string $rutaDestino): string|null
    {
        // Comprobamos si se ha subido el fichero correctamente
        if ($fichero['error'] !== UPLOAD_ERR_OK || $fichero['size'] === 0) {
            return 'No se ha podido subir la imagen';
        }

        // Comprobamos que el fichero tiene un MIME type permitido
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_fichero = finfo_file($finfo, $fichero['tmp_name']);
        if (!in_array($mime_fichero, MIME_IMAGENES_PERMITIDOS)) {
            return 'Solo se permiten imágenes PNG y JPEG';
        }

        // Se mueve el fichero temporal a la carpeta del servidor
        if (move_uploaded_file($fichero['tmp_name'], $rutaDestino)) {
            return null;
        } else {
            return 'No se ha podido mover el fichero subido';
        }
    }
}
