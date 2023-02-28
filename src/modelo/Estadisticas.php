<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Entrada;

class Estadisticas
{
    public function __construct(
        private Entrada $entrada,
        private int $numMeGusta,
        private array $usuariosMeGusta
    )
    {}

    public function getEntrada(): Entrada
    {
        return $this->entrada;
    }

    public function getNumMeGusta(): int
    {
        return $this->numMeGusta;
    }

    public function getUsuariosMeGusta(): array
    {
        return $this->usuariosMeGusta;
    }

    public function setEntrada(Entrada $entrada): void
    {
        $this->entrada = $entrada;
    }

    public function setNumMeGusta(int $numMeGusta): void
    {
        $this->numMeGusta = $numMeGusta;
    }

    public function setUsuariosMeGusta(array $usuariosMeGusta): void
    {
        $this->usuariosMeGusta = $usuariosMeGusta;
    }
}