<?php

class ProcesosAP extends ModeloDatos {

    public $documentos;

    public function __construct($procesoID = null) {
        return parent::__construct('ProcesosAP', 'procesoID', $procesoID);
    }

    function datosCompletos($procesoID) {
        $Proceso = $this->porID($procesoID);
        if (!empty($Proceso)) {
            $Proceso->Responsable = new Colaboradores($Proceso->procesoRESPONSABLE);
            $Proceso->Documentos = $this->documentos($Proceso->procesoID);
        }
        return $Proceso;
    }

    function todosConResponsables() {
        $Procesos = $this->todos();
        foreach ($Procesos as $i => $Proceso) {
            $Procesos[$i]->Responsable = new Colaboradores($Proceso->procesoRESPONSABLE);
        }
        return $Procesos;
    }

    function todosCompletos() {
        $Procesos = $this->todos();
        foreach ($Procesos as $i => $Proceso) {
            $Procesos[$i]->Documentos = $this->documentos($Proceso->procesoID);
        }
        return $Procesos;
    }

    function todosConDocumentos() {
        $Procesos = $this->todos();
        foreach ($Procesos as $i => $Proceso) {
            $Procesos[$i]->Documentos = $this->documentos($Proceso->procesoID);
        }
        return $Procesos;
    }

    private static function generarCodigo($procesoTITULO) {
        return strtoupper(substr($procesoTITULO, 0, 4));
    }

    public function guardarSoloTitulo($procesoTITULO) {
        $procesoCODIGO = self::generarCodigo($procesoTITULO);
        $procesoID = $this->insertar([
            'procesoCODIGO' => $procesoCODIGO,
            'procesoTITULO' => $procesoTITULO,
            'procesoUSRCREA' => Usuario::id()
        ]);
        return $this->porID($procesoID);
    }

    function documentos($procesoID) {
        $DocumentosAP = new DocumentosAP();
        return $this->documentos = $DocumentosAP->todosDelProceso($procesoID);
    }

    function delUsuario() {
        $sql = "SELECT `ProcesosAP`.* FROM `DocumentosAP` LEFT JOIN `ProcesosAP` ON (`DocumentosAP`.`procesoID` = `ProcesosAP`.`procesoID`) LEFT JOIN `DocumentosUsuarios` ON (`DocumentosUsuarios`.`documentoID` = `DocumentosAP`.`documentoID`) WHERE (  `DocumentosAP`.`documentoPUBLICO` = 'SI' OR `DocumentosUsuarios`.`usuarioID` = :usuarioID ) GROUP BY `ProcesosAP`.`procesoID`; ";
        return $Procesos = $this->consultaMUCHOS($sql, [':usuarioID' => Usuario::id()]);
    }

    public static function guardar($procesoCODIGO, $procesoTITULO, $procesoDESCRIPCION, $procesoRESPONSABLE) {
        $sqlQuery = ProcesosSQL::CREAR_REGISTRO;
        return BasededatosAP::insertFila($sqlQuery, array($procesoCODIGO, $procesoTITULO, $procesoDESCRIPCION, $procesoRESPONSABLE, Usuario::usuarioID()));
    }

    public static function actualizar($procesoID, $procesoCODIGO, $procesoTITULO, $procesoDESCRIPCION, $procesoRESPONSABLE) {
        $sqlQuery = ProcesosSQL::ACTUALIZAR_REGISTRO;
        return BasededatosAP::actualizarFila(
            $sqlQuery, array(
              $procesoCODIGO, $procesoTITULO, $procesoDESCRIPCION, $procesoRESPONSABLE, Usuario::usuarioID(), $procesoID
            )
        );
    }

    public function actualizarESTADO($procesoESTADO, $procesoID = null) {
        if (is_null($procesoID)) {
            $procesoID = $this->procesoID;
        }
        return $this->actualiza(['procesoESTADO' => $procesoESTADO], ['procesoID' => $procesoID]);
    }

    /**
     * Recibe un identificador de ControlConsecutivos y elimina el registro.
     * @param int $consecutivosID Identificador del registro
     * ha eliminar.
     * @return int Cantidad de registros eliminados
     */
    public static function eliminar($procesoID) {
        $sqlQuery = ProcesosSQL::ELIMINAR_REGISTRO;
        return BasededatosAP::actualizarFila($sqlQuery, array($procesoID));
    }

    public static function asignarAplicacion($aplicacionID, $procesoID) {
        $sqlQuery = ProcesosSQL::ASIGNAR_APLICACION;
        return BasededatosAP::insertFila(
            $sqlQuery, array(
              $aplicacionID, $procesoID, Usuario::usuarioID()
            )
        );
    }

    public static function eliminarAsignacionAplicacion($aplicacionID, $procesoID) {
        $sqlQuery = ProcesosSQL::ELIMINAR_REGISTRO_ASIGNACION_APLICACION;
        return BasededatosAP::actualizarFila($sqlQuery, array($aplicacionID, $procesoID));
    }

}
