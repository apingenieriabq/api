<?php
class ControlConsecutivos extends ModeloDatos
{
  public function __construct($consecutivoID = null) {
    return parent::__construct('ControlConsecutivos', 'consecutivoID', $consecutivoID);
  }

  function valor($consecutivoCODIGO){
     return $this->consultaUNO(['consecutivoCODIGO'=> $consecutivoCODIGO]);
  }

  function actualizar($consecutivoCODIGO, $consecutivoACTUAL){
       return $this->actualiza(
           array("consecutivoACTUAL" =>$consecutivoACTUAL), 
           array( "consecutivoCODIGO" =>$consecutivoCODIGO )
        );
  }
  
}


class Consecutivos extends ControlConsecutivos {
    
}