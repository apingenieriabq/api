<?php

class DepartamentosControlador extends Controladores {

  function todos(){
    $P = new Departamentos();
    return Respuestassistema::exito("Todos los departamentos del sistema",$P->todos());
  }

  function deColombia(){
    $P = new Departamentos();
    return Respuestassistema::exito("Todos los departamentos de de colombia",$P->delPais(Paises::COLOMBIA));
  }

  function delPais(){
    if(isset($this->paisID)){
      $P = new Departamentos();
      return Respuestassistema::exito("Todos los departamentos de de colombia",$P->delPais($this->paisID));
    }else{
      return Respuestassistema::error("No llego paisID. Verifique los datos, o contacte al Centro TICS.");
    }
  }

}