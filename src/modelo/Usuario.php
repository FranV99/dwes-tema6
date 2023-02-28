<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Usuario extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $nombre = null,
        private int|null $id = null,
        private string|null $email = null,
        private string|null $clave = null,
        private string|null $avatar = null,
        private int|null $registrado = null
    ) {
        $this->setNombre($nombre);
        $this->id = $id;
        $this->setEmail($email);
        $this->setClave($clave, null);
    }

    private function setNombre(string|null $nombre): void
    {
        if (!$nombre) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        } else {
            $this->errores['nombre'] = null;
        }

        $this->nombre = $nombre;
    }

    private function setEmail(string|null $email): void
    {
        if (!$email) {
            $this->errores['email'] = 'El email no puede estar vacío';
        } else {
            $this->errores['email'] = null;
        }

        $this->email = $email;
    }

    private function setClave(string|null $clave, string|null $repiteClave): void
    {
        if (!$clave) {
            $this->errores['clave'] = 'La contraseña no puede estar vacía';
            return;
        }

        if(mb_strlen($clave) < 8){
            $this->errores['clave'] = 'La contraseña tiene que tener al menos 8 caracteres';
            return;
        } 

        if (!$repiteClave || $clave != $repiteClave) {
            $this->errores['clave'] = 'Las contraseñas no coinciden';
            return;
        }

        $this->errores['clave'] = null;
        $this->clave = $clave;
    }

    private function setAvatar(array $avatar): void
    {
        if ($avatar['error'] !== UPLOAD_ERR_OK || $avatar['size'] === 0) {
            $this->avatar = null;
            return;
        }

        $extension = pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $nombre = explode(' ', microtime())[1];
        $rutaDestino = CARPETA_AVATARES . '/' . $nombre . '.' . $extension;

        $this->avatar = $rutaDestino;
    }

    public function getNombre(): string|null
    {
        return $this->nombre;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function getClave(): string|null
    {
        return $this->clave;
    }

    public function getClaveCifrada(): string|null
    {
        return password_hash($this->clave, PASSWORD_DEFAULT);
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    public function setError(string $idx, string $error): void
    {
        $this->errores[$idx] = $error;
    }

    public function esValido(): bool
    {
        return count(array_filter($this->errores, fn($e) => $e !== null)) == 0;
    }

    public static function crearUsuarioDesdePost(array $post, array|null $files = null): Usuario
    {
        $usuario = new Usuario();
        $usuario->setNombre($post && isset($post['nombre']) ? htmlspecialchars(trim($post['nombre'])) : null);
        $usuario->setEmail($post && isset($post['email']) ? htmlspecialchars(trim($post['email'])) : null);
        
        $usuario->setClave(
            $post && isset($post['clave']) ? htmlspecialchars(trim($post['clave'])) : null,
            $post && isset($post['repiteclave']) ? htmlspecialchars(trim($post['repiteclave'])) : null,
        );
        
        if ($files && $files['avatar']['error'] === UPLOAD_ERR_OK && $files['avatar']['size'] > 0) {
            $usuario->setAvatar($files['avatar']);
        }

        return $usuario;
    }
}
