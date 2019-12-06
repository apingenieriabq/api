<?php

class Componentes {


    public static function todosdelMenu(){
        $sqlQuery = ComponentesSQL::DATOS
        .'WHERE BasededatosAP.MenuComponentes.componenteMENU = "SI" '
        . "GROUP BY BasededatosAP.MenuComponentes.componenteID  "
        . "ORDER BY BasededatosAP.MenuComponentes.componenteORDEN  ";
        return BasededatosAP::selectVariasFilas($sqlQuery, array());
    }

    public static function delMenuPorUsuario($idUsuario){
        $sqlQuery = ControlAccesoSQL::COMPONENTES_POR_USUARIO_Y_COMPONENTES
        . ' WHERE BasededatosAP.MenuComponentes.componenteMENU = "SI" '
        . 'AND BasededatosAP.MenuOperaciones.menuMENU = "SI" '
        . 'AND ( '
        . ' (BasededatosAP.Usuarios.usuarioID = ? OR BasededatosAP.UsuariosRol.usuarioID = ? )  '
        . ' OR BasededatosAP.MenuOperaciones.menuPUBLICO = "SI" '
        . ') '
        . 'GROUP BY MenuComponentes.componenteID '
        . "ORDER BY MenuComponentes.componenteORDEN  ";
        return BasededatosAP::selectVariasFilas($sqlQuery, array($idUsuario, $idUsuario));
    }


    public static function todos(){
        $sqlQuery = ComponentesSQL::DATOS
        . "GROUP BY BasededatosAP.MenuComponentes.componenteID  "
        . "ORDER BY BasededatosAP.MenuComponentes.componenteORDEN  ";
        return BasededatosAP::selectVariasFilas($sqlQuery, array());
    }

    public static function datos($componenteID){
        $sqlQuery = ComponentesSQL::DATOS_COMPLETOS
            . "WHERE BasededatosAP.MenuComponentes.componenteID = ? "
            . "GROUP BY BasededatosAP.MenuComponentes.componenteID  "
            . "ORDER BY BasededatosAP.MenuComponentes.componenteORDEN  ";
        return BasededatosAP::selectUnaFila($sqlQuery, array($componenteID));
    }

    public static function todosCompleto(){
        $componentes = Componentes::todos();
        foreach ($componentes as $componente):
            $componente->controladores = ControladoresBD::delComponente($componente->componenteID);
            foreach ($componente->controladores as $controlador):
                $controlador->operaciones = Operaciones::delControlador($controlador->controladorID);
            endforeach;
        endforeach;
        return $componentes;
    }


    public static function porUsuario($idUsuario){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
            . ' WHERE BasededatosAP.Usuarios.usuarioID = ? OR BasededatosAP.UsuariosRol.usuarioID = ?';
        return BasededatosAP::selectVariasFilas($sqlQuery, array($idUsuario, $idUsuario));
    }


    /**
     * Recibe Todos los datos de necesarios para la creación de un nuevo
     * registro de ControlOperaciones.
     * @param int $categoriaID Identificador de la Categoria
     * @param String $operacionCODIGO Código de ControlOperaciones
     * @param String $operacionTITULO Titulo de ControlOperaciones
     * @param Texto $operacionDESCRIPCION Descripción de ControlOperaciones
     * @param Url $operacionURL Url del archivo asociado al ControlOperaciones
     * @param String $operacionESTADO Estado del registro de ControlOperaciones
     * @return int con el Identificador del Registro ControlOperaciones
     */
    public static function guardar($componenteORDEN , $componenteMENU , $componenteMENUICONO , $componenteMENUTITULO , $componenteCARPETA , $componenteCODIGO , $componenteTITULO , $componenteESTADO , $componenteDESCRIPCION) {
        $sqlQuery = ComponentesSQL::CREAR_REGISTRO;
        return BasededatosAP::insertFila($sqlQuery, array($componenteORDEN , $componenteMENU , $componenteMENUICONO , $componenteMENUTITULO , $componenteCARPETA , $componenteCODIGO , $componenteTITULO , $componenteESTADO , $componenteDESCRIPCION, Cliente::usuarioID() ) );
    }

    /**
     * Recibe los todos los datos del registro para ser actualizados, junto con
     * el identificador del registro que se va ha actualizar.
     * @param int $operacionID Identificador del Registro de ControlOperaciones
     * @param int $categoriaID Identificador de la Categoria
     * @param String $operacionCODIGO Código de ControlOperaciones
     * @param String $operacionTITULO Titulo de ControlOperaciones
     * @param Texto $operacionDESCRIPCION Descripción de ControlOperaciones
     * @param Url $operacionURL Url del archivo asociado al ControlOperaciones
     * @param String $operacionESTADO Estado del registro de ControlOperaciones
     * @return int cantidad de registros actualziados en la operacion
     */
    public static function actualizar($componenteID,$componenteORDEN , $componenteMENU , $componenteMENUICONO , $componenteMENUTITULO , $componenteCARPETA , $componenteCODIGO , $componenteTITULO , $componenteESTADO , $componenteDESCRIPCION) {
        $sqlQuery = ComponentesSQL::ACTUALIZAR_REGISTRO;
        return BasededatosAP::actualizarFila($sqlQuery, array(
                $componenteORDEN , $componenteMENU , $componenteMENUICONO , $componenteMENUTITULO ,
                $componenteCARPETA , $componenteCODIGO , $componenteTITULO , $componenteESTADO ,
                $componenteDESCRIPCION, $componenteID
            )
        );
    }


}