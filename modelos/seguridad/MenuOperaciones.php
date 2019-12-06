<?php

class MenuOperaciones {

    public function arbolCompleto(){
        $menuComponente = Componentes::todos();
        foreach ($menuComponente as $componentes):
            $componentes->Operaciones = MenuOperaciones::padresDelComponente($componentes->componenteID);
            foreach($componentes->Operaciones as $OperacionMenu){
                $OperacionMenu->SubOperaciones = MenuOperaciones::deLaOperacion($OperacionMenu->menuID);
            }
        endforeach;
        return $menuComponente;
    }

    public static function porCombinacion($componenteCODIGO, $menuOPERACION ){
        $sqlQuery = ControlAccesoSQL::DATOS_COMPLETOS .
            ' WHERE LOWER(MenuComponentes.componenteCODIGO) = ? '.
            ' AND LOWER(MenuOperaciones.menuOPERACION) = ? ';
        return BasededatosAP::selectUnaFila($sqlQuery,
            array(strtolower($componenteCODIGO), strtolower($menuOPERACION))
        );
    }

    public static function padresDelComponente($componenteID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.componenteID = ? AND MenuOperaciones.menuPADRE = 0 '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($componenteID));
    }
    public static function delComponente($componenteID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.componenteID = ?  '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($componenteID));
    }

    public static function deLaOperacion($menuID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.menuPADRE = ? '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($menuID));
    }
    public static function delMenu($menuID){
        $sqlQuery = ControlAccesoSQL::DATOS_COMPLETOS_2
            .' WHERE MenuOperaciones.menuPADRE = ? AND MenuOperaciones.menuMENU = "SI" '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($menuID));
    }


    public static function delMenuPorUsuario($idUsuario, $menuID){
       $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
            . ' WHERE MenuOperaciones.menuMENU = "SI" '
            . 'AND MenuOperaciones.menuPADRE = ?  '
            . 'AND ( (Usuarios.usuarioID = ? OR UsuariosRol.usuarioID = ? )'
            . ' OR MenuOperaciones.menuPUBLICO = "SI" ) '
            .'GROUP BY MenuOperaciones.menuID '
            .'ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($menuID, $idUsuario, $idUsuario));
    }

    public static function menuPadresComponentePorUsuario($idUsuario, $componenteID){
       $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
            . ' WHERE MenuOperaciones.menuMENU = "SI"  '
            . 'AND MenuOperaciones.menuPADRE = 0  '
            . 'AND (  '
            . ' MenuOperaciones.componenteID = ?  '
            . ' AND (  '
            . '     (Usuarios.usuarioID = ? OR UsuariosRol.usuarioID = ? )  '
            . '     OR MenuOperaciones.menuPUBLICO = "SI"  '
            . ' ) '
            . ') '
            . ' '
            .'GROUP BY MenuOperaciones.menuID '
            .'ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array( $componenteID, $idUsuario, $idUsuario));
    }




    public static function delUsuario($idUsuario){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_USUARIO_Y_COMPONENTES
        . ' WHERE (Usuarios.usuarioID = ? OR UsuariosRol.usuarioID = ?) '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($idUsuario, $idUsuario));
    }

    public static function delMenuComponente($componenteID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.componenteID = ? AND MenuOperaciones.menuMENU = "SI" '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($componenteID));
    }

    public static function menuPadresComponente($componenteID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.componenteID = ? AND MenuOperaciones.menuMENU = "SI" AND MenuOperaciones.menuPADRE = 0 '
            .' ORDER BY MenuOperaciones.menuORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($componenteID));
    }













    public static function ultimos30DiasUsuario( $usuarioID, $cantidad = 12 ){
        $Usos = Log::operacionesUltimos30DiasUsuario($usuarioID, $cantidad);
        $Operaciones = array();
        $contador = 0;
        if(count($Usos)){
          foreach($Usos as $UsoOperacion){
              if(empty($UsoOperacion->operacionID)) continue;
              $Operacion =  Operaciones::datosCompletos($UsoOperacion->operacionID);
              if( $Operacion->operacionACCESORAPIDO == 'SI' ){
                  array_push( $Operaciones, $Operacion);
                  $contador++;
              }
              if( $contador >= $cantidad ){
                  break;
              }
          }
        }
        return $Operaciones;
    }

    public static function masUsadasUsuario( $usuarioID, $cantidad = 12 ){
        $Usos = Log::operacionesUsadasUsuario($usuarioID, $cantidad);
        $Operaciones = array();
        $contador = 0;
        foreach($Usos as $UsoOperacion){
            if(empty($UsoOperacion->operacionID)) continue;
            $Operacion =  Operaciones::datosCompletos($UsoOperacion->operacionID);
            if( $Operacion->operacionACCESORAPIDO == 'SI' ){
                array_push( $Operaciones, $Operacion);
                $contador++;
            }
            if( $contador >= $cantidad ){
                break;
            }
        }
        return $Operaciones;
    }

    public static function todos(){
        $sqlQuery = OperacionesSQL::DATOS_COMPLETOS
            .' ORDER BY MenuOperaciones.operacionORDEN ';
        return BasededatosAP::selectVariasFilas($sqlQuery, array());
    }

    public static function datos($operacionID){
        $sqlQuery = OperacionesSQL::DATOS
            . ' WHERE MenuOperaciones.operacionID = ? ';
        return BasededatosAP::selectUnaFila($sqlQuery, array($operacionID));
    }

    public static function datosCompletos($operacionID){
        $sqlQuery = OperacionesSQL::DATOS_COMPLETOS
            . ' WHERE MenuOperaciones.operacionID = ? ';
        return BasededatosAP::selectUnaFila($sqlQuery, array($operacionID));
    }

    public static function todosDelComponente($componenteID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES . ' WHERE MenuComponentes.componenteID = ? '
            .'ORDER BY MenuOperaciones.operacionORDEN ';
        return BasededatosAP::selectVariasFilas($sqlQuery, array($componenteID));
    }

    public static function delControlador($controladorID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            . ' WHERE MenuOperaciones.controladorID = ? '
            .'';
        return BasededatosAP::selectVariasFilas($sqlQuery, array($controladorID));
    }

    public static function delMenuControlador($controladorID){
        $sqlQuery = ControlAccesoSQL::OPERACIONES_POR_COMPONENTES
            .' WHERE MenuOperaciones.controladorID = ? AND MenuOperaciones.operacionMENU = "SI" '
            .' ORDER BY MenuOperaciones.operacionORDEN ' ;
        return BasededatosAP::selectVariasFilas($sqlQuery, array($controladorID));
    }

    public static function datosPorCodigo($codigoOperacion){
        $sqlQuery = ControlAccesoSQL::DATOS_COMPLETOS . 'WHERE MenuOperaciones.operacionCODIGO = ? ';
        return BasededatosAP::selectUnaFila($sqlQuery, array($codigoOperacion));
    }

    public static function datosPorCombinacion($componenteCODIGO, $menuCONTROLADOR, $menuOPERACION ){
        $sqlQuery = ControlAccesoSQL::DATOS_COMPLETOS .
            ' WHERE LOWER(MenuComponentes.componenteCODIGO) = ? '.
            ' AND LOWER(MenuOperaciones.menuCONTROLADOR) = ? '.
            ' AND LOWER(MenuOperaciones.menuOPERACION) = ? ';
        return BasededatosAP::selectUnaFila($sqlQuery,
            array(strtolower($componenteCODIGO), strtolower($menuCONTROLADOR), strtolower($menuOPERACION))
        );
    }

    //
    //
    ///
    ///
    /////
    /////
    /////////
    /////////



    public static function guardar( $controladorID , $operacionCODIGO , $operacionTITULO ,
        $menuOPERACION , $operacionNOMBRETAB , $operacionMENU , $operacionMENUICONO , $operacionMENUTITULO ) {
        $sqlQuery = "INSERT INTO BasededatosAP.MenuOperaciones ( "
        ."controladorID , operacionCODIGO , operacionTITULO , menuOPERACION ,  "
        ."operacionNOMBRETAB , operacionMENU , operacionMENUICONO , operacionMENUTITULO , operacionUSRCREO  "
        .") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        return BasededatosAP::insertFila(
            $sqlQuery, array(
                $controladorID , $operacionCODIGO , $operacionTITULO , $menuOPERACION , $operacionNOMBRETAB ,
                $operacionMENU , $operacionMENUICONO , $operacionMENUTITULO, Cliente::usuarioID()
            )
        );
    }


}