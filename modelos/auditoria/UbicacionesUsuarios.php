<?php

class UbicacionesUsuarios extends ModeloAuditoria {

  var $ubicacionID;
  public function __construct()
  {
     self::$nombreTabla = 'UbicacionesUsuarios';
  }

  function nuevo(
    $usuarioID = null,
    $ubicacionURL = null,
    $ubicacionIP = null,
    $ubicacionCONTINENTE = null,
    $ubicacionPAIS = null,
    $ubicacionCIUDAD = null,
    $ubicacionREGION = null,
    $ubicacionZIP = null,
    $ubicacionLATITUD = null,
    $ubicacionLONGITUD = null,
    $ubicacionCAPITAL = null,
    $ubicacionBANDERA = null,
    $ubicacionINDICATIVO = null
  ){
      $this->ubicacionID = self::insertar(array(
        'usuarioID' =>	$usuarioID,
        'ubicacionURL' => $ubicacionURL,
        'ubicacionIP' =>	$ubicacionIP,
        'ubicacionCONTINENTE' =>	$ubicacionCONTINENTE,
        'ubicacionPAIS' =>	$ubicacionPAIS,
        'ubicacionCIUDAD' =>	$ubicacionCIUDAD,
        'ubicacionREGION' =>	$ubicacionREGION,
        'ubicacionZIP' =>	$ubicacionZIP,
        'ubicacionLATITUD' =>	$ubicacionLATITUD,
        'ubicacionLONGITUD' =>	$ubicacionLONGITUD,
        'ubicacionCAPITAL' =>	$ubicacionCAPITAL,
        'ubicacionBANDERA' =>	$ubicacionBANDERA,
        'ubicacionINDICATIVO' =>	$ubicacionINDICATIVO
      ));
  }

}