<?php
class Parametros extends ModeloDatos
{


  public function __construct($parametroID = null) {
    return parent::__construct('Parametros', 'parametroID', $parametroID);
  }

    // public static function datos($parametroID)
    // {
    //     $sqlQuery = ParametrosSQL::DATOS_COMPLETOS . " WHERE Parametros.parametroID = ? ";
    //     return BasededatosAP::selectUnaFila($sqlQuery, array($parametroID));
    // }

    public static function deLaAplicacion($aplicacionID)
    {
        $sqlQuery = ParametrosSQL::DATOS_COMPLETOS . " WHERE ParametrosAplicaciones.aplicacionID = ? ";
        return BasededatosAP::selectVariasFilas($sqlQuery, array($aplicacionID));
    }

    // public static function todos()
    // {
    //     $sqlQuery = ParametrosSQL::DATOS_COMPLETOS;
    //     return BasededatosAP::selectVariasFilas($sqlQuery, array());
    // }

    public static function valor($codigoParametro)
    {
        $sqlQuery =  ParametrosSQL::DATOS_COMPLETOS . " WHERE Parametros.parametroCODIGO = ?; ";
        $Parametro = BasededatosAP::selectUnaFila($sqlQuery, array($codigoParametro));
        switch ($Parametro->parametroTIPO) {
            case 'COLABORADOR':
                return Colaboradores::porId($Parametro->parametroVALOR);
                break;
            default:
                return $Parametro->parametroVALOR;
                break;
        }
        return null;
    }

    public static function tipos()
    {
        $sqlQuery = ParametrosSQL::TIPOS_PARAMETROS;
        $consulta = BasededatosAP::selectUnaFila($sqlQuery, array());
        $tipos = explode("','", substr($consulta->Type, 6, -2));
        return $tipos;
    }

    public static function guardar($parametroTIPO, $parametroCODIGO, $parametroTITULO, $parametroDESCRIPCION, $parametroVALOR)
    {
        $sqlQuery = ParametrosSQL::CREAR_REGISTRO;
        return BasededatosAP::insertFila($sqlQuery,array($parametroTIPO, $parametroCODIGO, $parametroTITULO, $parametroDESCRIPCION, $parametroVALOR, Usuario::usuarioID()));
    }

    public static function actualizar($parametroID, $parametroTIPO, $parametroCODIGO, $parametroTITULO, $parametroDESCRIPCION, $parametroVALOR)
    {
        $sqlQuery = ParametrosSQL::ACTUALIZAR_REGISTRO;
        return BasededatosAP::actualizarFila(
            $sqlQuery,
            array(
            $parametroTIPO, $parametroCODIGO,
            $parametroTITULO, $parametroDESCRIPCION,
            $parametroVALOR, Usuario::usuarioID(), $parametroID
            )
        );
    }

    /**
     * Recibe un identificador de ControlConsecutivos y elimina el registro.
     * @param int $consecutivosID Identificador del registro
     * ha eliminar.
     * @return int Cantidad de registros eliminados
     */
    public static function eliminar($parametroID)
    {
        $sqlQuery = ParametrosSQL::ELIMINAR_REGISTRO;
        return BasededatosAP::actualizarFila($sqlQuery, array($parametroID));
    }

    public static function asignarAplicacion($aplicacionID, $parametroID)
    {
        $sqlQuery = ParametrosSQL::ASIGNAR_APLICACION;
        return BasededatosAP::insertFila(
            $sqlQuery,
            array(
               $aplicacionID, $parametroID, Usuario::usuarioID()
            )
        );
    }

    public static function eliminarAsignacionAplicacion($aplicacionID, $parametroID)
    {
        $sqlQuery = ParametrosSQL::ELIMINAR_REGISTRO_ASIGNACION_APLICACION;
        return BasededatosAP::actualizarFila($sqlQuery, array($aplicacionID, $parametroID));
    }
}
