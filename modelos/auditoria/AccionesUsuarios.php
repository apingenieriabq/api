<?php

class AccionesUsuarios extends ModeloAuditoria {

   var $accionID;
   public function __construct($accionCOMPONENTE, $accionCONTROLADOR, $accionOPERACION, $usuarioNOMBRE, $accionIP = null, $usuarioID = null)
   {
       self::$nombreTabla = 'AccionesUsuarios';
       $this->nuevo($operacionID = null, $accionCOMPONENTE, $accionCONTROLADOR, $accionOPERACION, $usuarioNOMBRE, $accionIP, $usuarioID );
   }


  function nuevo( $operacionID, $accionCOMPONENTE, $accionCONTROLADOR, $accionOPERACION, $usuarioNOMBRE, $accionIP, $usuarioID = null){
      $this->accionID = self::insertar(array(
        'usuarioID' => $usuarioID,
        'usuarioNOMBRE' => $usuarioNOMBRE,
        'operacionID' => $operacionID,
        'accionIP' => $accionIP,
        'accionCOMPONENTE' => $accionCOMPONENTE,
        'accionCONTROLADOR' => $accionCONTROLADOR,
        'accionOPERACION'  => $accionOPERACION
      ));
  }

  function respuesta( $accionRESPUESTA ){
      $registros = self::actualizar(array(
        'accionRESPUESTA' => $accionRESPUESTA,
      ), ['accionID' => $this->accionID]);
  }


}