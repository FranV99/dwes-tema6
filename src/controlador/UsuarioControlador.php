<?php
namespace dwesgram\controlador;


use dwesgram\controlador\Controlador;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBd;
use dwesgram\utilidades\Ficheros;

class UsuarioControlador extends Controlador
{
    public function login(): array|string|null
    {
        // Si no hay POST mostramos el formulario de login sin más
        if (!$_POST) {
            $this->vista = 'usuario/login';
            return null;
        }

        // Si hay POST vamos a comprobar que se autenticado bien
        $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : null;
        $clave = isset($_POST['clave']) ? htmlspecialchars($_POST['clave']) : null;
        $usuario = UsuarioBd::getUsuarioPorNombre($nombre);
        
        if ($usuario && password_verify($clave, $usuario->getClave())) {
            $this->vista = 'usuario/mensaje';
            $_SESSION['usuario'] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'avatar' => $usuario->getAvatar()
            ];
            return [
                'resultado' => true,
                'mensaje' => 'Has iniciado sesión correctamente'
            ];
        }

        // Nombre de usuario o contraseña no válidos
        $this->vista = 'usuario/login';
        return [
            'nombre' => $nombre,
            'error' => 'Usuario y/o contraseña no válido'
        ];
    }

    public function registro(): Usuario|array|null
    {
        // Si no hay POST mostramos el formulario
        if (!$_POST) {
            $this->vista = 'usuario/registro';
            return null;
        }

        // Si ha POST creamos el usuario con los datos que vienen por POST
        $usuario = Usuario::crearUsuarioDesdePost($_POST, $_FILES);

        // Si el usuario no es válido mostramos el formulario con los datos del objeto
        if (!$usuario->esValido()) {
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        // Si ya existe un usuario con ese nombre mostramos el formulario con los errores
        $usuarioBd = UsuarioBd::getUsuarioPorNombre($usuario->getNombre());
        if ($usuarioBd) {
            $usuario->setError('nombre', 'Este nombre no está libre, elige otro');
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        // Subimos el avatar. Si hay error volvemos al formulario
        if ($usuario->getAvatar()) {
            $resultado = Ficheros::subirImagen($_FILES['avatar'], $usuario->getAvatar());
            if ($resultado !== null) {
                $usuario->setError('avatar', $resultado);
                $this->vista = 'usuario/registro';
                return $usuario;
            }
        }

        // Todo correcto: se inserta el usuario
        $this->vista = 'usuario/mensaje';
        UsuarioBd::insertar($usuario);
        return [
            'resultado' => true,
            'mensaje' => 'Te has registrado correctamente y ya puedes iniciar sesión'
        ];
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
