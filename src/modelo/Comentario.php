<?php
namespace dwesgram\modelo;

use dwesgram\modelo\UsuarioBd;

class Comentario extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string $comentario,
        private int $usuario,
        private int $entrada,
        private int|null $id = null
    ) {
        $this->errores = [
            'comentario' => mb_strlen($comentario) > 0 ? null : 'El comentario no puede estar vacío'
        ];
    }

    public function getComentario(): string
    {
        return $this->comentario;
    }

    public function getIdUsuario(): int
    {
        return $this->usuario;
    }

    public function getUsuario(): Usuario|null
    {
        return UsuarioBd::getUsuarioPorId($this->usuario);
    }

    public function getIdEntrada(): int
    {
        return $this->entrada;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function esValido(): bool
    {
        return count(array_filter($this->errores, fn($e) => $e !== null)) == 0;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }
}