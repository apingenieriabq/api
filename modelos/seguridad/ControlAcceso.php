<?php


class ControlAcceso {

    public static function delUsaurio($idUsuario, $operacion){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
          . ' WHERE (Usuarios.usuarioID = ? OR UsuariosRol.usuarioID = ?) AND MenuOperaciones.menuFUNCION = ?';
        if(!empty(BasededatosAP::selectUnaFila($sqlQuery, array($idUsuario, $idUsuario, $operacion)))):
            return true;
        endif;
        return false;
    }

    public static function delUsaurioPorCodigoOperacion($idUsuario, $operacion){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
        . ' WHERE (Usuarios.usuarioID = ? OR UsuariosRol.usuarioID = ?) AND MenuOperaciones.menuCODIGO = ?';
        if(!empty(BasededatosAP::selectUnaFila($sqlQuery, array($idUsuario, $idUsuario, $operacion)))):
            return true;
        endif;
        return false;
    }

    public static function porIp($usuario){
        // if(!empty($usuario->aplicacionID)):
        //     $aplicacion = Aplicaciones::datos($usuario->aplicacionID);
        //     if(TiposAplicaciones::MOVIL != $aplicacion->aplicacionTipoID):
        //         if(Cliente::ip() != $usuario->apiIP):
        //             return false;
        //         endif;
        //     endif;
        // endif;
        return true;
    }

}