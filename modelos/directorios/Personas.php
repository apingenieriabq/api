<?php
class Personas extends ModeloDatos {
  public function __construct($personaID = null, $personaIDENTIFICACION = null) {
    if(!is_null($personaIDENTIFICACION)){
      parent::__construct('Personas', 'personaID');
      $this->datos(['tipoIdentificacionID' => $personaID, 'personaIDENTIFICACION' => $personaIDENTIFICACION]);
    }else{
      parent::__construct('Personas', 'personaID', $personaID);
    }
  }

  function crear($tipoIdentificacionID, $personaIDENTIFICACION, $personaNOMBRES, $personaAPELLIDOS, $personaMUNICIPIO, $personaDIRECCION, $personaEMAIL,
  $personaTELEFONO = null, $personaCELULAR = null, $personaNIT = null, $personaIMAGEN = null, $personaSEXO = null, $personaFCHNACIMIENTO = null, $personaTIPOSANGRE = null){
    return $this->personaID = $this->insertar([
      'tipoIdentificacionID' => $tipoIdentificacionID,
      'personaIDENTIFICACION' => $personaIDENTIFICACION,
      'personaNIT' => $personaNIT,
      'personaRAZONSOCIAL' => $personaNOMBRES." ".$personaAPELLIDOS,
      'personaNOMBRES' => $personaNOMBRES,
      'personaAPELLIDOS' => $personaAPELLIDOS,
      'personaMUNICIPIO' => $personaMUNICIPIO,
      'personaDIRECCION' => $personaDIRECCION,
      'personaTELEFONO' => $personaTELEFONO,
      'personaCELULAR' => $personaCELULAR,
      'personaEMAIL' => $personaEMAIL,
      'personaIMAGEN' => $personaIMAGEN,
      'personaFCHNACIMIENTO' => $personaFCHNACIMIENTO,
      'personaSEXO' => $personaSEXO,
      'personaTIPOSANGRE' => $personaTIPOSANGRE,
      'personaUSRCREA' => Usuario::id(),
   ]);
  }

  function modificar($tipoIdentificacionID, $personaIDENTIFICACION, $personaNOMBRES, $personaAPELLIDOS, $personaMUNICIPIO, $personaDIRECCION, $personaEMAIL, $personaTELEFONO = null, $personaCELULAR = null, $personaNIT = null, $personaIMAGEN = null, $personaSEXO = null, $personaFCHNACIMIENTO = null, $personaTIPOSANGRE = null, $personaID = null ){
    if(is_null($personaID)){
      $personaID = $this->personaID;
    }
    return $this->actualiza([
      'tipoIdentificacionID' => $tipoIdentificacionID,
      'personaIDENTIFICACION' => $personaIDENTIFICACION,
      'personaNIT' => $personaNIT,
      'personaRAZONSOCIAL' => $personaNOMBRES." ".$personaAPELLIDOS,
      'personaNOMBRES' => $personaNOMBRES,
      'personaAPELLIDOS' => $personaAPELLIDOS,
      'personaMUNICIPIO' => $personaMUNICIPIO,
      'personaDIRECCION' => $personaDIRECCION,
      'personaTELEFONO' => $personaTELEFONO,
      'personaCELULAR' => $personaCELULAR,
      'personaEMAIL' => $personaEMAIL,
      'personaIMAGEN' => $personaIMAGEN,
      'personaFCHNACIMIENTO' => $personaFCHNACIMIENTO,
      'personaSEXO' => $personaSEXO,
      'personaTIPOSANGRE' => $personaTIPOSANGRE,
      'personaFCHMODIFICADO' => date('Y-m-d h:i:s'),
      'personaUSRMODIFICA' => Usuario::id(),
      ] , ['personaID' => $personaID ]
    );
  }


  function modificarDatosBasicos($personaNOMBRES, $personaAPELLIDOS, $personaMUNICIPIO, $personaDIRECCION, $personaEMAIL, $personaID = null ){
    if(is_null($personaID)){
      $personaID = $this->personaID;
    }
    return $this->actualiza([
      'personaRAZONSOCIAL' => $personaNOMBRES." ".$personaAPELLIDOS,
      'personaNOMBRES' => $personaNOMBRES,
      'personaAPELLIDOS' => $personaAPELLIDOS,
      'personaMUNICIPIO' => $personaMUNICIPIO,
      'personaDIRECCION' => $personaDIRECCION,
      'personaEMAIL' => $personaEMAIL,
      'personaFCHMODIFICADO' => date('Y-m-d h:i:s'),
      'personaUSRMODIFICA' => Usuario::id(),
      ] , ['personaID' => $personaID ]
    );
  }

}