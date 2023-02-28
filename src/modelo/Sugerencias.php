<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBd;

class Sugerencias extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $texto,
        private int|null $id = null,
        private Usuario|null $usuario = null
    ) {
        $this->errores = [
            'texto' => $texto === null || empty($texto) ? 'El texto no puede estar vacío' : null,
            'usuario' => $usuario === null ? 'El autor de la sugerencia no puede estar vacío' : null,
        ];
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


    public function getUsuario(): Usuario|null
    {
        return $this->usuario;
    }


    public static function nuevaSugerenciaDesdePost(array $post): Sugerencias|null
    {
        // Obtenemos los campos que pueden llegar del POST
        $id = $post && isset($post['id']) ? htmlspecialchars($post['id']) : null;
        $texto = $post && isset($post['texto']) ? htmlspecialchars($post['texto']) : null;
        $usuario = $post && isset($post['usuario']) ? UsuarioBd::getUsuarioPorId(htmlspecialchars($post['usuario'])) : null;

        // Creamos la sugerencia con los datos mínimos
        $sugerencia = new Sugerencias(id: $id, texto: $texto, usuario: $usuario);

        return $sugerencia;
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

