<?php

class Municipios extends ModeloDatos {

  public function __construct($municipioID = null) {
    return parent::__construct('Municipios', 'municipioID', $municipioID);
  }

  function delDepartamento($departamentoID){
     return $this->todos(['departamentoID'=> $departamentoID]);
  }

}