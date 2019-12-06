<?php

class MunicipiosControlador extends Controladores {

  function todos(){
    $P = new Municipios();
    return Respuestassistema::exito("Todos los parametros del sistema",$P->todos());
  }


  function delDepartamento(){
    if(isset($this->departamentoID)){
      $P = new Municipios();
      return Respuestassistema::exito("Todos los Municipios del Departamento [".$this->departamentoID."]",$P->delDepartamento($this->departamentoID));
    }else{
      return Respuestassistema::error("No llego departamentoID. Verifique los datos, o contacte al Centro TICS.");
    }
  }

}