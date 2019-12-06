<?php
class DocumentosAP extends ModeloDatos
{


  public function __construct($documentoID = null) {
    return parent::__construct('DocumentosAP', 'documentoID', $documentoID);
  }
  
  public function buscarPorPalabras($palabras) {
      return $this->todos([ 'documentoNOMBRE[~]' => $palabras]);
  }

  public function todosCompletos(){
    $Documentos = $this->consultaMUCHOS(  DocumentosAPSQL::DATOS_COMPLETOS);
    return $Documentos;
  }

  public function datosCompletos($documentoID=null){
    if(is_null($documentoID)){
      $documentoID =  $this->documentoID;
    }
    $Documento = $this->consultaUNO(  DocumentosAPSQL::DATOS_COMPLETOS." WHERE DocumentosAP.documentoID = :documentoID ", [':documentoID' => $documentoID] );
    return $Documento;
  }

  public function actualizarIMAGEN($documentoIMAGEN, $documentoID = null){
    if(is_null($documentoID)){
      $documentoID = $this->documentoID;
    }
    return $this->actualiza([ 'documentoIMAGEN' => $documentoIMAGEN], [ 'documentoID' => $documentoID] );
  }


 


  public function actualizarESTADO($documentoESTADO , $documentoID = null){
    if(is_null($documentoID)){
      $documentoID = $this->documentoID;
    }
    return $this->actualiza([ 'documentoESTADO' => $documentoESTADO], [ 'documentoID' => $documentoID] );
  }
  public function actualizarVISIBILIDAD($documentoPUBLICADO , $documentoID = null){
    if(is_null($documentoID)){
      $documentoID = $this->documentoID;
    }
    return $this->actualiza([ 'documentoPUBLICADO' => $documentoPUBLICADO], [ 'documentoID' => $documentoID] );
  }
  public function actualizarSEGURIDAD($documentoPUBLICADO , $documentoID = null){
    if(is_null($documentoID)){
      $documentoID = $this->documentoID;
    }
    return $this->actualiza([ 'documentoPUBLICO' => $documentoPUBLICADO], [ 'documentoID' => $documentoID] );
  }













  public function todosSinProceso($documentoPUBLICADO = null){
    if(!is_null($documentoPUBLICADO)){
      return $this->todos([ 'procesoID' => null, 'documentoPUBLICADO' => $documentoPUBLICADO ]);
    }
    return $this->todos([ 'procesoID' => null]);
  }

  public function todosSinProcesoDelUsuario(){
    return $this->todos([ 'procesoID' => null]);
  }

  public function todosDelProceso($procesoID, $documentoPUBLICADO = null){
    if(!is_null($documentoPUBLICADO)){
      return $this->todos([ 'procesoID' => $procesoID, 'documentoPUBLICADO' => $documentoPUBLICADO ]);
    }
    return $this->todos([ 'procesoID' => $procesoID]);
  }

  public function todosDelProcesoDelUsuario($procesoID, $documentoPUBLICADO = null){
    if(!is_null($documentoPUBLICADO)){
     $sql = "SELECT DocumentosAP.* , DocumentosUsuarios.documentoUsuarioID , DocumentosUsuarios.documentoUsuarioFCHASIGNACION FROM DocumentosAP "
      ." LEFT JOIN DocumentosUsuarios ON (DocumentosAP.documentoID = DocumentosUsuarios.documentoID ) "
      ." WHERE (`DocumentosAP`.`documentoPUBLICO` = 'SI' OR  DocumentosUsuarios.usuarioID = :usuarioID ) AND DocumentosAP.procesoID = :procesoID AND DocumentosAP.documentoPUBLICADO = :documentoPUBLICADO   ";
      $Documentos = $this->consultaMUCHOS($sql, [':procesoID' => $procesoID, ':documentoPUBLICADO' => $documentoPUBLICADO, ':usuarioID' => Usuario::id() ] );
      return $Documentos;
    }

    return $Documentos = $this->consultaMUCHOS(
      "SELECT DocumentosAP.* , DocumentosUsuarios.documentoUsuarioID , DocumentosUsuarios.documentoUsuarioFCHASIGNACION FROM DocumentosUsuarios "
      ." LEFT JOIN DocumentosAP ON (DocumentosUsuarios.documentoID = DocumentosAP.documentoID) "
      ." WHERE DocumentosUsuarios.usuarioID = :usuarioID AND DocumentosAP.procesoID = :procesoID ",
      [ ':procesoID' => $procesoID, ':usuarioID' => Usuario::id() ] );
  }

  private function generarCodigo($procesoID){
    $Proceso = new ProcesosAP($procesoID);
    $CantDocumentos = count($this->todosDelProceso($procesoID));
    $documentoCODIGO = $Proceso->procesoCODIGO."-"
      .str_pad($Proceso->procesoID,2,"0",STR_PAD_LEFT).""
      .str_pad($CantDocumentos, 2, "0", STR_PAD_LEFT);
    return $documentoCODIGO;
  }




  public function nuevo( $procesoID, $documentoVERSION , $documentoPUBLICADO , $documentoNOMBRE , $documentoCONTENIDO ,
    $documentoURL , $documentoRESPONSABLE , $documentoOBSERVACIONES){

    $nuevo = $this->insertar([ 'procesoID' => $procesoID ,
          'documentoCODIGO' => $this->generarCodigo($procesoID) ,
          'documentoVERSION' => $documentoVERSION ,
          'documentoPUBLICADO' => $documentoPUBLICADO ,
          'documentoNOMBRE' => $documentoNOMBRE ,
          'documentoCONTENIDO' => $documentoCONTENIDO ,
          'documentoURL' => $documentoURL ,
          'documentoIMAGEN' => 'images/logo-ap2.png',
          'documentoRESPONSABLE' => $documentoRESPONSABLE ,
          'documentoOBSERVACIONES' => $documentoOBSERVACIONES,
          'documentoUSRCREACION' => Usuario::id(),
          'documentoUSRACTUALIZACION' => Usuario::id()
        ]);
    return $this->porID($nuevo);
  }
  public function cambios( $documentoID, $procesoID, $documentoVERSION , $documentoCODIGO, $documentoFCHACTUALIZACION,  $documentoPUBLICADO , $documentoNOMBRE , $documentoCONTENIDO ,
    $documentoURL , $documentoRESPONSABLE , $documentoOBSERVACIONES){

    $actualizado = $this->actualiza([ 'procesoID' => $procesoID ,
          'documentoVERSION' => $documentoVERSION ,
        'documentoCODIGO' => $documentoCODIGO,
          'documentoPUBLICADO' => $documentoPUBLICADO ,
          'documentoNOMBRE' => $documentoNOMBRE ,
          'documentoCONTENIDO' => $documentoCONTENIDO ,
          'documentoURL' => $documentoURL ,
          'documentoRESPONSABLE' => $documentoRESPONSABLE ,
          'documentoOBSERVACIONES' => $documentoOBSERVACIONES,
          'documentoFCHACTUALIZACION' => $documentoFCHACTUALIZACION,
          'documentoUSRACTUALIZACION' => Usuario::id()
        ], ['documentoID' => $documentoID ]);
    return $this->porID($documentoID);
  }







    public static function guardar($documentoCODIGO, $documentoTITULO, $documentoDESCRIPCION, $documentoRESPONSABLE)
    {
        $sqlQuery = DocumentosSQL::CREAR_REGISTRO;
        return BasededatosAP::insertFila($sqlQuery,array($documentoCODIGO, $documentoTITULO, $documentoDESCRIPCION, $documentoRESPONSABLE, Usuario::usuarioID()));
    }

    public static function actualizar($documentoID, $documentoCODIGO, $documentoTITULO, $documentoDESCRIPCION, $documentoRESPONSABLE)
    {
        $sqlQuery = DocumentosSQL::ACTUALIZAR_REGISTRO;
        return BasededatosAP::actualizarFila(
            $sqlQuery,
            array(
            $documentoCODIGO, $documentoTITULO, $documentoDESCRIPCION, $documentoRESPONSABLE, Usuario::usuarioID(), $documentoID
            )
        );
    }

    /**
     * Recibe un identificador de ControlConsecutivos y elimina el registro.
     * @param int $consecutivosID Identificador del registro
     * ha eliminar.
     * @return int Cantidad de registros eliminados
     */
    public static function eliminar($documentoID)
    {
        $sqlQuery = DocumentosSQL::ELIMINAR_REGISTRO;
        return BasededatosAP::actualizarFila($sqlQuery, array($documentoID));
    }


} 
