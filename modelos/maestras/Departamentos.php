<?php

class Departamentos extends ModeloDatos {

  public function __construct($departamentoID = null) {
    return parent::__construct('Departamentos', 'departamentoID', $departamentoID);
  }

  function delPais($paisID){
     return $this->todos(['paisID'=> $paisID]);
  }

}