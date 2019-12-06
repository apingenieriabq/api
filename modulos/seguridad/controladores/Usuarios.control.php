<?php

class UsuariosControlador extends Controladores {

    /**
     * @api {post} seguridad/usuarios/perfil Solicitud de datos del perfil de usuario
     * @apiName perfilUsuario
     * @apiGroup Usuarios
     *
     * @apiParam {Number} usuarioID=NULL ID del Usuario dentro del sistema. Si el valor es NULL
     *  se responde con los datos del usuario logueado.
     *
     * @apiSuccess {Usuarios} DatosUsuario Datos del Usuario con el colaborador asociado.
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     *
     *
     *
     */
    public function verificarLoginCedulaColaborador() {
        // print_r($this);
        if (!empty($this->personaIDENTIFICACION) and ! empty($this->usuarioCLAVE)) {
            $resultado = Usuario::iniciarSesionColaboradorCedula($this->personaIDENTIFICACION, $this->usuarioCLAVE);
            switch ($resultado) {
                case 'FALLO CEDULA':
                    return Respuestassistema::error("La cédula no está registrado en nuestro sistema.");
                    break;
                case 'COMBINACION':
                    return Respuestassistema::error("La combinación de cédula y clave no coinciden.");
                    break;
                case 'DESACTIVO':
                    return Respuestassistema::error("El colaborador de la cédula ".$this->personaIDENTIFICACION." está DESACTIVO.");
                    break;
                case 'CORRECTO':
                    $Colaborador = new Colaboradores();
                    $Colaborador->datosPorCedula($this->personaIDENTIFICACION);
                    // print_r($Colaboradores);
                    if (!is_null($Colaborador->colaboradorID)) {
                        $Usuario = new Usuarios();
                        $Usuario->porColaboradorID($Colaborador->colaboradorID);
                    }
                    $Usuario = $Usuario->datosCompletos();
                    $Respuesta = Respuestassistema::exito("Bienvenid@ .........", $Usuario);
                    return $Respuesta;
                    break;
            }
        }
        // Usuario::cerrarSesion();
        return Respuestassistema::error("Los datos no son validos"); ;
    }

    public function verificarLoginColaborador() {
        // print_r($this);
        if (!empty($this->colaboradorCORREO) and ! empty($this->usuarioCLAVE)) {
            $Usuario = Usuario::iniciarSesionColaborador($this->colaboradorCORREO, $this->usuarioCLAVE);
            switch ($Usuario) {
                case 'FALLO CORREO':
                    return Respuestassistema::error("El correo no está registrado en nuestro sistema.");
                    break;
                case 'COMBINACION':
                    return Respuestassistema::error("La combinación de correo y clave no coinciden.");
                    break;
                case 'CORRECTO':
                default:
                    return Respuestassistema::exito("Datos del Usuario.");
                    break;
            }
        }
        // Usuario::cerrarSesion();
        return Respuestassistema::error("Los datos no son validos"); ;
    }

    public function perfil() {
        // print_r($_SESSION);
        if (!isset($this->usuarioID)) {
            $this->usuarioID = Usuario::usuarioID();
        }
        $Usuario = new Usuarios($this->usuarioID);
        echo RespuestasSistema::exito('Datos del Pefil del Usuario', $Usuario->datosCompletos());
    }

    public function mostrarMenu() {

        $user = null;
        if (isset($this->usuarioID) and ! empty($this->usuarioID)) {
            $user = new Usuarios($this->usuarioID);
            Usuario::abrirSesion($user);
        }

        $menu = null;
        if (Usuario::esAdministrador() == 'SI'):
            $menu = self::menuCompleto();
        else:
            $menu = self::menuDelUsuario(Usuario::usuarioID());
        endif;
        echo RespuestasSistema::exito("Menu del Usuario", $menu);
    }

    public static function menuCompleto() {
        $menuComponente = Componentes::todosdelMenu();
        foreach ($menuComponente as $componentes):
            $componentes->Operaciones = MenuOperaciones::menuPadresComponente($componentes->componenteID);
            foreach ($componentes->Operaciones as $OperacionMenu) {
                $OperacionMenu->SubOperaciones = MenuOperaciones::delMenu($OperacionMenu->menuID);
            }
        endforeach;
        return $menuComponente;
    }

    public static function menuDelUsuario($idUsuario) {
        $menuComponente = Componentes::delMenuPorUsuario($idUsuario);
        if ($menuComponente):
            foreach ($menuComponente as $componentes):
                $componentes->Operaciones = MenuOperaciones::menuPadresComponentePorUsuario($idUsuario, $componentes->componenteID);
                foreach ($componentes->Operaciones as $OperacionMenu) {
                    $OperacionMenu->SubOperaciones = MenuOperaciones::delMenuPorUsuario($idUsuario, $OperacionMenu->menuID);
                }
            endforeach;
        endif;
        return $menuComponente;
    }

    public static function registrarVisita() {
        $Usuario = new Usuarios();
        $Usuario->registrarUltimaVisita(Usuario::ip(), Usuario::latitud(), Usuario::longitud());
    }

}
