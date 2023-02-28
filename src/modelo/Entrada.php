<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBd;

class Entrada extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $texto,
        private int|null $id = null,
        private string|null $imagen = null,
        private Usuario|null $usuario = null,
        private array $listaUsuariosMegusta = [],
        private array $listaComentarios = [], 
    ) {
        $this->errores['texto'] = $texto === null || empty($texto) ? 'El texto no puede estar vacío' : null;
        $this->errores['usuario'] = $usuario === null ? 'El autor de la entrada no puede estar vacío' : null;
        $this->errores['imagen'] = null;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTexto(): string
    {
        return $this->texto ? $this->texto : '';
    }

    public function getImagen(): string|null
    {
        return $this->imagen;
    }

    public function getUsuario(): Usuario|null
    {
        return $this->usuario;
    }

    public function getNumeroMegusta(): int
    {
        return count($this->listaUsuariosMegusta);
    }

    public function getComentarios(): array
    {
        return $this->listaComentarios;
    }

    public function usuarioDioMegusta(int $usuarioId): bool
    {
        return in_array($usuarioId, $this->listaUsuariosMegusta);
    }

    public function addComentario(Comentario $comentario): void
    {
        $this->listaComentarios[] = $comentario;
    }

    private function generaRutaDestino(array $imagen): string|null
    {
        if ($imagen['error'] !== UPLOAD_ERR_OK || $imagen['size'] === 0) {
            return null;
        }
        
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombre = microtime(false);

        return CARPETA_IMAGENES . "/$nombre.$extension";
    }

    public static function nuevaEntradaDesdePost(array $post, array $files): Entrada
    {
        // Obtenemos los campos que pueden llegar del POST
        $id = $post && isset($post['id']) ? htmlspecialchars($post['id']) : null;
        $texto = $post && isset($post['texto']) ? htmlspecialchars($post['texto']) : null;
        $usuario = $post && isset($post['usuario']) ? UsuarioBd::getUsuarioPorId(htmlspecialchars($post['usuario'])) : null;

        // Creamos la entrada con los datos mínimos
        $entrada = new Entrada(id: $id, texto: $texto, usuario: $usuario);

        // Añadimos la imagen si llega y es válida
        $imagen = $files && isset($files['imagen']) ? $entrada->generaRutaDestino($files['imagen']) : null;
        if ($imagen !== null) {
            $entrada->imagen = $imagen;
        }

        return $entrada;
    }

    public function esValida(): bool
    {
        return count(array_filter($this->errores, fn($e) => $e !== null)) == 0;
    }

    public function setError(string $idx, string $mensaje): void
    {
        $this->errores[$idx] = $mensaje;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }
}
